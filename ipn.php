<?php
require_once('../../config.php');
require_once($CFG->libdir . '/moodlelib.php');

header('Content-Type: application/json');

// Get raw POST data
$rawInput = file_get_contents('php://input');
$payload = json_decode($rawInput, true);

// Log for debugging (optional)
// file_put_contents(__DIR__.'/ipn_log.txt', $rawInput, FILE_APPEND);

if (!$payload || !isset($payload['order_tracking_id']) || !isset($payload['status'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}

$orderId = $payload['order_tracking_id'];
$status = $payload['status'];

// Find transaction in DB
$transaction = $DB->get_record('enrol_pesapal', ['orderid' => $orderId], '*', MUST_EXIST);
if (!$transaction) {
    http_response_code(404);
    echo json_encode(['error' => 'Transaction not found']);
    exit;
}

// Update transaction status
$transaction->status = $status;
$transaction->timemodified = time();
$DB->update_record('enrol_pesapal', $transaction);

// If payment was successful, enrol user
if ($status === 'COMPLETED') {
    $plugin = enrol_get_plugin('pesapal');
    $instance = $DB->get_record('enrol', ['id' => $transaction->instanceid], '*', MUST_EXIST);
    $user = $DB->get_record('user', ['id' => $transaction->userid], '*', MUST_EXIST);
    $plugin->enrol_user($instance, $user->id, $instance->roleid, time(), 0, 'pesapal');
}

echo json_encode(['message' => 'IPN received']);
