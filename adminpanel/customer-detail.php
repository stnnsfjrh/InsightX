<?php
require "session.php";
require "../db.php";

$id = $_GET['id'];

// Ambil data sales berdasarkan ID
$res = supabase("GET", "sales", null, "?id=eq.$id");

$data = $res["data"][0] ?? null;

if (!$data) {
    die("<h3>Data tidak ditemukan</h3>");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<link rel="icon" type="image/x-icon" href="../assets/favicon.ico">

  <meta charset="UTF-8">
  <title>Detail Customer</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<?php require "navbar.php"; ?>

<div class="container mt-5">

  <nav aria-label="breadcrumb"> 
    <ol class="breadcrumb"> 
      <li class="breadcrumb-item">
        <a href="../adminpanel" class="no-decoration text-muted">
          <i class="fas fa-home"></i> Home
        </a>
      </li>
      <li class="breadcrumb-item">
        <a href="sales.php" class="no-decoration text-muted">Sales</a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">Detail</li>
    </ol>
  </nav>

  <h3>Detail Customer</h3>

  <ul class="list-group mt-3">
      <li class="list-group-item"><strong>No:</strong> <?= htmlspecialchars($data['id']); ?></li>
      <li class="list-group-item"><strong>Tanggal:</strong> <?= htmlspecialchars($data['tanggal']); ?></li>
      <li class="list-group-item"><strong>Nama Customer:</strong> <?= htmlspecialchars($data['nama_customer']); ?></li>
      <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($data['email']); ?></li>
      <li class="list-group-item"><strong>No HP:</strong> <?= htmlspecialchars($data['no_hp']); ?></li>
      <li class="list-group-item"><strong>Total Pembelian:</strong> <?= htmlspecialchars($data['total_pembelian']); ?></li>
  </ul>

  <a href="sales.php" class="btn btn-secondary mt-3">Kembali</a>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
