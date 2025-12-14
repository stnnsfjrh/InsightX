<?php
session_start();
// Contoh data reward
$reward = [
    ["judul" => "Voucher Diskon 10%", "desc" => "Dapatkan diskon 10% untuk pembelian paket internet."],
    ["judul" => "Bonus Kuota 2GB", "desc" => "Klaim bonus kuota 2GB untuk 3 hari."],
    ["judul" => "Voucher Belanja", "desc" => "Voucher belanja Rp 20.000 di marketplace."],
    ["judul" => "Poin Tambahan", "desc" => "Dapatkan tambahan 50 poin reward."],
    ["judul" => "Hadiah Misteri", "desc" => "Dapatkan hadiah kejutan dengan penukaran poin."],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<link rel="icon" type="image/x-icon" href="assets/favicon.ico">

    <meta charset="UTF-8">
    <title>Reward</title>
    <link rel="stylesheet" href="css/reward.css">
    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

</head>
<body>

<!-- SIDEBAR -->
<div class="container">

    <div class="sidebar">
  <div class="user-box">
    <img src="https://via.placeholder.com/60" alt="user" class="user-img">
    <div>   
    <?php if (isset($_SESSION['user'])) : ?>
        <span class="greeting">Selamat datang, <?php echo $_SESSION["user"]['username']; ?></span>
    <?php  else : ?>
        <span class="auth-links"><a href="login.php">MASUK</a> / <a href="register.php">DAFTAR</a></span>
    <?php endif; ?>
    </div>
  </div>

    <div class="menu">
        <a href="index.php" class="menu-item"><i class="ri-home-2-line"></i> Home</a>
        <a href="buy.php" class="menu-item"><i class="ri-shopping-bag-line"></i> Buy</a>
        <a href="reward.php" class="menu-item"><i class="ri-gift-line"></i> Reward</a>
        <a href="history.php" class="menu-item"><i class="ri-time-line"></i> History</a>
        <a href="account.php" class="menu-item"><i class="ri-user-3-line"></i> Account</a>
        <a href="rekomendasi.php" class="menu-item"><i class="ri-archive-drawer-line"></i> Rekomendasi</a>
    </div>

</div>

<!-- MAIN -->
<div class="main">

    <!-- Search Section -->
    <div class="search-wrapper">
  <div class="search-container">
      <button class="search-btn"><i class="ri-search-line"></i></button>
      <input type="text" placeholder="Cari" class="search-input" />
  </div>
</div>

    <!-- CONTENT -->
    <div class="content">
        <?php foreach ($reward as $r): ?>
            <div class="reward-card">
                <div class="reward-title"><?= $r["judul"] ?></div>
                <div class="reward-desc"><?= $r["desc"] ?></div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

</body>
</html>