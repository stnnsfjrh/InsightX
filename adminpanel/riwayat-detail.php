<?php
require "session.php";
require "../db.php";

$id = intval($_GET["id"]);

$data = supabase("GET", "riwayat", null, "?id=eq.$id")["data"][0];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<link rel="icon" type="image/x-icon" href="../assets/favicon.ico">

  <meta charset="UTF-8">
  <title>Detail Riwayat</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<?php require "navbar.php"; ?>

<div class="container mt-5">
  <h3>Detail Riwayat</h3>
  <ul class="list-group mt-3">
    <li class="list-group-item"><strong>Username:</strong> <?= htmlspecialchars($data['username']); ?></li>
    <li class="list-group-item"><strong>Aktivitas:</strong> <?= htmlspecialchars($data['aktivitas']); ?></li>
    <li class="list-group-item"><strong>Tanggal:</strong> <?= $data['tanggal']; ?></li>
  </ul>
  <a href="riwayat.php" class="btn btn-secondary mt-3">Kembali</a>
</div>

</body>
</html>
