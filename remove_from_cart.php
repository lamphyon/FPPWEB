<?php
session_start();
include "connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=login_required");
    exit;
}

if (!isset($_GET['id'])) {
    die("Invalid cart item.");
}

$user_id = $_SESSION['user_id'];
$cart_id = intval($_GET['id']);

$stmt = $conn->prepare("
    DELETE FROM cart_items 
    WHERE id = ? AND user_id = ?
");
$stmt->bind_param("ii", $cart_id, $user_id);
$stmt->execute();
$stmt->close();

// Go back to the cart page
header("Location: cart.php");
exit;
?>
