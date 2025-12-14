<?php
session_start();
require "db.php"; // ganti koneksi MySQL → Supabase

$username = "User";

// --------------------------------------------------------
// AMBIL KATEGORI UNTUK HALAMAN BUY
// tampil_di = buy ATAU keduanya
// --------------------------------------------------------
$kategoriBuy = supabase(
    "GET",
    "kategori",
    null,
    "?tampil_di=in.(buy)"
);
$kategoriBuy = $kategoriBuy["data"] ?? [];


// --------------------------------------------------------
// AMBIL PRODUK YANG KATEGORINYA BUY / KEDUANYA
// terdapat relasi kategori
// --------------------------------------------------------
$produkBuy = supabase(
    "GET",
    "produk",
    null,
    "?select=id,nama,harga,foto,kategori(id,nama,tampil_di)&kategori.tampil_di=in.(buy)"
);
$produkBuy = $produkBuy["data"] ?? [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/x-icon" href="assets/favicon.ico">

    <meta charset="UTF-8">
    <title>Buy</title>
    <link rel="stylesheet" href="css/buy.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>

<!-- SIDEBAR -->
<div class="container">

    <div class="sidebar">
        <div class="user-box">
            <img src="https://via.placeholder.com/60" alt="user" class="user-img">
            <div>   
                <?php if (isset($_SESSION['user'])) : ?>
                    <span class="greeting">Selamat datang, <?= $_SESSION["user"]['username']; ?></span>
                <?php  else : ?>
                    <span class="auth-links">
                        <a href="login.php">MASUK</a> / <a href="register.php">DAFTAR</a>
                    </span>
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

<!-- CONTENT -->
<div class="content">

    <!-- SEARCH -->
    <div class="search-container">
        <button class="search-btn"><i class="ri-search-line"></i></button>
        <input type="text" placeholder="Cari produk" class="search-input">
    </div>

    <!-- FILTER -->
    <div class="category-pills">
        <?php foreach ($kategoriBuy as $k) : ?>
            <a href="kategori.php?nama=<?= $k['nama']; ?>" class="pill">
                <?= htmlspecialchars($k['nama']); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- GRID PRODUK -->
    <div class="product-grid">

        <?php foreach ($produkBuy as $p) : ?>

            <div class="product-card">
                <div class="product-img"
                     style="background-image: url('<?= $p['foto']; ?>'); background-size: cover; background-position: center;">
                </div>

                <h3 class="product-title"><?= htmlspecialchars($p['nama']); ?></h3>
                <p class="product-price">Rp <?= number_format($p['harga'], 0, ',', '.'); ?></p>

                <?php if (isset($_SESSION['user'])): ?>
                    <button class="product-btn"
                            onclick="window.location.href='produk-detail.php?id=<?= $p['id']; ?>'">
                        Beli
                    </button>
                <?php else: ?>
                    <button class="product-btn" onclick="window.location.href='login.php'">
                        Beli
                    </button>
                <?php endif; ?>
            </div>

        <?php endforeach; ?>

    </div>
</body>
</html>
