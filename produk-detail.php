<?php
session_start();
require "db.php"; // fungsi supabase()

function uploadToSupabaseStorage($bucket, $fileTmpPath, $fileName)
{
    global $SUPABASE_URL, $SUPABASE_API_KEY;

    $mimeType = mime_content_type($fileTmpPath);
    $url = $SUPABASE_URL . "storage/v1/object/$bucket/$fileName";
    $fileData = file_get_contents($fileTmpPath);

    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "apikey: $SUPABASE_API_KEY",
            "Authorization: Bearer $SUPABASE_API_KEY",
            "Content-Type: $mimeType",
            "x-upsert: true"
        ],
        CURLOPT_POSTFIELDS => $fileData
    ]);

    curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return [
        "status" => $status,
        "url" => $SUPABASE_URL . "storage/v1/object/public/$bucket/$fileName"
    ];
}


if (!isset($_GET['id'])) {
    header("Location: buy.php");
    exit;
}

$id = $_GET['id'];

// Ambil produk dari Supabase (jika relasi kategori ada)
$get = supabase("GET", "produk", null, "?id=eq.$id&select=*,kategori(nama)");

if (empty($get["data"])) {
    echo "Produk tidak ditemukan!";
    exit;
}

$produk = $get["data"][0];

// SAFETY: fallback untuk field yang mungkin tidak ada
$nama       = isset($produk['nama']) ? $produk['nama'] : 'Nama produk';
$foto       = isset($produk['foto']) && $produk['foto'] !== '' ? $produk['foto'] : 'placeholder.png'; // pastikan file placeholder ada di folder image/
$harga      = isset($produk['harga']) ? (float)$produk['harga'] : 0;
$deskripsi  = isset($produk['deskripsi']) && $produk['deskripsi'] !== null && $produk['deskripsi'] !== '' 
                ? $produk['deskripsi'] 
                : "Deskripsi belum tersedia untuk produk ini.";
$kategori_nama = isset($produk['kategori']['nama']) ? $produk['kategori']['nama'] : 'Umum';
$id_produk = isset($produk['id']) ? $produk['id'] : $id;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<link rel="icon" type="image/x-icon" href="assets/favicon.ico">

    <meta charset="UTF-8">
    <title><?= htmlspecialchars($nama); ?></title>
    <link rel="stylesheet" href="css/detail.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>

<div class="detail-container">

    <!-- LEFT — BANNER BESAR -->
    <div class="detail-left">
        <img src="<?= htmlspecialchars($foto); ?>" class="detail-banner" alt="<?= htmlspecialchars($nama); ?>">
    </div>

    <!-- RIGHT — INFORMASI PRODUK -->
    <div class="detail-right">

        <h2 class="detail-title"><?= htmlspecialchars($nama); ?></h2>

        <div class="detail-box">

            <div class="detail-line">
                <i class="ri-price-tag-3-line"></i>
                <span class="label">Harga</span>
                <span class="value">Rp <?= number_format($harga, 0, ',', '.'); ?></span>
            </div>

            <div class="detail-line">
                <i class="ri-shape-line"></i>
                <span class="label">Kategori</span>
                <span class="value"><?= htmlspecialchars($kategori_nama); ?></span>
            </div>

        </div>

        <p class="detail-desc">
            <?= nl2br(htmlspecialchars($deskripsi)); ?>
        </p>

        <!-- TOMBOL BELI -->
        <button class="btn-buy" onclick="window.location.href='checkout.php?id=<?= htmlspecialchars($id_produk); ?>'">
            Beli Sekarang
        </button>

    </div>

</div>

</body>
</html>
