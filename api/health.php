<?php
/**
 * Health Check Endpoint
 * GET /api/health
 * 
 * Returns server status
 */

require_once __DIR__ . '/cors.php';



// Only allow GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed',
        'data' => []
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Server is running',
    'data' => []
]);
?>
