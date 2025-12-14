<?php
session_start();
include 'connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rumah Jamur</title>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="asset/style.css">
    <style>
        .floating-profile {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 999;
        }
        .floating-profile a {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 55px;
            height: 55px;
            background: #c7e096;
            color: white;
            text-decoration: none;
            font-size: 28px;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            transition: 0.2s ease;
        }
        .floating-profile a:hover {
            background: #849377;
            transform: scale(1.1);
        }
        .floating-profile img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <header>
        <b>
            <img width="30px" src="https://i.imgur.com/nDqzOji.png">
            Rumah Jamur
        </b>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="product.php">Produk</a>
                <a href="keranjang/cart.php">Keranjang</a>
                <a href="user/logout.php">Keluar</a>
            <?php else: ?>
                <a href="product.php">Produk</a>
                <a href="user/login.php">Login</a>
                <a href="user/register.php">Daftar</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="hero"><h1>Yuk Beli <br> Jamur!!</h1></div>

    <div class="section" id="opening"> 
        <p> 
        Tahukah kamu bahwa produksi produk hewani menjadi salah satu penyumbang gas rumah kaca terbesar 
        di dunia? Sebagai langkah awal dalam usaha menyelamatkan bumi, kini saatnya beralih ke pilihan makanan yang lebih ramah 
        lingkungan—tanpa mengorbankan rasa. Produk olahan jamur hadir sebagai solusi lezat, sehat, dan berkelanjutan. 
        Dengan tekstur yang mirip daging, kaya nutrisi, dan dapat diolah dalam berbagai menu, 
        jamur menjadi alternatif modern yang bukan hanya baik untuk tubuh, tetapi juga untuk planet kita. 
        Yuk, mulai gaya hidup baru yang lebih hijau dengan menikmati kelezatan olahan jamur! 
        </p> 
    </div>

    <div class="section" id="testimoni">
        <h2>Testimoni pelanggan</h2>
        <div class="testimoni-list">
            <blockquote>"Pilihannya banyak banget, anak saya suka! Sayapun tidak rugi"  <b>Mirna, Sidoarjo</b></blockquote>
            <blockquote>"Enak dan sehat, banyak pilihan juga!"  <b>Basuki, Surabaya</b></blockquote>
        </div>
    </div>

    <div class="section promo" id="promo">
        <h2 style="font-size: 25pt;">Promo Spesial!</h2>
        <h2 style="font-size: 25pt;">Dapatkan diskon <b>10%</b> untuk pembelian di atas Rp200.000! </h2>

        <a href="product.php" class="tombol">Beli Sekarang</a>
        <p style="font-size: smaller;">S&K berlaku.</p>
    </div>
    
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="floating-profile">
        <a href="user/profile.php">
            <img src="https://i.imgur.com/TjLhkFO.png">
        </a>
    </div>
    <?php endif; ?>

    <?php include 'footer.php'; ?>
</body>
</html>
