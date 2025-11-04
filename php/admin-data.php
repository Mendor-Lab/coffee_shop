<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/supabase-client.php';

// Localhost-only
$remote = $_SERVER['REMOTE_ADDR'] ?? '';
if (!in_array($remote, ['127.0.0.1', '::1'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden']);
    exit;
}

$resource = $_GET['resource'] ?? '';

function read_logs($type) {
    $dir = __DIR__ . '/../logs';
    $file = $dir . '/' . $type . '.log';
    $items = [];
    if (file_exists($file)) {
        $lines = @array_slice(@file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [], -100);
        foreach (array_reverse($lines) as $line) {
            if ($type === 'orders') {
                // Format: YYYY-mm-dd HH:ii:ss | Order ID: X | Customer: Y | Email: Z | Total: R123.45
                if (preg_match('/^(.*?) \| Order ID: (.*?) \| Customer: (.*?) \| Email: (.*?) \| Total: R([0-9\.,]+)/', $line, $m)) {
                    $items[] = [
                        'order_id' => $m[2],
                        'customer_name' => $m[3],
                        'customer_email' => $m[4],
                        'total' => (float) str_replace([','], [''], $m[5]),
                        'status' => status_from_local($m[2]) ?? 'pending',
                        'created_at' => $m[1]
                    ];
                }
            } else if ($type === 'messages') {
                // Format: YYYY-mm-dd HH:ii:ss | Name | Email | Subject | ...
                if (preg_match('/^(.*?) \| (.*?) \| (.*?) \| (.*?) \|/', $line, $m)) {
                    $items[] = [
                        'name' => $m[2],
                        'email' => $m[3],
                        'subject' => $m[4],
                        'created_at' => $m[1]
                    ];
                }
            }
        }
    }
    return $items;
}

function status_from_local($orderId) {
    $mapFile = __DIR__ . '/../logs/status-map.json';
    if (file_exists($mapFile)) {
        $json = json_decode(file_get_contents($mapFile), true);
        if (isset($json[$orderId])) return $json[$orderId];
    }
    return null;
}

try {
    $supabase = new SupabaseClient();
    $todayIso = date('Y-m-d');

    if ($resource === 'orders') {
        if ($supabase->isConfigured()) {
            // Select latest 50 orders
            $query = http_build_query([
                'select' => 'order_id,customer_name,customer_email,total,status,created_at',
                'order' => 'created_at.desc',
                'limit' => 50
            ]);
            $res = $supabase->select('orders', $query);
            if ($res['success']) {
                echo json_encode(['success' => true, 'data' => $res['data']]);
                exit;
            }
        }
        // Fallback to logs
        $data = read_logs('orders');
        echo json_encode(['success' => true, 'data' => $data, 'fallback' => true]);
        exit;
    }

    if ($resource === 'messages') {
        if ($supabase->isConfigured()) {
            $query = http_build_query([
                'select' => 'name,email,subject,created_at',
                'order' => 'created_at.desc',
                'limit' => 50
            ]);
            $res = $supabase->select('messages', $query);
            if ($res['success']) {
                echo json_encode(['success' => true, 'data' => $res['data']]);
                exit;
            }
        }
        $data = read_logs('messages');
        echo json_encode(['success' => true, 'data' => $data, 'fallback' => true]);
        exit;
    }

    if ($resource === 'stats') {
        // Basic stats for today
        $orders = [];
        if ($supabase->isConfigured()) {
            $query = http_build_query([
                'select' => 'total,status,created_at',
                'created_at' => 'gte.' . $todayIso,
                'limit' => 1000
            ]);
            $res = $supabase->select('orders', $query);
            if ($res['success']) {
                $orders = $res['data'];
            }
        }
        if (!$orders) {
            $orders = array_filter(read_logs('orders'), function($o) use ($todayIso) {
                return strpos($o['created_at'], $todayIso) === 0;
            });
        }
        $count = count($orders);
        $revenue = 0.0; $pending = 0; $ready = 0;
        foreach ($orders as $o) {
            $revenue += (float)($o['total'] ?? 0);
            $st = strtolower($o['status'] ?? 'pending');
            if ($st === 'pending') $pending++;
            if ($st === 'ready') $ready++;
        }
        echo json_encode(['success' => true, 'data' => [
            'orders' => $count,
            'revenue' => round($revenue, 2),
            'pending' => $pending,
            'ready' => $ready
        ]]);
        exit;
    }

    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Unknown resource']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
