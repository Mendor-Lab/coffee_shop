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
    $menuItem = $menuMap[$id];
    $name = (string)($menuItem['name'] ?? '');
    $image = isset($menuItem['image']) ? (string)$menuItem['image'] : '';

    // Recompute unit price using options schema
    $unit = (float)($menuItem['price'] ?? 0);
    $options = isset($it['options']) && is_array($it['options']) ? $it['options'] : [];

    // Sizes
    if (isset($menuItem['sizes']) && is_array($menuItem['sizes'])) {
        $sizes = $menuItem['sizes'];
        $values = isset($sizes['values']) && is_array($sizes['values']) ? $sizes['values'] : [];
        $type = isset($sizes['type']) ? $sizes['type'] : 'delta';
        $chosen = isset($options['size']['label']) ? (string)$options['size']['label'] : null;
        if ($chosen && array_key_exists($chosen, $values)) {
            if ($type === 'absolute') {
                $unit = (float)$values[$chosen];
            } else {
                $unit = (float)($menuItem['price'] ?? 0) + (float)$values[$chosen];
            }
        } else if ($type === 'absolute' && !empty($values)) {
            // Default to first defined absolute size to avoid zero pricing
            $firstKey = array_keys($values)[0];
            $unit = (float)$values[$firstKey];
        }
    }

    $delta = 0.0;
    // Milk
    if (isset($menuItem['milkOptions']) && is_array($menuItem['milkOptions'])) {
        $milk = isset($options['milk']['label']) ? (string)$options['milk']['label'] : null;
        $allowed = isset($menuItem['milkOptions']['values']) && is_array($menuItem['milkOptions']['values']) ? $menuItem['milkOptions']['values'] : [];
        $priceDelta = isset($menuItem['milkOptions']['priceDelta']) && is_array($menuItem['milkOptions']['priceDelta']) ? $menuItem['milkOptions']['priceDelta'] : [];
        if ($milk && in_array($milk, $allowed, true)) {
            $delta += (float)($priceDelta[$milk] ?? 0);
        }
    }

    // Sugar (no price effect but clamp)
    if (isset($menuItem['sugar']) && is_array($menuItem['sugar'])) {
        $minSugar = (int)($menuItem['sugar']['min'] ?? 0);
        $maxSugar = (int)($menuItem['sugar']['max'] ?? 5);
        $sugar = isset($options['sugar']) ? (int)$options['sugar'] : $minSugar;
        $sugar = max($minSugar, min($maxSugar, $sugar));
        $options['sugar'] = $sugar; // sanitized
    }

    // Extra shots
    if (isset($menuItem['extraShots']) && is_array($menuItem['extraShots'])) {
        $minS = (int)($menuItem['extraShots']['min'] ?? 0);
        $maxS = (int)($menuItem['extraShots']['max'] ?? 0);
        $ppu = (float)($menuItem['extraShots']['pricePerUnit'] ?? 0);
        $count = isset($options['extraShots']['count']) ? (int)$options['extraShots']['count'] : 0;
        $count = max($minS, min($maxS, $count));
        $delta += $count * $ppu;
        $options['extraShots'] = [ 'count' => $count, 'unitPrice' => $ppu, 'delta' => $count * $ppu ];
    }

    // Extras checkboxes
    if (isset($menuItem['extras']) && is_array($menuItem['extras'])) {
        $allowedExtras = [];
        foreach ($menuItem['extras'] as $ex) { if (isset($ex['key'])) { $allowedExtras[$ex['key']] = (float)($ex['price'] ?? 0); } }
        $finalExtras = [];
        if (isset($options['extras']) && is_array($options['extras'])) {
            foreach ($options['extras'] as $ex) {
                $key = is_array($ex) ? ($ex['key'] ?? null) : (is_string($ex) ? $ex : null);
                if ($key !== null && array_key_exists($key, $allowedExtras)) {
                    $finalExtras[] = [ 'key' => $key, 'price' => $allowedExtras[$key] ];
                    $delta += (float)$allowedExtras[$key];
                }
            }
        }
        $options['extras'] = $finalExtras;
    }

    $unit = round($unit + $delta, 2);
    $qty = max(1, $qty);
    $lineTotal = round($unit * $qty, 2);
    $computedSubtotal += $lineTotal;

    $sanitizedItems[] = [
        'id' => $id,
        'name' => $name,
        'image' => $image,
        'quantity' => $qty,
        'unit_price' => $unit,
        'line_total' => $lineTotal,
        'options' => $options
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
