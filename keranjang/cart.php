<?php
session_start();
include "../connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=login_required");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT 
        cart_items.id AS cart_id,
        cart_items.quantity,
        products.id AS product_id,
        products.name,
        products.price,
        products.image_url
    FROM cart_items
    JOIN products ON cart_items.product_id = products.id
    WHERE cart_items.user_id = ?
    ORDER BY cart_items.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="../asset/style.css">

    <style>
        .cart-container {
            max-width: 900px;
            margin: 120px auto;
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .cart-item {
            display: flex;
            align-items: center;
            gap: 20px;
            margin: 20px 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .cart-item img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
        }
        .cart-info {
            flex: 1;
        }
        .cart-actions {
            text-align: right;
        }
        .qty-box {
            width: 60px;
            padding: 8px;
            font-size: 14pt;
            text-align: center;
            border-radius: 6px;
        }
        .remove-btn {
            color: white;
            background: #d9534f;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
        }
    </style>
</head>

<body>

<header>
    <b><img src="https://i.imgur.com/nDqzOji.png" width="30"> Rumah Jamur</b>
    <nav><a href="../product.php">Kembali ke Produk</a></nav>
</header>


<div class="cart-container">
    <h2>Keranjang Belanja</h2>

    <?php
    $total = 0;
    if ($result->num_rows === 0): ?>
        <p>Keranjang Anda kosong.</p>
    <?php else: 
        while ($row = $result->fetch_assoc()):
            $subtotal = $row['price'] * $row['quantity'];
            $total += $subtotal;
    ?>
        <div class="cart-item">
            <img src="<?php echo $row['image_url']; ?>">

            <div class="cart-info">
                <h3><?php echo $row['name']; ?></h3>
                <p>Harga: Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                <p>Jumlah: <?php echo $row['quantity']; ?></p>
                <p>Subtotal: <b>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></b></p>
            </div>

            <div class="cart-actions">
                <a class="remove-btn" href="remove_from_cart.php?id=<?php echo $row['cart_id']; ?>">Hapus</a>
            </div>
        </div>
    <?php endwhile; ?>

        <h3>Total Belanja: Rp <?php echo number_format($total, 0, ',', '.'); ?></h3>
        <br>
        <a class="tombol" href="checkout.php">Checkout</a>

    <?php endif; ?>
</div>

<?php include "../footer.php"; ?>

</body>
</html>
