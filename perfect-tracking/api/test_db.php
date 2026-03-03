<?php
require_once 'config.php';

try {
    // If $pdo exists and is connected, this will run successfully
    $stmt = $pdo->query('SELECT version()');
    $version = $stmt->fetchColumn();
    
    echo "SUCCESS: Connected to database!\n";
    echo "Postgres Version: " . $version . "\n";
    
} catch (PDOException $e) {
    echo "ERROR: Could not connect to the database.\n";
    echo $e->getMessage() . "\n";
}
?>
