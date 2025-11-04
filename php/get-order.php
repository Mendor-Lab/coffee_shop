<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/supabase-client.php';

// CSRF optional for GET; still accept header if provided for parity
$orderId = trim($_GET['order_id'] ?? '');
if ($orderId === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing order_id']);
    exit;
}

function read_local_order($orderId) {
    $dir = __DIR__ . '/../logs';
    $file = $dir . '/orders.log';
    $createdAt = null; $customer = null; $email = null; $total = null; $status = null;
    if (file_exists($file)) {
        $lines = @array_slice(@file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [], -1000);
        foreach (array_reverse($lines) as $line) {
            if (strpos($line, $orderId) !== false) {
                if (preg_match('/^(.*?) \| Order ID: '.preg_quote($orderId,'/').' \| Customer: (.*?) \| Email: (.*?) \| Total: R([0-9\.,]+)/', $line, $m)) {
                    $createdAt = $m[1];
                    $customer = $m[2];
                    $email = $m[3];
                    $total = (float) str_replace([','], [''], $m[4]);
                    break;
                }
            }
        }
    }
    $mapFile = $dir . '/status-map.json';
    if (file_exists($mapFile)) {
        $json = json_decode(file_get_contents($mapFile), true);
        if (isset($json[$orderId])) $status = $json[$orderId];
    }
    if (!$createdAt) return null;
    return [
        'order_id' => $orderId,
        'customer_name' => $customer,
        'customer_email' => $email,
        'total' => $total,
        'status' => $status ?? 'pending',
        'created_at' => $createdAt
    ];
}

function eta_and_progress($createdAt, $status) {
    // Define target durations (seconds)
    $durations = [
        'pending' => 15*60,
        'preparing' => 10*60,
        'ready' => 0,
        'completed' => 0
    ];
    $steps = ['pending'=>0,'preparing'=>50,'ready'=>90,'completed'=>100];

    $status = strtolower($status ?: 'pending');
    $progress = $steps[$status] ?? 0;

    $etaSeconds = 0;
    if ($status === 'pending' || $status === 'preparing') {
        $createdTs = strtotime($createdAt) ?: time();
        $base = ($status === 'pending') ? $durations['pending'] : $durations['preparing'];
        $elapsed = max(0, time() - $createdTs);
        $etaSeconds = max(0, $base - $elapsed);
        // Ensure non-negative
    }
    return [$etaSeconds, $progress];
}

try {
    $supabase = new SupabaseClient();
    $order = null;
    if ($supabase->isConfigured()) {
        $query = http_build_query([
            'select' => 'order_id,customer_name,customer_email,total,status,created_at',
            'order_id' => 'eq.' . $orderId,
            'limit' => 1
        ]);
        $res = $supabase->select('orders', $query);
        if ($res['success'] && !empty($res['data'])) {
            $order = $res['data'][0];
        }
    }
    if (!$order) {
        $order = read_local_order($orderId);
    }

    if (!$order) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    list($etaSeconds, $progress) = eta_and_progress($order['created_at'] ?? '', $order['status'] ?? 'pending');

    echo json_encode([
        'success' => true,
        'data' => $order,
        'eta_seconds' => $etaSeconds,
        'progress' => $progress
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
