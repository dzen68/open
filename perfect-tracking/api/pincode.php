<?php
// api/pincode.php - Pincode Serviceability API
require_once 'config.php';

setJsonHeaders();

$pincode = isset($_GET['pincode']) ? trim($_GET['pincode']) : null;

if (!$pincode) {
    http_response_code(400);
    echo json_encode(['error' => 'Pincode is required.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM pincodes WHERE pincode = :pincode LIMIT 1");
    $stmt->execute(['pincode' => $pincode]);
    $data = $stmt->fetch();

    if (!$data) {
        echo json_encode([
            'success' => true,
            'serviceable' => false,
            'message' => 'Sorry, we do not currently serve this location.'
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'serviceable' => (bool)$data['is_serviceable'],
        'details' => [
            'city' => $data['city'],
            'state' => $data['state'],
            'partner' => $data['partner_courier']
        ],
        'message' => $data['is_serviceable'] ? 'Great! We provide service in this area.' : 'Service is currently suspended in this area.'
    ]);

} catch (PDOException $e) {
    error_log("Pincode Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'An internal error occurred.']);
}
?>
