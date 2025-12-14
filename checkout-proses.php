<?php
session_start();
require "db.php";

if (!isset($_SESSION['user'])) {
    die("User belum login");
}

$user = $_SESSION['user'];
$user_id = $user['id']; // UUID
$username = $user['username'];
$email = $user['email'];
$phone = $user['phone_number'];

$produk_id = $_POST["produk_id"];
$harga = floatval($_POST["harga"]);
$metode = $_POST["metode"];

$total = $harga;

// ----------------------------------------------------
// 1️⃣ Cek user dan update total_pengeluaran
// ----------------------------------------------------
$cekUsers = supabase("GET", "users", null, "?id=eq.$user_id");

if (!empty($cekUsers["data"])) {
    $userData = $cekUsers["data"][0];
    $totalLama = floatval($userData["total_pengeluaran"] ?? 0);

    // Update total_pengeluaran user
    $updateUser = supabase(
        "PATCH",
        "users",
        [
            "total_pengeluaran" => $totalLama + $total
        ],
        "?id=eq.$user_id"
    );
} else {
    // Jika user tidak ditemukan (harusnya tidak terjadi)
    die("Data user tidak ditemukan di database Supabase.");
}

// ----------------------------------------------------
// 2️⃣ Simpan riwayat pembayaran
// ----------------------------------------------------
$insertRiwayat = supabase(
    "POST",
    "riwayat_pembayaran",
    [
        "user_id" => $user_id,
        "produk_id" => $produk_id,
        "total_harga" => $total,
        "metode_pembayaran" => $metode
    ]
);

// ----------------------------------------------------
// 3️⃣ Cek hasil dan redirect
// ----------------------------------------------------
if ($insertRiwayat["status"] == 201) {
    header("Location: success.php");
    exit;
} else {
    echo "<pre>";
    echo "Gagal menyimpan riwayat pembayaran:\n";
    print_r($insertRiwayat);
    echo "</pre>";
}
?>