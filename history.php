<?php
session_start();
require "db.php";

// cek login user
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];

// ambil semua riwayat pembayaran user
$query = "?user_id=eq.$user_id";
$res = supabase("GET", "riwayat_pembayaran", null, $query);
$riwayat = $res["data"] ?? [];

// ambil semua produk
$resProduk = supabase("GET", "produk");
$produkList = $resProduk["data"] ?? [];

// buat mapping id produk -> nama produk
$produkMap = [];
foreach ($produkList as $p) {
    $produkMap[$p['id']] = $p['nama'];
}

// urutkan riwayat terbaru ke lama
usort($riwayat, function ($a, $b) {
    $timeA = !empty($a['created_at']) ? strtotime($a['created_at']) : 0;
    $timeB = !empty($b['created_at']) ? strtotime($b['created_at']) : 0;

    return $timeB <=> $timeA;
});

?>
<!DOCTYPE html>
<html lang="id">
<head>
<link rel="icon" type="image/x-icon" href="assets/favicon.ico">

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Riwayat Transaksi</title>
<link rel="stylesheet" href="css/history.css">
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>

<div class="header">
    <i class="ri-arrow-left-line" onclick="window.location='index.php'"></i>
    Riwayat Transaksi
</div>

<!-- LIST -->
<div class="list">
<?php if (!empty($riwayat)): ?>
    <?php foreach ($riwayat as $item): ?>
    <a href="detail_transaksi.php?id=<?= $item['id'] ?>" class="history-card-link">
        <div class="history-card">

            <div class="card-left">
                <div class="icon-box">
                    <i class="ri-global-line"></i>
                </div>

                <div class="card-text">
                    <span class="card-title">TRANSAKSI</span>
                    <span class="card-sub">
                        <?= htmlspecialchars($produkMap[$item['produk_id']] ?? "Produk #" . $item['produk_id']) ?>
                    </span>
                    <span class="card-method">
                        Metode: <?= htmlspecialchars($item['metode_pembayaran'] ?? "Tidak diketahui") ?>
                    </span>
                    <span class="card-date">
                        <?php 
                            $dt = new DateTimeImmutable($item['created_at']);
                            $dt = $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
                            echo $dt->format("d M Y");
                        ?>
                    </span>
                </div>
            </div>

            <div class="card-right">
                <!-- Status selalu BERHASIL -->
                <span class="badge-status badge-success">BERHASIL</span>
            </div>

        </div>
    </a>
    <?php endforeach; ?>
<?php else: ?>
    <div class="no-data">Tidak ada transaksi</div>
<?php endif; ?>
</div>

</body>
</html>
