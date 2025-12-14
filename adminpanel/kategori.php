<?php
require "session.php";
require "../db.php"; // ← gunakan supabase()

// ===============================
// GET DATA KATEGORI DARI SUPABASE
// ===============================
$responseKategori = supabase("GET", "kategori");
$kategoriList = $responseKategori["data"];
$jumlahKategori = $kategoriList ? count($kategoriList) : 0;


// ===============================
// TAMBAH KATEGORI
// ===============================
if (isset($_POST['simpan_kategori'])) {

    $kategori = htmlspecialchars($_POST['kategori']);
    $tampil_di = $_POST['tampil'];

    // CEK KATEGORI SUDAH ADA
    $cek = supabase("GET", "kategori", null, "?nama=eq.$kategori");

    if (!empty($cek["data"])) {
        $alert = '<div class="alert alert-warning mt-3">Kategori sudah ada</div>';
    } else {
        // INSERT KE SUPABASE
        $insert = supabase("POST", "kategori", [
            "nama" => $kategori,
            "tampil_di" => $tampil_di
        ]);

        if ($insert["status"] == 201 || $insert["status"] == 200) {
            $alert = '<div class="alert alert-primary mt-3">Kategori berhasil tersimpan</div>';
            echo '<meta http-equiv="refresh" content="2; url=kategori.php" />';
        } else {
            $alert = '<div class="alert alert-danger mt-3">Gagal menyimpan kategori!</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/x-icon" href="../assets/favicon.ico">

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kategori</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<style> 
    .no-decoration { 
        text-decoration: none; 
    }
    .table thead th {
        border-bottom: 2px solid #000;
    }
</style>

<body>
<?php require "navbar.php"; ?>

<div class="container mt-5"> 
<nav aria-label="breadcrumb"> 
    <ol class="breadcrumb"> 
        <li class="breadcrumb-item">
            <a href="../adminpanel" class="no-decoration text-muted">  
                <i class="fa-solid fa-house"></i> Home
            </a> 
        </li> 
        <li class="breadcrumb-item active" aria-current="page">Kategori</li> 
    </ol> 
</nav> 

<div class="my-5 col-12 col-md-6"> 
    <h3>Tambah Kategori</h3> 

    <form action="" method="post"> 
        <div class="mb-3"> 
            <label for="kategori">Kategori</label>  
            <input type="text" id="kategori" name="kategori" placeholder="input nama kategori" class="form-control"> 
        </div> 

        <div class="mb-3">
            <label for="tampil">Tampilkan di</label>
            <select id="tampil" name="tampil" class="form-control">
                <option value="index">Halaman Index</option>
                <option value="buy">Halaman Buy</option>
            </select>
        </div>

        <div class="mt-3"> 
            <button class="btn btn-primary" type="submit" name="simpan_kategori">Simpan</button> 
        </div> 
    </form> 

    <?php 
        if (isset($alert)) echo $alert;
    ?>
</div>

<div class="mt-3"> 
    <h2>List Kategori</h2>

    <div class="table-responsive mt-5">
        <table class="table"> 
            <thead> 
                <tr> 
                    <th>No.</th> 
                    <th>Nama</th> 
                    <th>Tampil Di</th>
                    <th>Action</th>
                </tr> 
            </thead>
            <tbody>
                <?php 
                if ($jumlahKategori == 0) { 
                    echo '<tr><td colspan="4" class="text-center">Data kategori tidak tersedia</td></tr>';
                } 
                else {
                    $no = 1;
                    foreach ($kategoriList as $data) {
                ?> 
                <tr> 
                    <td><?= $no; ?></td> 
                    <td><?= $data['nama']; ?></td>
                    <td><?= ucfirst($data['tampil_di']); ?></td>
                    <td>
                        <a href="kategori-detail.php?p=<?= $data['id']; ?>" class="btn btn-info">
                            <i class="fas fa-search"></i>
                        </a>
                    </td>
                </tr>
                <?php
                    $no++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div> 
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>

</body>
</html>
