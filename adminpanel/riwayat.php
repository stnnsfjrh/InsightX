<?php
require "session.php";
require "../db.php";

// Handle hapus data
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    supabase("DELETE", "pemesanan", null, "?id_pemesanan=eq.$id");
    echo "<script>alert('Data berhasil dihapus'); window.location='riwayat.php';</script>";
    exit;
}

// Handle kirim WA
if (isset($_GET['send'])) {
    $id = intval($_GET['send']);

    // Ambil pemesanan
    $pesanan = supabase("GET", "pemesanan", null, "?id_pemesanan=eq.$id")["data"][0];

    // Ambil customer
    $customer = supabase("GET", "customer", null, "?id_customer=eq.".$pesanan["id_customer"])["data"][0];

    // Ambil user (sales)
    $sales = supabase("GET", "users", null, "?id=eq.".$pesanan["id_user"])["data"][0];

    // Ambil produk
    $produk = supabase("GET", "produk", null, "?id=eq.".$pesanan["id_produk"])["data"][0];

    // Persiapan pesan
    $harga = number_format($produk["harga"], 0, ',', '.');
    $cicilan = $pesanan["jumlah_cicilan"];
    $cicilan_perbulan = ($cicilan > 0) ? number_format($produk["harga"] / $cicilan, 0, ',', '.') : "N/A";

    $message = "*[NOTIFIKASI PEMESANAN SPK]*\n\n"
             . "Halo *{$customer['nama_pemesan']}*, 👋\n"
             . "Terima kasih telah melakukan pemesanan.\n\n"
             . "🗓️ *Tanggal:* {$pesanan['tanggal_pemesanan']}\n"
             . "👤 *Sales:* {$sales['username']}\n"
             . "🚘 *Motor:* {$produk['nama']}\n"
             . "💰 *Harga:* Rp $harga\n"
             . "💳 *Pembayaran:* {$customer['metode_pembayaran']}\n"
             . "🏷️ *Cicilan:* {$cicilan}x (Rp $cicilan_perbulan)\n\n"
             . "Pesanan sedang diproses.\n\n"
             . "JG Motor 🙏";

    // Kirim WA via Fonnte
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.fonnte.com/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            "target" => $customer["no_handphone"],
            "message" => $message,
            "delay" => 2
        ],
        CURLOPT_HTTPHEADER => ["Authorization: RHpJ98oAUWLckE2jZAXt"]
    ]);

    curl_exec($curl);
    curl_close($curl);

    echo "<script>alert('Pesan berhasil dikirim'); window.location='riwayat.php';</script>";
    exit;
}

// ---------- MENAMPILKAN DATA TABEL ----------

// Ambil semua pemesanan
$list = supabase("GET", "pemesanan")["data"];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat SPK</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php require "navbar.php"; ?>
<div class="container mt-5">

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="../adminpanel" class="text-muted"><i class="fas fa-home"></i> Home</a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">Riwayat</li>
    </ol>
  </nav>

  <h2 class="mb-4">Daftar Riwayat SPK</h2>

  <table class="table table-striped table-bordered">
    <thead class="table-dark text-center">
      <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Nama Customer</th>
        <th>Sales</th>
        <th>NIK</th>
        <th>Jenis Pembayaran</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody class="text-center">

<?php
$no = 1;

foreach ($list as $p) {

    $customer = supabase("GET", "customer", null, "?id_customer=eq.".$p["id_customer"])["data"][0];
    $user     = supabase("GET", "users", null, "?id=eq.".$p["id_user"])["data"][0];

    echo "
      <tr>
        <td>{$no}</td>
        <td>{$p['tanggal_pemesanan']}</td>
        <td>{$customer['nama_pemesan']}</td>
        <td>{$user['username']}</td>
        <td>{$customer['nik']}</td>
        <td>{$customer['metode_pembayaran']}</td>
        <td>
            <a href='?delete={$p['id_pemesanan']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Hapus data ini?\")'>Hapus</a>
            <a href='?send={$p['id_pemesanan']}' class='btn btn-success btn-sm' onclick='return confirm(\"Kirim pesan WA ke customer?\")'>Kirim</a>
        </td>
      </tr>
    ";

    $no++;
}
?>

    </tbody>
  </table>
</div>
</body>
</html>
