<?php
session_start();
include "../db.php";

$error = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $get = supabase("GET", "admins", null, "?username=eq.$username");

    if (empty($get["data"])) {
        $error = "Admin tidak ditemukan!";
    } else {
        $admin = $get["data"][0];

        if (password_verify($password, $admin["password_hash"])) {
            $_SESSION['admin'] = $admin;
            header("Location: index.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/registepage.css">
</head>

<body>

<h1 class="brand">InsightX</h1>

<div class="wrapper">
<form method="POST">
    <h4>LOGIN ADMIN</h4>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="input-box">
        <i class="fa-solid fa-user"></i>
        <input type="text" name="username" placeholder="Username Admin" required>
    </div>

    <div class="input-box">
        <i class="fa-solid fa-lock"></i>
        <input type="password" name="password" placeholder="Password" required>
    </div>

    <button name="login">Login</button>

    <div class="login-link">
        <p>Belum punya akun admin? <a href="register.php">Daftar</a></p>
    </div>
</form>
</div>

</body>
</html>