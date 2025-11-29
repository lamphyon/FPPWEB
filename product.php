<?php

include "connect.php";

$result = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Produk | Rumah Jamur</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            padding: 40px;
            max-width: 1200px;
            margin: auto;
        }
        .product-card {
            background: #fff;
            border-radius: 10px;
            padding: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            transition: transform .2s;
            text-align: center;
        }
        .product-card:hover {
            transform: scale(1.04);
        }
        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 12px;
        }
        .product-card h3 {
            margin: 10px 0;
        }
        .product-card p {
            color: #666;
        }
        .product-card a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 16px;
            background: #849377;
            color: white;
            border-radius: 8px;
            text-decoration: none;
        }
        .product-card a:hover {
            background: #6e8364;
        }
    </style>
</head>

<body>

<header>
    <b><img width="30px" src="https://i.imgur.com/nDqzOji.png"> Rumah Jamur</b>
    <nav>
        <a href="index.php">Home</a>
        <a href="products.php">Produk</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<div style="height:100px;"></div>

<h2 style="text-align:center;">Daftar Produk</h2>

<div class="product-grid">

<?php while($row = $result->fetch_assoc()): ?>

    <div class="product-card">
        <img src="<?php echo $row['image_url']; ?>" style="width:100%; height:180px; object-fit:cover;">
        <h3><?php echo $row['name']; ?></h3>
        <p>Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
        <a href="product_detail.php?id=<?php echo $row['id']; ?>">Lihat Detail</a>
    </div>

<?php endwhile; ?>

</div>

<?php include "footer.php"; ?>

</body>
</html>
