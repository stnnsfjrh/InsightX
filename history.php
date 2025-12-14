<?php
// Contoh data transaksi (bisa diganti query DB)
$riwayat = [
    ["judul" => "Pembelian Motor R15", "status" => "Diproses", "tanggal" => "2025-01-15"],
    ["judul" => "Pembelian Aksesoris Helm", "status" => "Selesai", "tanggal" => "2025-01-12"],
    ["judul" => "Servis Motor", "status" => "Diproses", "tanggal" => "2025-01-10"]
];

$filter = $_GET['filter'] ?? "semua";
?>
<!DOCTYPE html>
<html lang="id">
<head>
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

<!-- Tabs --> 
 <div class="tabs"> 
    <a href="?filter=semua" class="category-pills <?= $filter=='semua'?'active':'' ?>">Semua</a>
     <a href="?filter=diproses" class="category-pills <?= $filter=='diproses'?'active':'' ?>">Diproses</a>
      <a href="?filter=selesai" class="category-pills <?= $filter=='selesai'?'active':'' ?>">Selesai</a>
     </div>

<!-- LIST -->
<div class="list">
<?php
$ada = false;
foreach ($riwayat as $item) {
    if ($filter == "semua" || strtolower($item["status"]) == strtolower($filter)) {
        $ada = true;
?>
<a href="detail_transaksi.php?id=<?= urlencode($item['judul']) ?>" class="history-card-link">
    <div class="history-card">
        
        <div class="card-left">
            <div class="icon-box">
                <i class="ri-global-line"></i>
            </div>

            <div class="card-text">
                <span class="card-title">PEMBELIAN PAKET</span>
                <span class="card-sub"><?= $item["judul"] ?></span>
                <span class="card-date"><?= date("d M Y", strtotime($item["tanggal"])) ?></span>
            </div>
        </div>

        <div class="card-right">
            <?php if ($item["status"] == "Selesai"): ?>
                <span class="badge-status badge-success">BERHASIL</span>

            <?php elseif ($item["status"] == "Diproses"): ?>
                <span class="badge-status badge-proses">DIPROSES</span>

            <?php else: ?>
                <span class="badge-status badge-gagal"><?= strtoupper($item["status"]) ?></span>
            <?php endif; ?>
        </div>

    </div>
</a>
<?php
    }
}

if (!$ada) {
    echo "<div class='no-data'>Tidak ada transaksi</div>";
}
?>
</div>

</body>
</html>
