<?php
// Halaman Search
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>

    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            background: #f5f5f5;
        }

        h2 {
            font-size: 20px;
            font-weight: 600;
            padding: 20px;
            margin: 0;
            color: #666;
        }

        .search-page {
            padding: 20px;
        }

        /* Tombol Back */
        .back-btn {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        /* Search Bar */
        .top-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .back-btn {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            padding: 0;
        }

        .search-bar {
            flex: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #e0e0e0;
            padding: 12px 18px;
            border-radius: 25px;
        }

        .search-bar button {
            background: none;
            border: none;
            font-size: 18px;
        }

        .search-bar input {
            border: none;
            background: transparent;
            font-size: 16px;
            width: 100%;
            outline: none;
        }

        /* Category List */
        .category-list {
            margin-top: 20px;
            margin-left: 40px;   /* sejajarkan dengan search-bar (sesuaikan) */
            width: 200px;        /* pendek, sama seperti search-bar */
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .category-list .item {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #e0e0e0;
            padding: 10px 15px;
            border-radius: 22px;
            border: none;
            font-size: 15px;
            cursor: pointer;
            transition: 0.2s;
        }

        /* Hover animasi */
        .category-list .item:hover {
            background: #d5d5d5;
            transform: translateX(3px);
        }

        .category-list .item i {
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="search-page">

    <div class="top-row">
    <button class="back-btn" onclick="window.location.href='index.php'">
        <i class="ri-arrow-left-line"></i>
    </button>

        <div class="search-bar" onclick="focusSearch()">
        <button><i class="ri-search-line"></i></button>
        <input type="text" placeholder="Cari" id="searchInput" autofocus>
    </div>
    </div>

</div>

    <div class="category-list">
        <button class="item"><i class="ri-search-line"></i> Combo</button>
        <button class="item"><i class="ri-search-line"></i> Games</button>
        <button class="item"><i class="ri-search-line"></i> Music</button>
        <button class="item"><i class="ri-search-line"></i> Internet</button>
        <button class="item"><i class="ri-search-line"></i> Voice</button>
        <button class="item"><i class="ri-search-line"></i> Roaming</button>
    </div>
</div>

<script>
function focusSearch() {
    document.getElementById("searchInput").focus();
}
</script>
</body>
</html>
