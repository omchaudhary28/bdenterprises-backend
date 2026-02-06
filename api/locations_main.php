<?php
/**
 * Main Office Location Endpoint
 * GET /api/locations_main
 * 
 * Returns the main office location
 */

require_once __DIR__ . '/cors.php';

require_once __DIR__ . '/db.php';



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

try {
    $stmt = $pdo->prepare("
        SELECT 
            id, name, latitude, longitude, address, 
            city, state, zip_code, phone, email, 
            is_main_office, is_active, created_at, updated_at
        FROM company_locations
        WHERE is_main_office = TRUE AND is_active = TRUE
        LIMIT 1
    ");
    
    $stmt->execute();
    $location = $stmt->fetch();
    
    if (!$location) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Main office location not found',
            'data' => []
        ]);
        exit;
    }
    
    // Convert numeric fields to proper types
    $location['latitude'] = (float)$location['latitude'];
    $location['longitude'] = (float)$location['longitude'];
    $location['is_main_office'] = (bool)$location['is_main_office'];
    $location['is_active'] = (bool)$location['is_active'];
    
    echo json_encode([
        'success' => true,
        'message' => 'Main office location retrieved successfully',
        'data' => $location
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error retrieving main office location',
        'data' => []
    ]);
}
?>
