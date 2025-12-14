<?php
require "session.php";
require "../db.php"; // ← PENTING: sekarang pakai Supabase, bukan mysqli

// ===========================
//   AMBIL DATA DARI SUPABASE
// ===========================
$kategori = supabase("GET", "kategori");
$jumlahKategori = is_array($kategori["data"]) ? count($kategori["data"]) : 0;

$produk = supabase("GET", "produk");
$jumlahProduk = is_array($produk["data"]) ? count($produk["data"]) : 0;

$sales = supabase("GET", "users");
$jumlahSales = is_array($sales["data"]) ? count($sales["data"]) : 0;

$customer = supabase("GET", "customer");
$jumlahCustomer = is_array($customer["data"]) ? count($customer["data"]) : 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/x-icon" href="../assets/favicon.ico">

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<style> 
  .kotak { 
    border: solid; 
  } 

  .summary-kategori{ 
    background-color: #0a6b4a; 
    border-radius: 15px;
  }

  .summary-produk{ 
    background-color: #0a156B; 
    border-radius: 15px;
  }

  .summary-riwayat{ 
    background-color: #F75A5A; 
    border-radius: 15px;
  }

  .summary-Sales{ 
    background-color: #84AE92; 
    border-radius: 15px;
  }

  .no-decoration{
    text-decoration: none; 
  }
</style>

<body>
  <?php require "navbar.php"; ?>
  <div class="container mt-5">
    <nav aria-label="breadcrumb"> 
      <ol class="breadcrumb"> 
        <li class="breadcrumb-item active" aria-current="page">
          <i class="fas fa-home"></i> Home 
        </li> 
      </ol> 
    </nav>

    <h2>Selamat datang <?= $_SESSION['admin']['username']; ?></h2>

    <div class="container mt-5">
      <div class="row">  

        <!-- KATEGORI -->
        <div class="col-lg-4 col-md-6 col-12 mb-3"> 
          <div class="summary-kategori p-3">
            <div class="row"> 
              <div class="col-6"> 
                <i class="fas fa-align-justify fa-7x text-black-50"></i> 
              </div> 
              <div class="col-6 text-white"> 
                <h3 class="fs-2">Kategori</h3> 
                <p class="fs-4"><?= $jumlahKategori; ?> Kategori</p> 
                <p><a href="kategori.php" class="text-white no-decoration">Lihat Detail</a></p> 
              </div>
            </div> 
          </div> 
        </div> 

        <!-- PRODUK -->
        <div class="col-lg-4 col-md-6 col-12 mb-3"> 
          <div class="summary-produk p-3">
            <div class="row"> 
              <div class="col-6"> 
                <i class="fas fa-box fa-7x text-black-50"></i> 
              </div> 
              <div class="col-6 text-white"> 
                <h3 class="fs-2">Produk</h3> 
                <p class="fs-4"><?= $jumlahProduk; ?> Produk</p> 
                <p><a href="produk.php" class="text-white no-decoration">Lihat Detail</a></p> 
              </div>
            </div>  
          </div> 
        </div> 

        <!-- CUSTOMER -->
        <div class="col-lg-4 col-md-6 col-12 mb-3"> 
          <div class="summary-riwayat p-3 bg-primary text-white" style="min-height: 170px; border-radius: 15px;">
            <div class="row align-items-center"> 
              <div class="col-4 text-center"> 
                <i class="fas fa-users fa-6x me-2"></i> 
              </div> 
              <div class="col-8"> 
                <h4 class="fw-bold mb-2">Customer</h4> 
                <p class="fs-4"><?= $jumlahCustomer; ?> Customer</p> 
                <a href="customer.php" class="text-white text-decoration-underline">Lihat Detail</a> 
              </div>
            </div>  
          </div> 
        </div>

      </div>  
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
</body>
</html>
