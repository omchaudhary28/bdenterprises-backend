<?php
/**
 * Contact Submissions Endpoints
 * POST /api/contacts - Submit new contact
 * GET /api/contacts - Get all contacts
 */

require_once __DIR__ . '/cors.php';

require_once __DIR__ . '/db.php';



// POST - Submit contact form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON body
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    $required = ['firstName', 'lastName', 'email', 'message'];
    $missing = [];
    
    foreach ($required as $field) {
        if (empty($input[$field])) {
            $missing[] = $field;
        }
    }
    
    if (!empty($missing)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Missing required fields: ' . implode(', ', $missing),
            'data' => []
        ]);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO contact_submissions 
            (first_name, last_name, email, phone, company_name, service_type, message, preferred_contact_method, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'new')
        ");
        
        $stmt->execute([
            $input['firstName'],
            $input['lastName'],
            $input['email'],
            $input['phone'] ?? null,
            $input['companyName'] ?? null,
            $input['serviceType'] ?? null,
            $input['message'],
            $input['preferredMethod'] ?? 'email'
        ]);
        
        $id = $pdo->lastInsertId();
        
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Contact submission received successfully',
            'data' => [
                'id' => (int)$id,
                'email' => $input['email']
            ]
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error submitting contact form',
            'data' => []
        ]);
    }
}

// GET - Retrieve all contacts
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
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
}

// Method not allowed
else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed',
        'data' => []
    ]);
}
?>
