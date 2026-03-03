<?php
// admin/dashboard.php - Admin Dashboard Home
require_once 'auth_check.php';
require_once '../api/config.php';

require_login();

// Fetch basic stats from Supabase
try {
    $totalShipments = $pdo->query("SELECT count(*) FROM shipments")->fetchColumn();
    $inTransit = $pdo->query("SELECT count(*) FROM shipments WHERE status = 'In Transit'")->fetchColumn();
    $delivered = $pdo->query("SELECT count(*) FROM shipments WHERE status = 'Delivered'")->fetchColumn();
    
    // Recent shipments
    $stmt = $pdo->query("SELECT tracking_id, receiver_name, status, created_at FROM shipments ORDER BY created_at DESC LIMIT 5");
    $recentShipments = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $totalShipments = 0;
    $inTransit = 0;
    $delivered = 0;
    $recentShipments = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin Panel</title>
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
                    <li><a href="dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="shipments.php">Manage Shipments</a></li>
                    <li><a href="add-shipment.php">Add New Shipment</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <h2>Dashboard Overview</h2>
                <div class="user-info">Logged in as: <?php echo ADMIN_USER; ?></div>
            </header>

            <div class="stats-grid">
                <div class="stat-card">
                    <h4>Total Shipments</h4>
                    <div class="value"><?php echo $totalShipments; ?></div>
                </div>
                <div class="stat-card">
                    <h4>In Transit</h4>
                    <div class="value"><?php echo $inTransit; ?></div>
                </div>
                <div class="stat-card">
                    <h4>Delivered</h4>
                    <div class="value"><?php echo $delivered; ?></div>
                </div>
            </div>

            <section class="recent-activity">
                <div class="data-table-card">
                    <div class="table-header">
                        <h3>Recent Shipments</h3>
                        <a href="shipments.php" class="btn btn-primary btn-sm">View All</a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Tracking ID</th>
                                <th>Receiver</th>
                                <th>Status</th>
                                <th>Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentShipments as $shipment): ?>
                            <tr>
                                <td><strong><?php echo $shipment['tracking_id']; ?></strong></td>
                                <td><?php echo $shipment['receiver_name']; ?></td>
                                <td><span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $shipment['status'])); ?>"><?php echo $shipment['status']; ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($shipment['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($recentShipments)): ?>
                            <tr>
                                <td colspan="4" class="text-center">No shipments found.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
