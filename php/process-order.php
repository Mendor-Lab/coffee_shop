<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/bootstrap.php';
require_once 'supabase-client.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$rawData = file_get_contents('php://input');
$orderData = json_decode($rawData, true);

if (!$orderData) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid order data']);
    exit;
}

$customerName = isset($orderData['customer_name']) ? trim($orderData['customer_name']) : '';
$customerEmail = isset($orderData['customer_email']) ? trim($orderData['customer_email']) : '';
$customerPhone = isset($orderData['customer_phone']) ? trim($orderData['customer_phone']) : '';
$deliveryAddress = isset($orderData['delivery_address']) ? trim($orderData['delivery_address']) : '';
$items = isset($orderData['items']) ? $orderData['items'] : [];
$subtotal = isset($orderData['subtotal']) ? floatval($orderData['subtotal']) : 0;
$tax = isset($orderData['tax']) ? floatval($orderData['tax']) : 0;
$total = isset($orderData['total']) ? floatval($orderData['total']) : 0;

if (empty($customerName) || empty($customerEmail) || empty($customerPhone) || empty($deliveryAddress) || empty($items)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// CSRF check
$csrfHeader = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
if (!$csrfHeader || !hash_equals($_SESSION['csrf_token'] ?? '', $csrfHeader)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

// Recompute totals from server-side prices
$menuPath = __DIR__ . '/../data/menu.json';
$menuJson = file_exists($menuPath) ? file_get_contents($menuPath) : '';
$menu = $menuJson ? json_decode($menuJson, true) : [];
$menuMap = [];
foreach ($menu as $m) { if (isset($m['id'])) { $menuMap[(int)$m['id']] = $m; } }

$sanitizedItems = [];
$computedSubtotal = 0.0;
foreach ($items as $it) {
    $id = isset($it['id']) ? (int)$it['id'] : 0;
    $qty = isset($it['quantity']) ? (int)$it['quantity'] : 0;
    if ($id <= 0 || $qty <= 0 || !isset($menuMap[$id])) { continue; }
    $price = (float)$menuMap[$id]['price'];
    $name = (string)$menuMap[$id]['name'];
    $image = isset($menuMap[$id]['image']) ? (string)$menuMap[$id]['image'] : '';
    $lineTotal = $price * $qty;
    $computedSubtotal += $lineTotal;
    $sanitizedItems[] = [
        'id' => $id,
        'name' => $name,
        'price' => $price,
        'image' => $image,
        'quantity' => $qty
    ];
}
$computedSubtotal = round($computedSubtotal, 2);
$computedTax = round($computedSubtotal * 0.15, 2);
$computedTotal = round($computedSubtotal + $computedTax, 2);

try {
    $orderId = 'BB' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

    $supabase = new SupabaseClient();

    $data = [
        'order_id' => $orderId,
        'customer_name' => $customerName,
        'customer_email' => $customerEmail,
        'customer_phone' => $customerPhone,
        'delivery_address' => $deliveryAddress,
        'items' => $sanitizedItems,
        'subtotal' => $computedSubtotal,
        'tax' => $computedTax,
        'total' => $computedTotal,
        'status' => 'pending'
    ];

    $result = $supabase->insert('orders', $data);

    if ($result['success']) {
        $logDir = __DIR__ . '/../logs';
        if (!is_dir($logDir)) { @mkdir($logDir, 0700, true); }
        $logFile = $logDir . '/orders.log';
        $logEntry = date('Y-m-d H:i:s') . " | Order ID: $orderId | Customer: $customerName | Email: $customerEmail | Total: R" . number_format($computedTotal, 2) . "\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND);

        echo json_encode([
            'success' => true,
            'message' => 'Order placed successfully',
            'order_id' => $orderId
        ]);
    } else {
        throw new Exception('Failed to save order');
    }
} catch (Exception $e) {
    // Log failure for troubleshooting without exposing internal error details to client
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) { @mkdir($logDir, 0700, true); }
    $logFile = $logDir . '/orders.log';
    $logEntry = date('Y-m-d H:i:s') . " | ERROR saving order | Customer: $customerName | Email: $customerEmail | Total: R" . number_format($computedTotal ?? 0, 2) . " | Reason: " . $e->getMessage() . "\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'There was a problem processing your order. Please try again later.'
    ]);
}
