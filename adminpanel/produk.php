<?php 
require "session.php";
require "../db.php"; // memakai fungsi supabase()

function uploadToSupabaseStorage($bucket, $fileTmpPath, $fileName)
{
    global $SUPABASE_URL, $SUPABASE_API_KEY;

    // Tentukan MIME type otomatis dari file
    $mimeType = mime_content_type($fileTmpPath);

    $url = $SUPABASE_URL . "storage/v1/object/$bucket/$fileName";

    $fileData = file_get_contents($fileTmpPath);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);

    $headers = [
        "apikey: $SUPABASE_API_KEY",
        "Authorization: Bearer $SUPABASE_API_KEY",
        "Content-Type: $mimeType", // <--- perbaikan disini
        "x-upsert: true",
        "Content-Length: " . strlen($fileData)
    ];

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $fileData);

    $response = curl_exec($curl);
    $http = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return [
        "status" => $http,
        "url" => $SUPABASE_URL . "storage/v1/object/public/$bucket/$fileName"
    ];
}



// --------------------------------------------------------
// AMBIL SEMUA PRODUK
// --------------------------------------------------------
$resProduk = supabase(
    "GET",
    "produk",
    null,
    "?select=id,nama,harga,foto,ketersediaan_stok,kategori(id,nama)"
);

$produk = $resProduk["data"] ?? [];
$jumlahProduk = count($produk);

// --------------------------------------------------------
// AMBIL SEMUA KATEGORI
// --------------------------------------------------------
$resKategori = supabase("GET", "kategori");
$kategoriList = $resKategori["data"] ?? [];

// --------------------------------------------------------
function generateRandomString($length = 10) { 
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    $charactersLength = strlen($characters); 
    $randomString = ''; 
    for ($i = 0; $i < $length; $i++) { 
        $randomString .= $characters[rand(0, $charactersLength - 1)]; 
    } 
    return $randomString;
}
?>

<!DOCTYPE html> 
<html lang="en"> 
<head>
<link rel="icon" type="image/x-icon" href="../assets/favicon.ico">
 
    <meta charset="UTF-8"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Produk</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head> 

<style> 
    .no-decoration { text-decoration: none; }
    form div{ margin-bottom: 10px; } 
</style>

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
            <li class="breadcrumb-item active" aria-current="page">Produk</li> 
        </ol> 
    </nav>  


    <!-- ========================================================= -->
    <!-- FORM TAMBAH PRODUK -->
    <!-- ========================================================= -->

    <div class="my-5 col-12 col-md-6">
        <h3>Tambah Produk</h3>

        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" class="form-control" required>
            </div>

            <div>
                <label for="kategori">Kategori</label>
                <select name="kategori" id="kategori" class="form-control" required>
                    <option value="">Pilih satu</option>
                    <?php foreach($kategoriList as $k){ ?>
                        <option value="<?= $k['id']; ?>"><?= $k['nama']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div>
                <label for="harga">Harga</label>
                <input type="number" class="form-control" name="harga" required>
            </div>

            <div>
                <label for="foto">Foto</label>
                <input type="file" name="foto" id="foto" class="form-control">
            </div>

            <div>
                <label for="detail">Detail</label>
                <textarea name="detail" id="detail" rows="1" class="form-control"></textarea>
            </div>

            <div>
                <label for="ketersediaan_stok">Ketersediaan Stok</label>
                <select name="ketersediaan_stok" id="ketersediaan_stok" class="form-control">
                    <option value="tersedia">tersedia</option>
                    <option value="habis">habis</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
        </form>

        <?php
        // =========================================================
        // LOGIKA SIMPAN PRODUK
        // =========================================================
        if(isset($_POST['simpan'])){

            $nama     = $_POST['nama'];
            $kategori = $_POST['kategori'];
            $harga    = $_POST['harga'];
            $detail   = $_POST['detail'];
            $stok     = $_POST['ketersediaan_stok'];

            $target_dir = "../image/";
            $nama_file  = basename($_FILES["foto"]["name"]);
            $image_ext  = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
            $image_size = $_FILES["foto"]["size"];

            $random_name = generateRandomString(20);
            $new_name = $random_name . "." . $image_ext;

            if ($nama == "" || $kategori == "" || $harga == "") {
                echo '<div class="alert alert-warning mt-3">Nama, kategori dan harga wajib diisi</div>';
            } else {

                $imageUrl = ""; // default
                if ($nama_file != "") {

                        if ($image_size > 2000000) { // 2MB maksimum
                            echo '<div class="alert alert-warning mt-3">Ukuran file max 2 MB</div>';
                        } else {

                            // Upload ke Supabase Storage (bucket: image_produk)
                            $tmp = $_FILES["foto"]["tmp_name"]; // FIX
                            $upload = uploadToSupabaseStorage("image_produk", $tmp, $new_name);

                            if ($upload["status"] == 200 || $upload["status"] == 201) {
                                $imageUrl = $upload["url"]; // URL public
                            } else {
                                echo '<div class="alert alert-danger mt-3">Gagal upload gambar ke Supabase Storage</div>';
                            }
                        }
                }

                // ===== INSERT KE SUPABASE =====
                $insert = supabase(
                    "POST",
                    "produk",
                    [
                        "kategori_id"       => $kategori,
                        "nama"              => $nama,
                        "harga"             => $harga,
                        "foto"              => $imageUrl,
                        "detail"            => $detail,
                        "ketersediaan_stok" => $stok
                    ]
                );

                if($insert["status"] == 201){
                    echo '<div class="alert alert-primary mt-3">Produk Berhasil Tersimpan</div>';
                    echo '<meta http-equiv="refresh" content="2; url=produk.php" />';
                } else {
                    echo '<div class="alert alert-danger mt-3">Gagal menyimpan produk</div>';
                }
            }
        }
        ?>

    </div>


    <!-- ========================================================= -->
    <!-- TABEL LIST PRODUK -->
    <!-- ========================================================= -->

    <div class="mt-3 mb-5">
        <h2>List Produk</h2>

        <div class="table-responsive mt-5">
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Ketersediaan Stok</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                <?php if($jumlahProduk == 0){ ?>
                    <tr>
                        <td colspan="6" class="text-center">Data produk tidak tersedia</td>
                    </tr>

                <?php } else { 
                    $no = 1;
                    foreach($produk as $p){ ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $p['nama']; ?></td>
                            <td><?= $p['kategori']['nama'] ?? '-'; ?></td>
                            <td><?= "Rp " . number_format($p['harga'],0,',','.'); ?></td>
                            <td><?= $p['ketersediaan_stok']; ?></td>
                            <td>
                                <a href="produk-detail.php?p=<?= $p['id']; ?>" class="btn btn-info">
                                    <i class="fas fa-search"></i>
                                </a>
                            </td>
                        </tr>
                <?php }} ?>
                </tbody>

            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>

</body>
</html>