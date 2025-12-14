<?php 
  session_start();

if (!isset($_SESSION['user'])) {
    // belum login → jangan jalankan rekomendasi
    header("Location: login.php");
    exit();
}

  require "db.php";
  require "call-api.php";
  $username = $_SESSION['username'] ?? "User";

$payload = [
    "avg_call_duration" => $_SESSION["user"]["avg_call_duration"],
    "avg_data_usage_gb" => $_SESSION["user"]["avg_data_usage_gb"],
    "complaint_count"   => $_SESSION["user"]["complaint_count"],
    "device_brand"      => $_SESSION["user"]["device_brand"],
    "monthly_spend"     => $_SESSION["user"]["monthly_spend"],
    "pct_video_usage"   => $_SESSION["user"]["pct_video_usage"],
    "plan_type"         => $_SESSION["user"]["plan_type"],
    "sms_freq"          => $_SESSION["user"]["sms_freq"],
    "topup_freq"        => $_SESSION["user"]["topup_freq"],
    "travel_score"      => $_SESSION["user"]["travel_score"]
];

$result = call_ml_api($payload);// kirim data ke api 

if (isset($result["error"])) {
    die("ML Error: " . htmlspecialchars($result["error"]));
}

$label = $result["top_3_recommendations"][0]["label"]; // ini label pertama recomeendation win 

$produkRekomendasi = supabase(
    "GET",
    "produk",
    null,
    "?select=id,nama,harga,foto,kategori:kategori_id!inner(id,nama,tampil_di)"
    . "&kategori.nama=eq." . urlencode("General Offer")
    . "&kategori.tampil_di=in.(index,buy)"
);



// how to debug
// echo "<pre>";
// print_r($produkRekomendasi);
// exit;

?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/x-icon" href="assets/favicon.ico">

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/buy.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css">
  <title>Recomended Product</title>
</head>
<body>
      <!-- ================= SIDEBAR ================= -->
    <div class="sidebar">
      <div class="main-content">
        <img src="https://via.placeholder.com/60" alt="user" class="user-img">
        <div>   
          <?php if (isset($_SESSION['user'])) : ?>
              <span class="greeting">Selamat datang, <?= htmlspecialchars($_SESSION["user"]['username']); ?></span>
          <?php else : ?>
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
        <a href="recomend_product.php" class="menu-item"><i class="ri-archive-drawer-line"></i></i> rekomendasi</a>
      </div>
    </div>

    <!-- main content -->
    <div class="main">

          <h2>Hasil Rekomendasi</h2>

    <p><b>dari kategori <?= $label?></b></p>
    

        <!-- GRID PRODUK -->
    <!-- GRID PRODUK -->
<div class="product-grid">

<?php if (!empty($produkRekomendasi['data'])): ?>
    <?php foreach ($produkRekomendasi['data'] as $p): ?>
        <div class="product-card">
            <div class="product-img"
                style="background-image:url('<?= htmlspecialchars($p['foto'] ?: 'default.png'); ?>');
                       background-size:cover;
                       background-position:center;">
            </div>

            <h3 class="product-title">
                <?= htmlspecialchars(trim($p['nama'])); ?>
            </h3>

            <p class="product-price">
                Rp <?= number_format($p['harga'], 0, ',', '.'); ?>
            </p>

            <?php if (!empty($_SESSION['username'])): ?>
                <a class="product-btn"
                   href="produk-detail.php?id=<?= (int)$p['id']; ?>">
                   Beli
                </a>
            <?php else: ?>
                <a class="product-btn" href="login.php">
                   Beli
                </a>
            <?php endif; ?>

            <!-- Optional badge -->
            
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="empty">Produk belum tersedia</p>
<?php endif; ?>

</div>


    </div>
</body>
</html>
