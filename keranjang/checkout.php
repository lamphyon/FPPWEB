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
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart = $stmt->get_result();

if ($cart->num_rows === 0) {
    die("<h2>Keranjang kosong, tidak bisa checkout.</h2><a href='../product.php'>Kembali</a>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = $_POST['name'];
    $phone    = $_POST['phone'];
    $address  = $_POST['address'];

    if ($name == "" || $phone == "" || $address == "") {
        die("Data wajib diisi.");
    }

    $cart->data_seek(0);
    $total = 0;
    while ($item = $cart->fetch_assoc()) {
        $total += $item['price'] * $item['quantity'];
    }

    $stmt = $conn->prepare("
        INSERT INTO orders (user_id, total_price, customer_name, customer_phone, customer_address)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iisss", $user_id, $total, $name, $phone, $address);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    $cart->data_seek(0);
    foreach ($cart as $item) {
        $stmt = $conn->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iiii",
            $order_id,
            $item['product_id'],
            $item['quantity'],
            $item['price']
        );
        $stmt->execute();
        $stmt->close();
    }

    $stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    echo "
    <h2>Checkout berhasil!</h2>
    <p>Terima kasih, pesanan Anda sudah dibuat.</p>
    <p><b>ID Pesanan: #$order_id</b></p>
    <a class='tombol' href='../product.php'>Kembali ke Produk</a>
    ";
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="../asset/style.css">
    <style>
        .checkout-container {
            max-width: 900px;
            margin: 120px auto;
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #ccc;
            border-radius: 8px;
            font-size: 14pt;
        }
        .order-summary {
            background: #f6f6f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .tombol {
            padding: 12px 20px;
            border-radius: 6px;
            background: #849377;
            color: white;
            text-decoration: none;
        }
    </style>
</head>

<body>

<header>
    <b><img src="https://i.imgur.com/nDqzOji.png" width="30"> Rumah Jamur</b>
</header>

<div class="checkout-container">

    <h2>Checkout</h2>

    <div class="order-summary">
        <h3>Ringkasan Pesanan:</h3>
        <ul>
            <?php
            $total = 0;
            foreach ($cart as $item):
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
                <li>
                    <?php echo $item['name']; ?> (x<?php echo $item['quantity']; ?>)
                    — Rp <?php echo number_format($subtotal, 0, ',', '.'); ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <p><b>Total: Rp <?php echo number_format($total, 0, ',', '.'); ?></b></p>
    </div>

    <h3>Data Pengiriman</h3>

    <form method="POST" action="../create_payment.php">
        <label>Nama Lengkap:</label>
        <input type="text" name="name" required>

        <label>No. Telepon:</label>
        <input type="text" name="phone" required>

        <label>Alamat Lengkap:</label>
        <textarea name="address" rows="4" required></textarea>

        <br><br>
        <button type="submit" class="tombol">Konfirmasi Pesanan</button>
    </form>

</div>

<?php include "../footer.php"; ?>
</body>
</html>
