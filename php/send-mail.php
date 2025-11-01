<?php
header('Content-Type: application/json');

require_once 'supabase-client.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

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
        $logFile = __DIR__ . '/messages.txt';
        $logEntry = date('Y-m-d H:i:s') . " | $name | $email | $subject | $message\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND);

        echo json_encode([
            'success' => true,
            'message' => 'Thank you! Your message has been sent successfully.'
        ]);
    } else {
        throw new Exception('Failed to save message');
    }
} catch (Exception $e) {
    $logFile = __DIR__ . '/messages.txt';
    $logEntry = date('Y-m-d H:i:s') . " | $name | $email | $subject | $message\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);

    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your message has been received.'
    ]);
}
