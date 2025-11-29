<?php
session_start();
include "connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=login_required");
    exit;
}

$user_id = $_SESSION['user_id'];

// Validate
if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    die("Invalid request.");
}

$product_id = intval($_POST['product_id']);
$quantity = max(1, intval($_POST['quantity']));  // minimum 1

// Check if product exists
$stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found.");
}
$stmt->close();

// Check if found in cart
$stmt = $conn->prepare("
    SELECT id, quantity 
    FROM cart_items 
    WHERE user_id = ? AND product_id = ?
");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($row) {
    // Increase qty + update timestamp
    $new_qty = $row['quantity'] + $quantity;

    $stmt = $conn->prepare("
        UPDATE cart_items 
        SET quantity = ?, created_at = NOW()
        WHERE id = ?
    ");
    $stmt->bind_param("ii", $new_qty, $row['id']);
    $stmt->execute();
    $stmt->close();

} else {
    // Insert new row
    $stmt = $conn->prepare("
        INSERT INTO cart_items (user_id, product_id, quantity)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $stmt->execute();
    $stmt->close();
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>
