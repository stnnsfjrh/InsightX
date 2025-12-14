<?php
session_start();

// jika belum login, lempar ke login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// ambil data user dari session
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Akun Saya</title>

<style>
    body {
        margin: 0;
        font-family: "Inter", sans-serif;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        color: #1e293b;
    }

    .header {
        display: flex;
        align-items: center;
        padding: 18px 25px;
        background: linear-gradient(135deg, #ffffff 0%, #fafcff 100%);
        font-size: 22px;
        font-weight: 700;
        gap: 15px;
        border-bottom: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(30, 64, 175, 0.05);
        position: relative;
    }

    .header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 100%);
    }

    .header i { 
        font-size: 26px; 
        cursor: pointer; 
        color: #64748b;
        transition: all 0.3s ease;
        padding: 8px;
        border-radius: 10px;
    }

    .header i:hover { 
        color: #3b82f6; 
        background: #f1f5f9;
        transform: scale(1.1);
    }

    .profile-box {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        margin: 20px;
        padding: 25px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 6px 20px rgba(30, 64, 175, 0.08);
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .profile-box:hover {
        border-color: #3b82f6;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
    }

    .profile-box .avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 32px;
        box-shadow: 0 6px 15px rgba(59, 130, 246, 0.3);
        border: 3px solid white;
    }

    .profile-info h2 {
        margin: 0 0 5px 0;
        font-size: 22px;
        font-weight: 700;
        color: #1e293b;
    }

    .profile-info p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .profile-info p i {
        color: #3b82f6;
        font-size: 16px;
    }

    .menu-top {
        margin: 20px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        padding: 20px;
        border-radius: 18px;
        display: flex;
        justify-content: space-between;
        gap: 15px;
        border: 2px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .menu-top .btn {
        flex: 1;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        padding: 18px 12px;
        border-radius: 14px;
        text-align: center;
        font-weight: 600;
        color: #475569;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .menu-top .btn:hover {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        transform: translateY(-3px);
        border-color: #3b82f6;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    }

    .menu-top .btn i {
        font-size: 32px;
        transition: all 0.3s ease;
    }

    .menu-top .btn:hover i {
        color: white;
        transform: scale(1.1);
    }

    .section-title {
        margin: 30px 25px 15px;
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        position: relative;
        padding-bottom: 10px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 100%);
        border-radius: 2px;
    }

    .list-menu {
        margin: 15px 25px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 16px;
        border: 2px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .list-item {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 18px 20px;
        border-bottom: 1px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .list-item:last-child {
        border-bottom: none;
    }

    .list-item:hover {
        background: linear-gradient(90deg, #f1f5f9 0%, #e2e8f0 100%);
        transform: translateX(5px);
    }

    .list-item:hover::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, #3b82f6 0%, #8b5cf6 100%);
    }

    .list-item i {
        font-size: 24px;
        color: #3b82f6;
        width: 28px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .list-item:hover i {
        color: #2563eb;
        transform: scale(1.2);
    }

    .item-text {
        flex: 1;
    }

    .item-text h3 {
        margin: 0 0 5px 0;
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
    }

    .item-text p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.4;
    }

    .list-item .arrow {
        color: #94a3b8;
        font-size: 18px;
        transition: all 0.3s ease;
    }

    .list-item:hover .arrow {
        color: #3b82f6;
        transform: translateX(5px);
    }

    /* Additional styling for specific menu items */
    .list-item:nth-child(1) i { color: #3b82f6; } /* Akun Saya */
    .list-item:nth-child(2) i { color: #10b981; } /* Pengaturan */
    .list-item:nth-child(3) i { color: #8b5cf6; } /* Bantuan */
    .list-item:nth-child(4) i { color: #f59e0b; } /* Tentang */
    .list-item:nth-child(5) i { color: #ef4444; } /* Keluar */

    /* Responsive Design */
    @media (max-width: 768px) {
        .header {
            padding: 15px 20px;
            font-size: 20px;
        }
        
        .profile-box {
            margin: 15px;
            padding: 20px;
        }
        
        .menu-top {
            margin: 15px;
            padding: 15px;
            flex-direction: column;
        }
        
        .section-title {
            margin: 25px 20px 12px;
            font-size: 18px;
        }
        
        .list-menu {
            margin: 12px 20px;
        }
        
        .list-item {
            padding: 15px 18px;
        }
    }
</style>

<!-- ICON (pakai remixicon) -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>

<div class="header">
    <a href="index.php">
    <i class="ri-arrow-left-line"></i>
</a>
    account
    <div style="margin-left:auto;">
    </div>
</div>

<div class="profile-box">
    <div class="avatar"></div>
    <div>
        <b><?= htmlspecialchars($user["username"] ?? "-"); ?></b><br>
        <?= htmlspecialchars($user["phone_number"] ?? "-"); ?><br>
        <?= htmlspecialchars($user["email"] ?? "-"); ?>
    </div>
</div>


<div class="menu-top">
    <div class="btn"><i class="ri-coins-line"></i></div>
    <div class="btn"><i class="ri-ticket-fill"></i></div>
    <div class="btn"><i class="ri-bank-card-fill"></i></div>
    <div class="btn"><i class="ri-wallet-3-line"></i></div>
</div>

<div class="section-title">Menu lainnya</div>
<div class="list-menu">
    <div class="list-item"><i class="ri-book-open-line"></i> Hukum & Kebijakan</div>
    <div class="list-item"><i class="ri-information-line"></i> About</div>
    <a href="logout.php" style="text-decoration:none; color:inherit;">
    <div class="list-item"><i class="ri-logout-box-line"></i> Keluar</div>
</a>
</div>

</body>
</html>
