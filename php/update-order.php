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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$csrfHeader = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
if (!$csrfHeader || !hash_equals($_SESSION['csrf_token'] ?? '', $csrfHeader)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

$raw = file_get_contents('php://input');
$body = json_decode($raw, true);
$orderId = trim($body['order_id'] ?? '');
$status = strtolower(trim($body['status'] ?? ''));

$allowedStatuses = ['pending','preparing','ready','completed'];
if ($orderId === '' || !in_array($status, $allowedStatuses, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$ok = false; $source = 'local';
try {
    $supabase = new SupabaseClient();
    if ($supabase->isConfigured()) {
        $query = http_build_query(['order_id' => 'eq.' . $orderId]);
        $res = $supabase->update('orders', $query, ['status' => $status]);
        if ($res['success']) { $ok = true; $source = 'supabase'; }
    }
} catch (Throwable $e) { /* ignore, fallback to local */ }

// Persist to local status map regardless; serves as cache/fallback
$mapDir = __DIR__ . '/../logs';
if (!is_dir($mapDir)) { @mkdir($mapDir, 0700, true); }
$mapFile = $mapDir . '/status-map.json';
$map = file_exists($mapFile) ? (json_decode(file_get_contents($mapFile), true) ?: []) : [];
$map[$orderId] = $status;
file_put_contents($mapFile, json_encode($map, JSON_PRETTY_PRINT));

if (!$ok) {
    // Not updated in supabase, but stored locally
    echo json_encode(['success' => true, 'message' => 'Status saved locally', 'source' => $source]);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Status updated', 'source' => $source]);
