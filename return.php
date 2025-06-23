<?php
require('../../config.php');

require_login();

$orderid    = required_param('orderid', PARAM_RAW);
$userid     = required_param('userid', PARAM_INT);
$instanceid = required_param('instanceid', PARAM_INT);

$instance = $DB->get_record('enrol', ['id' => $instanceid, 'enrol' => 'pesapal'], '*', MUST_EXIST);
$course = $DB->get_record('course', ['id' => $instance->courseid], '*', MUST_EXIST);
$context = context_course::instance($course->id);

// Load plugin config
$config = get_config('enrol_pesapal');

// Get OAuth token
$auth_url = ($config->sandbox) ? 'https://cybqa.pesapal.com/pesapalv3/api/Auth/RequestToken' : 'https://pay.pesapal.com/v3/api/Auth/RequestToken';
$token_payload = [
    'consumer_key' => $config->consumer_key,
    'consumer_secret' => $config->consumer_secret,
];
$token_response = \core\output\curl::post($auth_url, json_encode($token_payload), [
    'Content-Type' => 'application/json',
    'Accept' => 'application/json',
]);
$token_data = json_decode($token_response);
if (empty($token_data->token)) {
    print_error('Unable to get Pesapal auth token.');
}
$token = $token_data->token;

// Create payment request
$payment_url = ($config->sandbox) ? 'https://cybqa.pesapal.com/pesapalv3/api/Transactions/SubmitOrderRequest' : 'https://pay.pesapal.com/v3/api/Transactions/SubmitOrderRequest';

$amount = number_format((float)$instance->cost, 2, '.', '');
$user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);

$payload = json_encode([
    'id' => $orderid,
    'currency' => 'UGX',
    'amount' => $amount,
    'description' => 'Course enrolment for ' . $course->fullname,
    'callback_url' => $CFG->wwwroot . '/enrol/pesapal/ipn.php',
    'notification_id' => $config->notification_id,
    'billing_address' => [
        'email_address' => $user->email,
        'phone_number' => $user->phone1 ?? '',
        'first_name' => $user->firstname,
        'last_name' => $user->lastname
    ]
]);

// Save transaction to DB
$transaction = new \stdClass();
$transaction->orderid = $orderid;
$transaction->userid = $userid;
$transaction->instanceid = $instanceid;
$transaction->status = 'NEW';
$transaction->timecreated = time();
$transaction->timemodified = time();
$DB->insert_record('enrol_pesapal', $transaction);

$response = \core\output\curl::post($payment_url, $payload, [
    'Authorization' => 'Bearer ' . $token,
    'Content-Type' => 'application/json',
    'Accept' => 'application/json',
]);

$data = json_decode($response, true);
if (!empty($data['redirect_url'])) {
    redirect($data['redirect_url']);
} else {
    print_error('Unable to start Pesapal payment.');
}
