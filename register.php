<?php
session_start();
include "db.php";

$register_error = "";
$register_success = ""; // tambahkan agar tidak undefined


if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $phone    = trim($_POST['no_telepon']);
    $email    = trim($_POST['email']);

    if (empty($username) || empty($password) || empty($phone) || empty($email)) {
        $register_error = "Semua kolom wajib diisi.";
    } else {

        // CEK USERNAME
        $check_user = supabase("GET", "users", null, "?username=eq.$username");
        if (!empty($check_user["data"])) {
            $register_error = "Username sudah digunakan.";
        } else {

            // CEK EMAIL
            $check_email = supabase("GET", "users", null, "?email=eq.$email");
            if (!empty($check_email["data"])) {
                $register_error = "Email sudah terdaftar.";
            } else {

                // HASH PASSWORD
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // INSERT USER
                $insert = supabase("POST", "users", [
                    "username" => $username,
                    "email" => $email,
                    "phone_number" => $phone,
                    "password_hash" => $hashed_password
                ]);

                if ($insert["status"] === 201 || $insert["status"] === 200) {
                    $_SESSION['register_success'] = "Pendaftaran berhasil!";
                    header("Location: login.php");
                    exit();
                } else {
                    $register_error = "Gagal menyimpan ke database.";
                }
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
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/registepage.css">
</head>

<body>
    <?php if (!empty($register_success)): ?>
    <script>
        alert("Daftar akun berhasil");
    </script>
    <?php endif; ?>
    
    <h1 class="brand">InsightX</span></h1>
    <div class="wrapper">
    <form action="register.php" method="POST">
        <h4>DAFTAR AKUN</h4>

        <?php if (!empty($register_error)): ?>
            <div class="error"><?= htmlspecialchars($register_error) ?></div>
        <?php endif; ?>

        <div class="input-box">
            <i class="fa-solid fa-user"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <div class="input-box">
            <i class="fa-solid fa-phone"></i>
            <input type="text" name="no_telepon" placeholder="No. Telepon" required>
        </div>

        <div class="input-box">
            <i class="fa-solid fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-box">
            <i class="fa-solid fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" name="register">Daftar</button>

        <div class="login-link">
            <p>Sudah punya akun? <a href="login.php">Login</a></p>
        </div>
    </form>
    </div>
</body>
</html>
