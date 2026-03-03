<?php
// admin/shipments.php - List all shipments
require_once 'auth_check.php';
require_once '../api/config.php';

require_login();

// Fetch all shipments
try {
    $stmt = $pdo->query("SELECT * FROM shipments ORDER BY created_at DESC");
    $shipments = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $shipments = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Shipments | Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h3>Perfect Enterprises</h3>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="shipments.php" class="active">Manage Shipments</a></li>
                    <li><a href="add-shipment.php">Add New Shipment</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <h2>Manage Shipments</h2>
                <a href="add-shipment.php" class="btn btn-primary">Add New Shipment</a>
            </header>

            <div class="data-table-card">
                <div class="table-header">
                    <h3>Tracking Database</h3>
                    <div class="search-box">
                        <!-- Simple search could be added here -->
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Tracking ID</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Pincode</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shipments as $shipment): ?>
                        <tr>
                            <td><strong><?php echo $shipment['tracking_id']; ?></strong></td>
                            <td><?php echo $shipment['sender_name']; ?></td>
                            <td><?php echo $shipment['receiver_name']; ?></td>
                            <td><?php echo $shipment['destination_pincode']; ?></td>
                            <td><span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $shipment['status'])); ?>"><?php echo $shipment['status']; ?></span></td>
                            <td>
                                <!-- Actions like edit/delete could be added here -->
                                <button class="btn btn-sm">Edit</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($shipments)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No shipments found in database.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
