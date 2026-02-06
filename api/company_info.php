<?php
/**
 * Company Contact Information Endpoint
 * GET /api/company_info
 * 
 * Returns company contact details (phone, email, address, whatsapp)
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
        SELECT id, contact_type, value, label
        FROM company_contact_info
        WHERE is_active = TRUE
        ORDER BY id ASC
    ");
    
    $stmt->execute();
    $rows = $stmt->fetchAll();
    
    // Format data as requested: { phone: { value, label }, email: { value, label }, ... }
    $formatted_data = [];
    foreach ($rows as $row) {
        $formatted_data[$row['contact_type']] = [
            'value' => $row['value'],
            'label' => $row['label']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Company info retrieved successfully',
        'data' => $formatted_data
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error retrieving company info',
        'data' => []
    ]);
}
?>
