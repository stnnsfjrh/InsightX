<?php
session_start();
require "db.php"; 

if (!isset($_GET['id'])) {
    header("Location: buy.php");
    exit;
}

$id = $_GET['id'];

// Ambil data user login dari session
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// Ambil produk
$get = supabase("GET", "produk", null, "?id=eq.$id&select=*");
if (empty($get["data"])) {
    die("Produk tidak ditemukan");
}
$produk = $get["data"][0];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<link rel="icon" type="image/x-icon" href="assets/favicon.ico">

    <meta charset="UTF-8">
    <title>Checkout - <?= $produk['nama']; ?></title>
    <link rel="stylesheet" href="css/checkout.css">
</head>
<body>

<div class="checkout-container">

    <h2>Checkout Produk</h2>

    <div class="product-box">
        <img src="<?= $produk['foto']; ?>" class="checkout-img">

        <div class="product-info">
            <h3><?= $produk['nama']; ?></h3>
            <p>Harga: <b>Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></b></p>
        </div>
    </div>

    <form action="checkout-proses.php" method="POST">

        <input type="hidden" name="produk_id" value="<?= $produk['id']; ?>">
        <input type="hidden" name="harga" value="<?= $produk['harga']; ?>">

        <label>Metode Pembayaran</label>
        <select name="metode" required>
            <option value="Pulsa">Pulsa</option>
            <option value="OVO">OVO</option>
        </select>

        <!-- <label>Jumlah</label>
        <input type="number" name="jumlah" min="1" value="1" required> -->

        <button class="btn-checkout" type="submit">Bayar Sekarang</button>

    </form>

</div>

</body>
</html>
