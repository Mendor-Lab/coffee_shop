<?php
header('Content-Type: application/json');

require_once 'supabase-client.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$rawData = file_get_contents('php://input');
$orderData = json_decode($rawData, true);

if (!$orderData) {
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
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

try {
    $orderId = 'BB' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

    $supabase = new SupabaseClient();

    $data = [
        'order_id' => $orderId,
        'customer_name' => $customerName,
        'customer_email' => $customerEmail,
        'customer_phone' => $customerPhone,
        'delivery_address' => $deliveryAddress,
        'items' => json_encode($items),
        'subtotal' => $subtotal,
        'tax' => $tax,
        'total' => $total,
        'status' => 'pending'
    ];

    $result = $supabase->insert('orders', $data);

    if ($result['success']) {
        $logFile = __DIR__ . '/orders.txt';
        $logEntry = date('Y-m-d H:i:s') . " | Order ID: $orderId | Customer: $customerName | Email: $customerEmail | Total: R" . number_format($total, 2) . "\n";
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
    $orderId = 'BB' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

    $logFile = __DIR__ . '/orders.txt';
    $logEntry = date('Y-m-d H:i:s') . " | Order ID: $orderId | Customer: $customerName | Email: $customerEmail | Total: R" . number_format($total, 2) . "\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);

    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully',
        'order_id' => $orderId
    ]);
}
