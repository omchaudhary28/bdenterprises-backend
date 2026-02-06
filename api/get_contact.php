<?php
/**
 * Get Single Contact Endpoint
 * GET /api/get_contact?id=123
 * 
 * Returns a single contact by ID
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

// Get ID from query parameter
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid or missing contact ID',
        'data' => []
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            id, first_name, last_name, email, phone, 
            company_name, service_type, message, 
            preferred_contact_method, status, 
            created_at, updated_at
        FROM contact_submissions
        WHERE id = ?
    ");
    
    $stmt->execute([$id]);
    $contact = $stmt->fetch();
    
    if (!$contact) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Contact not found',
            'data' => []
        ]);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Contact retrieved successfully',
        'data' => $contact
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error retrieving contact',
        'data' => []
    ]);
}
?>
