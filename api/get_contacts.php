<?php
/**
 * Get All Contacts Endpoint
 * GET /api/get_contacts
 * 
 * Returns all contact submissions
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
            id, first_name, last_name, email, phone, 
            company_name, service_type, message, 
            preferred_contact_method, status, 
            created_at, updated_at
        FROM contact_submissions
        ORDER BY created_at DESC
    ");
    
    $stmt->execute();
    $contacts = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'message' => 'Contacts retrieved successfully',
        'data' => [
            'contacts' => $contacts,
            'count' => count($contacts)
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error retrieving contacts',
        'data' => []
    ]);
}
?>
