<?php
/**
 * PHP Backend API Router
 * Entry point for all API requests
 * 
 * For GoDaddy shared hosting compatibility
 * Route requests to appropriate endpoint files
 */

require_once __DIR__ . '/cors.php';

// Enable CORS


// Prefer the rewrite "path" value when available, fallback to URI parsing
$path = $_GET['path'] ?? '';
if ($path === '') {
    $request_uri = $_SERVER['REQUEST_URI'];
    $script_name = dirname($_SERVER['SCRIPT_NAME']);

    // Remove the script directory from the URI
    if (strpos($request_uri, $script_name) === 0) {
        $path = substr($request_uri, strlen($script_name));
    } else {
        $path = $request_uri;
    }

    // Remove query string
    $path = strtok($path, '?');
}

// Remove leading/trailing slashes
$path = trim($path, '/');

// Remove 'api/' prefix if present
if (strpos($path, 'api/') === 0) {
    $path = substr($path, 4);
}

// Route to appropriate handler
if ($path === 'health' || $path === '') {
    require_once __DIR__ . '/health.php';
}
elseif ($path === 'contacts' || $path === 'contacts/') {
    require_once __DIR__ . '/contacts.php';
}
elseif ($path === 'get_contacts' || $path === 'get_contacts/') {
    require_once __DIR__ . '/get_contacts.php';
}
elseif ($path === 'get_contact' || $path === 'get_contact/') {
    require_once __DIR__ . '/get_contact.php';
}
elseif ($path === 'update_status' || $path === 'update_status/') {
    require_once __DIR__ . '/update_status.php';
}
elseif ($path === 'company_info' || $path === 'company_info/') {
    require_once __DIR__ . '/company_info.php';
}
elseif ($path === 'company-info' || $path === 'company-info/') {
    // Also support kebab-case
    require_once __DIR__ . '/company_info.php';
}
elseif ($path === 'social_media' || $path === 'social_media/') {
    require_once __DIR__ . '/social_media.php';
}
elseif ($path === 'social-media' || $path === 'social-media/') {
    // Also support kebab-case
    require_once __DIR__ . '/social_media.php';
}
elseif ($path === 'locations' || $path === 'locations/') {
    require_once __DIR__ . '/locations.php';
}
elseif ($path === 'locations_main' || $path === 'locations_main/' || $path === 'locations/main' || $path === 'locations/main/') {
    require_once __DIR__ . '/locations_main.php';
}
else {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Endpoint not found',
        'data' => []
    ]);
}
?>
