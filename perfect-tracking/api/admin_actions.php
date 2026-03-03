<?php
// api/admin_actions.php - Handle administrative actions
require_once '../admin/auth_check.php';
require_once 'config.php';

setJsonHeaders();

// Require admin authentication for all actions here
if (!is_logged_in()) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access.']);
    exit;
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add_shipment':
        handleAddShipment($pdo);
        break;
    
    case 'update_status':
        handleUpdateStatus($pdo);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action.']);
}

function handleAddShipment($pdo) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit;
    }

    $data = $_POST;
    
    try {
        $pdo->beginTransaction();

        // Generate sequential Tracking ID server-side
        $date = date('Ymd');
        $prefix = 'PE'; // Perfect Enterprises
        
        // Get count of shipments today
        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM shipments 
            WHERE DATE(created_at) = CURRENT_DATE
        ");
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        $sequence = str_pad($count + 1, 2, '0', STR_PAD_LEFT);
        $trackingId = $prefix . $date . $sequence;
        
        // Ensure uniqueness just in case
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM shipments WHERE tracking_id = :tid");
        $checkStmt->execute(['tid' => $trackingId]);
        if ($checkStmt->fetchColumn() > 0) {
            $trackingId .= rand(10, 99); // Fallback if collision
        }

        // 1. Insert shipment record
        $stmt = $pdo->prepare("INSERT INTO shipments (tracking_id, partner_id, sender_name, receiver_name, destination_pincode, status) 
                               VALUES (:tid, :pid, :sender, :receiver, :pincode, :status) RETURNING id");
        $stmt->execute([
            'tid' => $trackingId,
            'pid' => trim($data['partner_id']) ?: null,
            'sender' => trim($data['sender_name']),
            'receiver' => trim($data['receiver_name']),
            'pincode' => trim($data['destination_pincode']),
            'status' => $data['status']
        ]);
        
        $shipmentId = $stmt->fetchColumn();

        // 2. Insert initial timeline entry
        $timelineStmt = $pdo->prepare("INSERT INTO tracking_timeline (shipment_id, status, location, description) 
                                       VALUES (:sid, :status, :location, :desc)");
        $timelineStmt->execute([
            'sid' => $shipmentId,
            'status' => $data['status'],
            'location' => trim($data['location']),
            'desc' => trim($data['description']) ?: "Shipment created."
        ]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => "Shipment added successfully! Tracking ID: $trackingId", 'id' => $shipmentId, 'tracking_id' => $trackingId]);

    } catch (PDOException $e) {
        $pdo->rollBack();
        http_response_code(500);
        if ($e->getCode() == 23505) { // Unique constraint violation in PG
            echo json_encode(['error' => 'Tracking ID already exists.']);
        } else {
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
    }
}

function handleUpdateStatus($pdo) {
    // To be implemented: logic to update existing shipment status
}
?>
