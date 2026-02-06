<?php
$allowed_origins = ['https://bdenterprises.in', 'https://www.bdenterprises.in'];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$is_local_dev_origin = preg_match('/^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?$/', $origin) === 1;

if ($origin !== '' && (in_array($origin, $allowed_origins, true) || $is_local_dev_origin)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Vary: Origin');
}
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, Accept');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
