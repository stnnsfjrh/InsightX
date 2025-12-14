<?php 
require "session.php"; 
require "../db.php";   // gunakan db.php yang berisi fungsi supabase()

$id = $_GET['p'] ?? null;
if (!$id) {
    echo "ID kategori tidak diberikan.";
    exit;
}

// ===============================
// AMBIL DATA KATEGORI BY ID
// ===============================
// supabase("GET", "<table>", <data|null>, <filterString>)
$res = supabase("GET", "kategori", null, "?id=eq.$id");
$data = (isset($res["data"]) && count($res["data"])>0) ? $res["data"][0] : null;

?>
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Detail Kategori</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head> 
<body> 
    <?php require "navbar.php"; ?> 

    <div class="container mt-5"> 
        <h2>Detail Kategori</h2> 

        <div class="col-12 col-md-6"> 
            <form action="" method="post">
                <div> 
                    <label for="kategori">Kategori</label> 
                    <input type="text" name="kategori" id="kategori" class="form-control" value="<?= htmlspecialchars($data['nama'] ?? ''); ?>"> 
                </div>

                <div class="mt-5 d-flex justify-content-between"> 
                    <button type="submit" class="btn btn-primary" name="editBtn">Edit</button> 
                    <button type="submit" class="btn btn-danger" name="deleteBtn">Delete</button>
                </div> 
            </form> 

<?php 
// ===============================
// UPDATE KATEGORI
// ===============================
if (isset($_POST['editBtn'])) {

    $kategori = trim(htmlspecialchars($_POST['kategori']));

    // Jika tidak ada perubahan, langsung kembali
    if ($data && $data['nama'] == $kategori) {
        echo '<meta http-equiv="refresh" content="0; url=kategori.php" />';
        exit;
    } 
    else {

        // Cek apakah nama sudah digunakan kategori lain (case-insensitive)
        $cekResp = supabase("GET", "kategori", null, "?nama=ilike." . rawurlencode($kategori));
        $cek = is_array($cekResp["data"]) ? $cekResp["data"] : [];

        // jika ada hasil dan id berbeda dari yang sedang diedit -> duplicate
        $duplicate = false;
        if (!empty($cek)) {
            foreach ($cek as $row) {
                if ((string)($row['id'] ?? '') !== (string)$id) {
                    $duplicate = true;
                    break;
                }
            }
        }

        if ($duplicate) {
            echo '<div class="alert alert-warning mt-3">Kategori Sudah Ada</div>';
        } else {

            // UPDATE DATA via PATCH
            $update = supabase("PATCH", "kategori", ["nama" => $kategori], "?id=eq.$id");

            // sukses biasanya status 200
            if (isset($update["status"]) && ($update["status"] == 200 || $update["status"] == 204)) {
                echo '<div class="alert alert-primary mt-3">Kategori Berhasil Diupdate</div>';
                echo '<meta http-equiv="refresh" content="2; url=kategori.php" />';
                exit;
            } else {
                echo '<div class="alert alert-danger mt-3">Gagal Update Kategori</div>';
            }
        }
    }
}

// ===============================
// DELETE KATEGORI
// ===============================
if (isset($_POST['deleteBtn'])) {

    // CEK APAKAH KATEGORI DIGUNAKAN DI PRODUK
    $cekProdukResp = supabase("GET", "produk", null, "?kategori_id=eq.$id");
    $cekProduk = is_array($cekProdukResp["data"]) ? $cekProdukResp["data"] : [];

    if (!empty($cekProduk)) {
        echo '<div class="alert alert-warning mt-3">Kategori tidak bisa dihapus karena digunakan di produk</div>';
        exit;
    }

    // DELETE KATEGORI
    $hapus = supabase("DELETE", "kategori", null, "?id=eq.$id");

    if (isset($hapus["status"]) && ($hapus["status"] == 200 || $hapus["status"] == 204)) {
        echo '<div class="alert alert-primary mt-3">Kategori Berhasil Dihapus</div>';
        echo '<meta http-equiv="refresh" content="2; url=kategori.php" />';
        exit;
    } else {
        echo '<div class="alert alert-danger mt-3">Gagal Menghapus Kategori</div>';
    }
}
?> 

        </div>  
    </div> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body> 
</html>