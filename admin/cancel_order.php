<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['order_id'])) {
    header("Location: dashboard.php");
    exit();
}

$order_id = $_POST['order_id'];

$stmt = $conn->prepare("DELETE FROM bestellingen WHERE id = ?");
$stmt->execute([$order_id]);

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
