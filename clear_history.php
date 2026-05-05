<?php
session_start();
require 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die('Access denied. Only administrators can wipe the database.');
}

try {
    $pdo->query("DELETE FROM transactions");
    $pdo->query("ALTER TABLE transactions AUTO_INCREMENT = 1");
    
    $_SESSION['admin_msg'] = "Transaction history has been completely wiped.";
    $_SESSION['admin_msg_type'] = "success";
} catch (Exception $e) {
    $_SESSION['admin_msg'] = "Failed to clear history due to a system error.";
    $_SESSION['admin_msg_type'] = "danger";
}

header('Location: admin.php');
exit;
?>