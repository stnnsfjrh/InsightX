<?php
session_start();
include "../db.php";

$register_error = "";
$register_success = "";

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);

    if (empty($username) || empty($password) || empty($confirm)) {
        $register_error = "Semua kolom wajib diisi.";
    } elseif ($password !== $confirm) {
        $register_error = "Password tidak sama.";
    } else {

        // Cek admin apakah sudah ada
        $check = supabase("GET", "admins", null, "?username=eq.$username");

        if (!empty($check["data"])) {
            $register_error = "Username sudah digunakan.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $insert = supabase("POST", "admins", [
                "username" => $username,
                "password_hash" => $hashed
            ]);

            if (isset($insert["status"]) && $insert["status"] == 201) {
                $register_success = "Admin berhasil terdaftar!";
                header("refresh:2; url=login.php"); // Redirect dalam 2 detik
            } else {
                $register_error = "Gagal menyimpan ke database.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<link rel="icon" type="image/x-icon" href="../assets/favicon.ico">

    <meta charset="UTF-8">
    <title>Register Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/registepage.css">
</head>

<body>

<h1 class="brand">InsightX</h1>

<div class="wrapper">
<form action="" method="POST">
    <h4>DAFTAR ADMIN</h4>

    <?php if (!empty($register_error)): ?>
        <div class="error"><?= htmlspecialchars($register_error) ?></div>
    <?php endif; ?>

    <?php if (!empty($register_success)): ?>
        <div class="success"><?= htmlspecialchars($register_success) ?></div>
    <?php endif; ?>

    <div class="input-box">
        <i class="fa-solid fa-user"></i>
        <input type="text" name="username" placeholder="Username Admin" required>
    </div>

    <div class="input-box">
        <i class="fa-solid fa-lock"></i>
        <input type="password" name="password" placeholder="Password" required>
    </div>

    <div class="input-box">
        <i class="fa-solid fa-lock"></i>
        <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
    </div>

    <button type="submit" name="register">Daftar</button>

    <div class="login-link">
        <p>Sudah punya akun admin? <a href="login.php">Login</a></p>
    </div>
</form>
</div>

</body>
</html>
