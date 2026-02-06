<?php
/**
 * Database Connection Handler
 * 
 * PDO connection to MySQL database with error handling
 * Use environment variables for credentials
 */

// Database credentials from environment variables
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_name = getenv('DB_NAME') ?: 'bd_enterprises';
$db_user = getenv('DB_USER') ?: 'bd_user';
$db_password = getenv('DB_PASSWORD') ?: 'bdenterprisespankajsharma';

try {
    // Create PDO connection with UTF8MB4 charset
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    // Log error and return failure response
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed',
        'data' => []
    ]);
    exit;
}
?>
