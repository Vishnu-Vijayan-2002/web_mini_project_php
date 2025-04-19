<?php
require_once dirname(__DIR__, 2) . '/includes/db_connect.php';

// --- ADMIN AUTHENTICATION CHECK ---
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== 'admin') {
    header("Location: ../login.php?error=unauthorized");
    exit;
}

// Optional: Regenerate session ID periodically
if (!isset($_SESSION['last_regen'])) $_SESSION['last_regen'] = time();
if (time() - $_SESSION['last_regen'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['last_regen'] = time();
}
