<?php
session_start();
include "../connect.php";

if (!isset($_GET['id'])) {
    die("Produk tidak ditemukan.");
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM products WHERE id = $id");

if ($result->num_rows === 0) {
    die("Produk tidak ditemukan.");
}

$product = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $product['name']; ?> | Rumah Jamur</title>
    <link rel="stylesheet" href="../asset/style.css">

    <style>
        .detail-container {
            max-width: 900px;
            margin: 120px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            display: flex;
            gap: 40px;
        }
        .detail-container img {
            width: 350px;
            height: 350px;
            object-fit: cover;
            border-radius: 10px;
        }
        .detail-info h2 {
            margin-bottom: 10px;
        }
        .detail-info p {
            font-size: 14pt;
            margin-bottom: 10px;
        }
        .price {
            font-size: 20pt;
            font-weight: bold;
            color: #849377;
        }
        .icon-btn {
            background: #849377;
            border: none;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            transition: transform .2s, background .2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .icon-btn img {
            width: 22px;
            height: 22px;
        }
        .icon-btn:hover {
            background: #6e8364;
            transform: scale(1.1);
        }
        .product-controls {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .qty-input {
            width: 60px;
            padding: 8px;
            font-size: 14pt;
            text-align: center;
            border: 2px solid #ccc;
            border-radius: 8px;
            margin-right: 10px;
        }
        .superbutton {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .superbutton span {
            display: inline-flex;
        }
        .add-to-cart {
            width: auto;
            border-radius: 40px;
            padding: 16px 32px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;               
        }
        .add-to-cart p {
            margin: 0;
            font-size: 14pt;
            color: white;
        }
    </style>
</head>

<body>

<header>
    <b><img width="30px" src="https://i.imgur.com/nDqzOji.png"> Rumah Jamur</b>
    <nav>
        <a href="../index.php">Home</a>
        <a href="../product.php">Etalase</a>
        <a href="cart.php">Keranjang</a>
    </nav>
</header>

<div class="detail-container">
    <img src="<?php echo $product['image_url']; ?>" alt="">
    
    <div class="detail-info">
        <h2><?php echo $product['name']; ?></h2>
        <p class="price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
        <p><?php echo $product['description']; ?></p>

        <br>

        <!-- Login/register warning -->
        <?php if (!isset($_SESSION['user_id'])): ?>
            <div style="
                background:#ffdddd;
                border-left:5px solid #d9534f;
                padding:15px;
                border-radius:8px;
                margin:20px 0;
            ">
                <b>Anda belum login!</b><br>
                Silakan login untuk menambahkan produk ke keranjang.
                <br><br>
                <a class="tombol" href="login.php" style="background:#8b5;">Login</a>
                <a class="tombol" href="register.php" style="background:#555;">Register</a>
            </div>
        <?php else: ?>
            <form action="add_to_cart.php" method="POST" class="product-controls">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <br><br><br>
                <div class="superbutton">
                    <span>
                        <button type="button" class="icon-btn" onclick="changeQty(-1)">
                            <img src="https://pngimg.com/uploads/minus/minus_PNG41.png">
                        </button>
                    </span>
                    <input type="number" id="qty" name="quantity" class="qty-input" value="1" min="1">
                    <span>
                        <button type="button" class="icon-btn" onclick="changeQty(1)"> 
                            <img src="https://i.imgur.com/n1GdYhC.png">
                        </button>
                    </span>
                </div>
                <div class="superbutton">
                    <span>
                        <button type="submit" class="icon-btn add-to-cart">
                            <p>Tambahkan ke keranjang</p>
                            <img src="https://i.imgur.com/DzQSEf3.png">
                        </button>
                    </span>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include "../footer.php"; ?>

<script>
function changeQty(amount) {
    let qty = document.getElementById("qty");
    let value = parseInt(qty.value) + amount;
    if (value < 1) value = 1;
    qty.value = value;
}
</script>

</body>
</html>
