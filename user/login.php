<?php
session_start();
include "../connect.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");

    // dari sini
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["email"] = $user["email"]; 

            if ($user["email"] == "admin@admin.com") {
                header("Location: ../admin/dashboard.php");
            } 
            else {
                header("Location: profile.php");
            }
            exit;
        }
        else {
            $message = "Password salah!";
        }
    } else {
        $message = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../asset/style.css">
    <title>Login</title>
</head>
<body>
    <header>
        <b>
            <img width="30px" src="https://i.imgur.com/nDqzOji.png">
            Rumah Jamur
        </b>
        <nav>
            <a href="../index.php">Home</a>
            <a href="../product.php">Produk</a>
        </nav>
    </header>

    <br><br><br><br>

    <h2 style="text-align:center;">Login User</h2>

    <?php if ($message != "") echo "<p style='color:red;'>$message</p>"; ?>

    <div class="section form-section" id="register">
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Kata Sandi" required>
            <button type="submit" class="tombol">Login</button>
        </form>
    </div>

    <p style="text-align:center;">Belum punya akun? <a href="register.php" class="no-underline">Register</a></p>

</body>
</html>
