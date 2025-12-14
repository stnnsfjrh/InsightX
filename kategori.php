<?php
session_start();
require "db.php";

/*
|--------------------------------------------------------------------------
| AMBIL NAMA KATEGORI DARI URL
|--------------------------------------------------------------------------
| Contoh:
| kategori.php?nama=General+Offer
*/
$namaKategori = $_GET['nama'] ?? null;


// decode: General+Offer -> General Offer
$namaKategori = urldecode($namaKategori);

// encode ulang agar aman ke URL Supabase
$namaKategoriEncoded = rawurlencode($namaKategori);


if (!$namaKategori) {
    die("Kategori tidak ditemukan");
}

/*
|--------------------------------------------------------------------------
| AMBIL SEMUA KATEGORI BERDASARKAN NAMA
|--------------------------------------------------------------------------
| Contoh hasil:
| - id 8 (index)
| - id 9 (buy)
*/
$responseKategori = supabase(
    "GET",
    "kategori",
    null,
    "?select=id,nama,tampil_di&nama=eq.$namaKategori*"
);

$responseKategori2 = supabase(
    "GET",
    "kategori",
    null,
    "?select=id,nama,tampil_di&nama=eq.$namaKategoriEncoded" // harus seperti ini formatnya: Budget%20Offer
);


$kategoriList = $responseKategori2['data'] ?? [];


if (empty($kategoriList)) {
    die("Kategori tidak valid");
}

/*
|--------------------------------------------------------------------------
| KUMPULKAN SEMUA kategori_id
|--------------------------------------------------------------------------
*/
$kategoriIds = array_column($kategoriList, 'id');
$idString = implode(',', $kategoriIds);

/*
|--------------------------------------------------------------------------
| AMBIL PRODUK BERDASARKAN kategori_id
|--------------------------------------------------------------------------
*/
$responseProduk = supabase(
    "GET",
    "produk",
    null,
    "?select=id,nama,harga,foto,kategori(id,nama,tampil_di)&kategori_id=in.($idString)"
);

$produkKategori = $responseProduk['data'] ?? [];

/*
|--------------------------------------------------------------------------
| JUDUL HALAMAN
|--------------------------------------------------------------------------
*/
$judulKategori = $kategoriList[0]['nama'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<link rel="icon" type="image/x-icon" href="assets/favicon.ico">

    <meta charset="UTF-8">
    <title>Kategori - <?= htmlspecialchars($judulKategori); ?></title>
    <link rel="stylesheet" href="css/buy.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>

<div class="container">

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="user-box">
        <img src="https://via.placeholder.com/60" class="user-img">
        <div>
            <?php if (isset($_SESSION['user'])) : ?>
                <span class="greeting">
                    Selamat datang, <?= htmlspecialchars($_SESSION['user']['username']); ?>
                </span>
            <?php else : ?>
                <span class="auth-links">
                    <a href="login.php">MASUK</a> /
                    <a href="register.php">DAFTAR</a>
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
    </div>
</div>

<!-- CONTENT -->
<div class="content">

    <!-- HEADER -->
    <h2 style="margin-bottom:20px;">
        Kategori: <?= htmlspecialchars($judulKategori); ?>
    </h2>

    <!-- GRID PRODUK -->
    <div class="product-grid">

        <?php if (empty($produkKategori)): ?>
            <p>Produk belum tersedia pada kategori ini.</p>
        <?php endif; ?>

        <?php foreach ($produkKategori as $p): ?>
            <div class="product-card">

                <div class="product-img"
                     style="background-image:url('<?= htmlspecialchars($p['foto']); ?>');
                            background-size:cover;
                            background-position:center;">
                </div>

                <h3 class="product-title">
                    <?= htmlspecialchars($p['nama']); ?>          
                </h3>

                <p class="product-price">
                    Rp <?= number_format($p['harga'], 0, ',', '.'); ?>
                </p>

                <button class="product-btn"
                        onclick="window.location.href='produk-detail.php?id=<?= $p['id']; ?>'">
                    Beli
                </button>

            </div>
        <?php endforeach; ?>

    </div>

</div>
</div>

</body>
</html>
