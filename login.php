<?php
session_start();
include "db.php";

// Jika sudah login
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$login_error = "";


if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $login_error = "Semua kolom wajib diisi.";
    } else {

        $result = supabase("GET", "users", null, "?username=eq.$username");

        if (empty($result["data"])) {
            $login_error = "Username tidak ditemukan!";
        } else {
            $user = $result["data"][0];

            if (password_verify($password, $user['password_hash'])) {

                $_SESSION['user'] = $user;

                // Karena tabel kamu TIDAK punya kolom is_admin
                // Maka default set ke false atau hapus sama sekali.
                $_SESSION['is_admin'] = false;

                header("Location: index.php");
                exit();
            } else {
                $login_error = "Password salah!";
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
<link rel="icon" type="image/x-icon" href="assets/favicon.ico">

    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/registepage.css">
    
    <style>

    </style>
</head>
<body>
    <!-- Bagian luar kotak -->
    <h2 class="brand" style="color: #ffff; margin-bottom: 0px;">Welcome to InsightX</h2>
    <h4 style="color: white; margin-bottom: 5px;">Create Your Account and Enjoy All Our Services Easily.</h4>
    <p style="font-size: 14px;margin-top: 0; margin-bottom: 25px; color: white;">Masuk untuk melanjutkan ke Dashboard</p>

<div class="wrapper">

    
    <form action="login.php" method="POST">
        <h4>InsightX</h4>

        <?php if (!empty($login_error)): ?>
            <div class="error"><?= htmlspecialchars($login_error) ?></div>
        <?php endif; ?>

        <div class="input-box">
            <i class="fa-solid fa-user"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <div class="input-box">
            <i class="fa-solid fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" name="login">Login</button>

        <div class="register-link">
            <p>Belum punya akun? <a href="register.php">Daftar</a></p>
        </div>
    </form>
</div>

</body>
</html>