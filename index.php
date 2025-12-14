<?php
session_start();
require "db.php";

$username = $_SESSION['username'] ?? "User";

/* ===========================================
   Ambil kategori tampil di index
   =========================================== */
$resKategori = supabase(
    "GET",
    "kategori",
    null,
    "?tampil_di=eq.index"
);
$kategoriIndex = $resKategori["data"] ?? [];

/* ===========================================
   Ambil produk yang kategori.tampil_di = index
   =========================================== */
$resProduk = supabase(
    "GET",
    "produk",
    null,
    "?select=*,kategori!inner(tampil_di)&kategori.tampil_di=eq.index"
);
$produkBuy = $resProduk["data"] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Landingpage</title>

  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css">
</head>

<body>

<!-- ================= SIDEBAR ================= -->
<div class="container">

    <div class="sidebar">
        <div class="user-box">
            <img src="https://via.placeholder.com/60" alt="user" class="user-img">
            <div>   
                <?php if (isset($_SESSION['user'])) : ?>
                    <span class="greeting">Selamat datang, <?= $_SESSION["user"]['username']; ?></span>
                <?php  else : ?>
                    <span class="auth-links">
                        <a href="login.php">MASUK</a>/<a href="register.php">DAFTAR</a>
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

<!-- ================= MAIN CONTENT ================= -->
<div class="main">

<!-- Search -->
<div class="search-wrapper">
  <div class="search-container">
      <button class="search-btn"><i class="ri-search-line"></i></button>
      <input type="text" placeholder="Cari" class="search-input" />
  </div>
</div>

<!-- Feature Cards -->
<div class="feature-cards">
  <div class="feature-card"><i class="ri-wifi-line"></i> -</div>
  <div class="feature-card"><i class="ri-wallet-3-line"></i> -</div>
  <div class="feature-card"><i class="ri-coins-line"></i> -</div>
</div>

<!-- Category Pills -->
<div class="category-pills">
<?php foreach ($kategoriIndex as $k) : ?>
    <a href="kategori.php?nama=<?= $k['nama']; ?>" class="pill">
        <?= htmlspecialchars($k['nama']); ?>
    </a>
<?php endforeach; ?>
</div>

<!-- Products -->
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
    <button class="product-btn" onclick="window.location.href='login.php'">Beli</button>
<?php endif; ?>

    </div>
<?php endforeach; ?>
</div>

<!-- Layanan -->
<h2 class="section-title">Layanan</h2>
<div class="layanan-box">
  <i class="ri-whatsapp-fill layanan-icon"></i>
  <div>
    <h3>Chat InsightX Melalui WhatsApp</h3>
    <p>Dapatkan informasi & promo spesial</p>
  </div>
  <span class="layanan-arrow">→</span>
</div>

<!-- News Section -->
<h2 class="section-title" style="margin-top: 25px;">Latest News</h2>

<div class="news-wrapper">

   <!-- LEFT — BIG ARTICLE -->
<a href="https://inet.detik.com/law-and-policy/d-8196069/pakar-sebut-sinyal-5g-ri-ngebut-jika-frekuensi-2,6-ghz-dilepas" 
   target="_blank" 
   class="news-big-link">
    <div class="news-big">
        <img class="news-big-img" src="assets/foto2.png" alt="News Image"/>
        <h3 class="news-big-title">
            Pakar Sebut Sinyal 5G RI Ngebut Jika Frekuensi 2,6 GHz Dilepas
        </h3>
        <span class="news-tag">Law & Policy</span>
    </div>
</a>

<!-- Small News -->
<div class="news-small-list">

<?php
$news = [
    ["link"=>"https://inet.detik.com/law-and-policy/d-8232160/komdigi-bakal-rombak-regulasi-telko-open-access-lelang-frekuensi-baru", "img"=>"assets/foto3.png", "title"=>"Komdigi Bakal Rombak Regulasi Telko", "tag"=>"Technology"],
    ["link"=>"https://inet.detik.com/cyberlife/d-8130637/5-tips-untuk-jaga-jaringan-internet-agar-tetap-stabil-di-hp", "img"=>"assets/foto4.png", "title"=>"5 Tips Internet Stabil", "tag"=>"Cyberlife"],
    ["link"=>"https://www.detik.com/bali/nusra/d-8073911/siswa-tempuh-75-km-demi-internet", "img"=>"assets/foto5.png", "title"=>"Siswa Tempuh 75 Km Demi Internet", "tag"=>"Technology"],
    ["link"=>"https://finance.detik.com/bursa-dan-valas/d-8069805/emiten-hashim-wifi-dikabarkan-mau-beli-saham-link", "img"=>"assets/foto6.png", "title"=>"Emiten WIFI Mau Beli Saham LINK", "tag"=>"Bursa & Valas"]
];

foreach($news as $n):
?>
<a href="<?= $n['link']; ?>" target="_blank" class="news-small-link">
    <div class="news-small">
        <img class="news-small-img" src="<?= $n['img']; ?>" alt="News Image"/>
        
        <div class="news-small-text">
            <h4><?= $n['title']; ?></h4>
            <span class="news-tag small"><?= $n['tag']; ?></span>
        </div>
    </div>
</a>
<?php endforeach; ?>

</div>
</div>
</div>

<!-- Footer -->
<?php include __DIR__ . "/footer.php"; ?>

<script src="auth.js"></script>
</body>
</html>
