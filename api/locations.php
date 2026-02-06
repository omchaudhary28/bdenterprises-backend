<?php
/**
 * Company Locations Endpoint
 * GET /api/locations
 * 
 * Returns all active office locations
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
        WHERE is_active = TRUE
        ORDER BY is_main_office DESC, name ASC
    ");
    
    $stmt->execute();
    $locations = $stmt->fetchAll();
    
    // Convert numeric fields to proper types
    foreach ($locations as &$location) {
        $location['latitude'] = (float)$location['latitude'];
        $location['longitude'] = (float)$location['longitude'];
        $location['is_main_office'] = (bool)$location['is_main_office'];
        $location['is_active'] = (bool)$location['is_active'];
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Locations retrieved successfully',
        'data' => $locations
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error retrieving locations',
        'data' => []
    ]);
}
?>
