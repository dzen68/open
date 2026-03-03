<?php
// admin/add-shipment.php - Form to add new shipments
require_once 'auth_check.php';
require_once '../api/config.php';

require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Shipment | Admin Panel</title>
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
                    <li><a href="shipments.php">Manage Shipments</a></li>
                    <li><a href="add-shipment.php" class="active">Add New Shipment</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <h2>Add New Shipment</h2>
                <a href="dashboard.php" class="btn">Back to Dashboard</a>
            </header>

            <div class="admin-card">
                <form id="addShipmentForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tracking_id">Tracking ID</label>
                            <input type="text" id="tracking_id" name="tracking_id" value="Auto-generated" readonly style="background-color: #f0f0f0; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label for="partner_id">Partner ID (Optional)</label>
                            <input type="text" id="partner_id" name="partner_id">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="sender_name">Sender Name</label>
                            <input type="text" id="sender_name" name="sender_name" required>
                        </div>
                        <div class="form-group">
                            <label for="receiver_name">Receiver Name</label>
                            <input type="text" id="receiver_name" name="receiver_name" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="destination_pincode">Destination Pincode</label>
                            <input type="text" id="destination_pincode" name="destination_pincode" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Initial Status</label>
                            <select id="status" name="status">
                                <option value="Pending">Pending</option>
                                <option value="In Transit">In Transit</option>
                                <option value="Out for Delivery">Out for Delivery</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="location">Current Location</label>
                        <input type="text" id="location" name="location" placeholder="e.g. Mumbai Hub" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Standard Description</label>
                        <textarea id="description" name="description" rows="3" placeholder="e.g. Shipment received at Mumbai Hub"></textarea>
                    </div>

                    <div id="formMsg" class="hidden" style="margin-bottom: 20px; padding: 10px; border-radius: 4px;"></div>

                    <button type="submit" class="btn btn-primary" id="saveBtn">Save Shipment</button>
                </form>
            </div>
        </main>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>
