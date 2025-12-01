<?php
session_start();
include "../connect.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $conn->real_escape_string($_POST["name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $message = "Email sudah terdaftar!";
    } else {
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if ($conn->query($sql)) {
            header("Location: login.php");
            exit;
        } else {
            $message = "Gagal register.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../asset/style.css">
    <title>Register</title>
</head>
<body>
    <header>
        <b>
            <img width="30px" src="https://i.imgur.com/nDqzOji.png">
            Rumah Jamur
        </b>
        <nav>
            <a href="index.php">Home</a>
            <a href="product.php">Produk</a>
        </nav>
    </header>

    <br><br><br><br>

    <h2 style="text-align:center;" >Register User</h2>

    <?php if ($message != "") echo "<p style='color:red;'>$message</p>"; ?>

    <div class="section form-section" id="register">
        <form method="POST">
            <input type="text" name="name" placeholder="Nama" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Kata Sandi" required>
            <button type="submit" class="tombol">Register</button>
        </form>
    </div>

    <p style="text-align:center;">Sudah punya akun? <a href="login.php" class="no-underline">Login di sini</a></p>

</body>
</html>
