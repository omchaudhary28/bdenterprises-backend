<?php
/**
 * Update Contact Status Endpoint
 * POST/PUT /api/update_status
 * Body: { id, status }
 * 
 * Valid statuses: new, in_progress, resolved, closed
 */

require_once __DIR__ . '/cors.php';

require_once __DIR__ . '/db.php';



// Only allow POST and PUT
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed',
        'data' => []
    ]);
    exit;
}

// Get JSON body
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (empty($input['id']) || empty($input['status'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Missing required fields: id, status',
        'data' => []
    ]);
    exit;
}

// Validate status
$valid_statuses = ['new', 'in_progress', 'resolved', 'closed'];
if (!in_array($input['status'], $valid_statuses)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid status. Valid values: ' . implode(', ', $valid_statuses),
        'data' => []
    ]);
    exit;
}

try {
    // Check if contact exists
    $check_stmt = $pdo->prepare("SELECT id FROM contact_submissions WHERE id = ?");
    $check_stmt->execute([$input['id']]);
    
    if (!$check_stmt->fetch()) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Contact not found',
            'data' => []
        ]);
        exit;
    }
    
    // Update status
    $stmt = $pdo->prepare("
        UPDATE contact_submissions
        SET status = ?, updated_at = CURRENT_TIMESTAMP
        WHERE id = ?
    ");
    
    $stmt->execute([$input['status'], $input['id']]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Contact status updated successfully',
        'data' => [
            'id' => (int)$input['id'],
            'status' => $input['status']
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error updating contact status',
        'data' => []
    ]);
}
?>
