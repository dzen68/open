<?php
// config.php - Supabase Database Configuration

// Define Supabase PostgreSQL Credentials
// Find these in your Supabase Dashboard -> Project Settings -> Database -> Connection String (URI)
define('DB_HOST', 'aws-1-ap-northeast-1.pooler.supabase.com'); 
define('DB_NAME', 'postgres'); 
define('DB_USER', 'postgres.yoghoueyrjgufuprhyru'); 
define('DB_PASS', '@Rk9860870183'); 
define('DB_PORT', '6543');

// Establish Database Connection using PDO for PostgreSQL
try {
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (\PDOException $e) {
    error_log("PostgreSQL Connection Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed. Please check your Supabase credentials.']);
    exit;
}

// Utility function to set JSON headers consistently
function setJsonHeaders() {
    header('Content-Type: application/json; charset=utf-8');
    // Implement CORS if needed
    // header('Access-Control-Allow-Origin: *');
}
?>
