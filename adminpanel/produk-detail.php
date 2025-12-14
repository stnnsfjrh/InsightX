<?php 
require "session.php"; 
require "../db.php"; 

$id = $_GET['p'];

// ===============================
// FUNCTION UPLOAD KE SUPABASE
// ===============================
function uploadToSupabaseStorage($bucket, $fileTmpPath, $fileName)
{
    global $SUPABASE_URL, $SUPABASE_API_KEY;

    // folder di dalam bucket
    $path = "images/" . $fileName;

    $mimeType = mime_content_type($fileTmpPath);
    $fileData = file_get_contents($fileTmpPath);

    $url = rtrim($SUPABASE_URL, '/') . "/storage/v1/object/$bucket/$path";

    $headers = [
        "apikey: $SUPABASE_API_KEY",
        "Authorization: Bearer $SUPABASE_API_KEY",
        "Content-Type: $mimeType",
        "x-upsert: true"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // WAJIB PUT
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fileData);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 && $httpCode !== 201) {
        die("Upload gagal. HTTP $httpCode : $response");
    }

    return rtrim($SUPABASE_URL, '/') . "/storage/v1/object/public/$bucket/$path";
}




// ===============================
// AMBIL DETAIL PRODUK
// ===============================
$produk = supabase("GET", "produk", null, "?id=eq.$id");
$data = $produk["data"][0];

// Kategori lain
$kategoriLain = supabase("GET", "kategori", null, "?id=neq.{$data['kategori_id']}");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Produk Detail</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>form div { margin-bottom: 10px; }</style>
</head>
<body>

<?php require "navbar.php"; ?>

<div class="container mt-5">
<h2>Detail Produk</h2>

<div class="col-md-6">

<form method="post" enctype="multipart/form-data">

    <div>
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="<?= $data['nama']; ?>" required>
    </div>

    <div>
        <label>Kategori</label>
        <select name="kategori" class="form-control" required>
            <option value="<?= $data['kategori_id']; ?>">Kategori Sekarang</option>
            <?php foreach($kategoriLain["data"] as $kat){ ?>
                <option value="<?= $kat['id']; ?>"><?= $kat['nama']; ?></option>
            <?php } ?>
        </select>
    </div>

    <div>
        <label>Harga</label>
        <input type="text" name="harga" class="form-control" value="<?= $data['harga']; ?>" required>
    </div>

    <div>
        <label>Foto Sekarang</label><br>
        <img src="<?= $data['foto']; ?>" width="300">
    </div>

    <div>
        <label>Ganti Foto</label>
        <input type="file" name="foto" class="form-control">
    </div>

    <div>
        <label>Stok</label>
        <select name="ketersediaan_stok" class="form-control">
            <option value="<?= $data['ketersediaan_stok']; ?>">
                <?= $data['ketersediaan_stok']; ?>
            </option>
            <option value="<?= $data['ketersediaan_stok']=='tersedia'?'habis':'tersedia'; ?>">
                <?= $data['ketersediaan_stok']=='tersedia'?'habis':'tersedia'; ?>
            </option>
        </select>
    </div>

    <button name="simpan" class="btn btn-primary">Simpan</button>
    <button name="hapus" class="btn btn-danger">Hapus</button>

</form>

<?php
// ===============================
// PROSES HAPUS (PRIORITAS)
// ===============================
if (isset($_POST['hapus'])) {

    supabase("DELETE", "produk", null, "?id=eq.$id");

    echo "<div class='alert alert-danger mt-3'>Produk berhasil dihapus</div>";
    echo "<meta http-equiv='refresh' content='1;url=produk.php'>";
    exit;
}

// ===============================
// UPDATE PRODUK
// ===============================
if (isset($_POST['simpan'])) {

    $fotoBaru = $data['foto'];

    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fileName = "produk_" . time() . "." . $ext;

        $fotoBaru = uploadToSupabaseStorage(
            "image_produk",
            $_FILES['foto']['tmp_name'],
            $fileName
        );
    }

    supabase("PATCH", "produk", [
        "nama" => $_POST['nama'],
        "kategori_id" => $_POST['kategori'],
        "harga" => $_POST['harga'],
        "ketersediaan_stok" => $_POST['ketersediaan_stok'],
        "foto" => $fotoBaru
    ], "?id=eq.$id");

    echo "<div class='alert alert-success mt-3'>Produk diperbarui</div>";
    echo "<meta http-equiv='refresh' content='2;url=produk.php'>";
}
?>

</div>
</div>

</body>
</html>
