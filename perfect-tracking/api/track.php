<?php
// api/track.php - Core Tracking API Endpoint
require_once 'config.php';

setJsonHeaders();

// Check if tracking ID is provided
$tracking_id = isset($_GET['id']) ? trim($_GET['id']) : null;

if (!$tracking_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Tracking ID is required.']);
    exit;
}

try {
    // Basic SQL Injection prevention via prepared statements
    $stmt = $pdo->prepare("SELECT * FROM shipments WHERE tracking_id = :tracking_id OR partner_id = :tracking_id LIMIT 1");
    $stmt->execute(['tracking_id' => $tracking_id]);
    $shipment = $stmt->fetch();

    if (!$shipment) {
        http_response_code(404);
        echo json_encode(['error' => 'Shipment not found. Please check your tracking number.']);
        exit;
    }

    // Fetch tracking timeline
    $timelineStmt = $pdo->prepare("SELECT status, location, description, timestamp FROM tracking_timeline WHERE shipment_id = :shipment_id ORDER BY timestamp DESC");
    $timelineStmt->execute(['shipment_id' => $shipment['id']]);
    $timeline = $timelineStmt->fetchAll();

    $response = [
        'success' => true,
        'shipment' => $shipment,
        'timeline' => $timeline
    ];

    echo json_encode($response);

} catch (\PDOException $e) {
    error_log("Tracking Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'An internal error occurred while fetching tracking details.']);
}
?>
