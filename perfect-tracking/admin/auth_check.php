<?php
// admin/auth_check.php - Session validation for admin pages
session_start();

// Simple hardcoded admin credentials for now (as per the blueprint's "Phase 1")
// In a real production app, these should be in the database (hashed) or environment variables.
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'admin123');

function is_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
?>
