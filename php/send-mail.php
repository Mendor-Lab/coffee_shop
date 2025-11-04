<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/bootstrap.php';
require_once 'supabase-client.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// CSRF check: header or form field
$csrf = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
if (!$csrf || !hash_equals($_SESSION['csrf_token'] ?? '', $csrf)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

// Simple honeypot: bots fill hidden field
if (!empty($_POST['website'] ?? '')) {
    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been received.']);
    exit;
}

// Simple rate limit via session (30s window)
$now = time();
$last = $_SESSION['last_contact_submit'] ?? 0;
if ($now - $last < 30) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Please wait a bit before sending another message.']);
    exit;
}
$_SESSION['last_contact_submit'] = $now;

try {
    $supabase = new SupabaseClient();

    $data = [
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message,
        'status' => 'unread'
    ];

    $result = $supabase->insert('messages', $data);

    if ($result['success']) {
        $logDir = __DIR__ . '/../logs';
        if (!is_dir($logDir)) { @mkdir($logDir, 0700, true); }
        $logFile = $logDir . '/messages.log';
        $logEntry = date('Y-m-d H:i:s') . " | $name | $email | $subject | (message redacted)\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND);

        echo json_encode([
            'success' => true,
            'message' => 'Thank you! Your message has been sent successfully.'
        ]);
    } else {
        throw new Exception('Failed to save message');
    }
} catch (Exception $e) {
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) { @mkdir($logDir, 0700, true); }
    $logFile = $logDir . '/messages.log';
    $logEntry = date('Y-m-d H:i:s') . " | ERROR saving message | $name | $email | $subject | Reason: " . $e->getMessage() . "\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'There was a problem sending your message. Please try again later.'
    ]);
}
