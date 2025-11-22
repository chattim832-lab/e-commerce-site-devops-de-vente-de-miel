<?php
session_start();
include 'connection.php';
$user_id = $_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT o.*, p.name FROM orders o JOIN products p ON o.product_id = p.id WHERE o.user_id=$user_id ORDER BY o.created_at DESC");
