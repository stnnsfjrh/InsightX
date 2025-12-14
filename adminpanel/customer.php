<?php
require "session.php";
require "../db.php";

// Inisialisasi keyword
$keyword = "";
$filterQuery = "";

// Jika tombol cari ditekan
if (isset($_GET['cari'])) {
    $keyword = $_GET['keyword'];

    // Gunakan filter Supabase (ilike = case-insensitive)
    $filterQuery = "?or=(username.ilike.*$keyword*,email.ilike.*$keyword*)";
} else {
    $filterQuery = "";
}

// Ambil data pelanggan dari Supabase
$res = supabase("GET", "users", null, $filterQuery);

$dataCustomer = $res["data"];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Customer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
      <li class="breadcrumb-item active" aria-current="page">Customer</li> 
    </ol> 
  </nav>

  <h2 class="mb-4">Daftar Customer</h2>

  <!-- Form Pencarian -->
  <form method="GET" class="row g-3 mb-4">
    <div class="col-md-4">
      <input type="text" name="keyword" class="form-control" 
             placeholder="Cari berdasarkan Nama atau Email" 
             value="<?= htmlspecialchars($keyword) ?>">
    </div>
    <div class="col-md-2">
      <button type="submit" name="cari" class="btn btn-primary">Cari</button>
      <a href="sales.php" class="btn btn-secondary">Reset</a>
    </div>
  </form>

  <!-- Tabel Customer -->
  <table class="table table-bordered table-striped table-hover">
    <thead class="table-dark text-center">
      <tr>
        <th>No</th>
        <th>Nama Customer</th>
        <th>Email</th>
        <th>No. Telepon</th>
        <th>(RP) Total Pembelian</th>
      </tr>
    </thead>
    <tbody class="text-center">

      <?php
      if (!empty($dataCustomer)) {
          $no = 1;
          foreach ($dataCustomer as $row) {
            $dataTotalPengeluaran = 0;
            if ($row['total_pengeluaran'] === null ){
              $dataTotalPengeluaran = 0;
            } else{
              $dataTotalPengeluaran = $row["total_pengeluaran"];
            }
              echo "<tr>
                     <td>{$no}</td>
                     <td>{$row['username']}</td>
                     <td>{$row['email']}</td>
                     <td>{$row['phone_number']}</td>
                     <td>{$row['total_pengeluaran']}</td>
                    </tr>";
              $no++;
          }
      } else {
          echo "<tr><td colspan='6'>Data tidak ditemukan</td></tr>";
      }
      ?>

    </tbody>
  </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
