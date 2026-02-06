<?php
/**
 * Social Media Links Endpoint
 * GET /api/social_media
 * 
 * Returns active social media links
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
        SELECT id, platform, url, icon_name
        FROM social_media_links
        WHERE is_active = TRUE
        ORDER BY platform ASC
    ");
    
    $stmt->execute();
    $social_media = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'message' => 'Social media links retrieved successfully',
        'data' => $social_media
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error retrieving social media links',
        'data' => []
    ]);
}
?>
