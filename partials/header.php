<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnapStudio - Studio Foto Management</title>

    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="stylesheet" href="assets/toastr.min.css">
    <link rel="stylesheet" href="assets/DataTables-1.13.3/css/jquery.dataTables.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="assets/jquery-3.6.1.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <script src="assets/toastr.min.js"></script>
    <script src="assets/DataTables-1.13.3/js/jquery.dataTables.min.js"></script>

    <style>
        :root {
            --pink-main:    #FF6B9D;
            --pink-light:   #FFB3CE;
            --pink-pale:    #FFF0F5;
            --yellow-main:  #FFD93D;
            --yellow-light: #FFF5CC;
            --purple-main:  #C084FC;
            --purple-light: #F3E8FF;
            --teal-main:    #06B6D4;
            --teal-light:   #E0F7FA;
            --coral:        #FF8C69;
            --sidebar-bg:   #2D1B4E;
            --sidebar-hover:#FF6B9D;
            --text-dark:    #2D1B4E;
            --card-shadow:  0 8px 32px rgba(255,107,157,0.15);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0; padding: 0;
            background: linear-gradient(135deg, #FFF0F5 0%, #F3E8FF 50%, #E0F7FA 100%);
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
        }

        /* ── NAVBAR ── */
        .navbar-snapstudio {
            background: linear-gradient(90deg, #FF6B9D 0%, #C084FC 50%, #06B6D4 100%);
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            height: 64px;
            display: flex; align-items: center; padding: 0 24px;
            box-shadow: 0 4px 20px rgba(255,107,157,0.3);
        }
        .navbar-brand-text {
            font-family: 'Pacifico', cursive;
            font-size: 1.6rem;
            color: white;
            text-decoration: none;
            letter-spacing: 1px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .navbar-brand-text span { color: #FFD93D; }
        .navbar-right {
            margin-left: auto;
            display: flex; align-items: center; gap: 12px;
        }
        .welcome-badge {
            background: rgba(255,255,255,0.25);
            color: white;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            backdrop-filter: blur(4px);
        }
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.5);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
        }
        .logout-btn:hover {
            background: white;
            color: #FF6B9D;
            border-color: white;
        }

        .sidebar {
            position: fixed; top: 64px; left: 0;
            width: 230px; height: calc(100vh - 64px);
            background: var(--sidebar-bg);
            padding: 20px 0;
            overflow-y: auto;
            z-index: 999;
        }
        .sidebar-section {
            padding: 8px 16px 4px;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase;
            margin-top: 8px;
        }
        .sidebar a {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 20px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.25s;
            border-left: 3px solid transparent;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,107,157,0.15);
            color: #FF6B9D;
            border-left-color: #FF6B9D;
        }
        .sidebar a i {
            width: 20px; text-align: center;
            font-size: 1rem;
        }
        .sidebar-footer {
            padding: 16px 20px;
            margin-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-footer p {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.3);
            margin: 0;
            text-align: center;
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: 230px;
            margin-top: 64px;
            padding: 28px;
            min-height: calc(100vh - 64px);
        }

        /* ── PAGE HEADER ── */
        .page-header {
            background: white;
            border-radius: 20px;
            padding: 20px 28px;
            margin-bottom: 24px;
            box-shadow: var(--card-shadow);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
        }
        .page-header h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text-dark);
            margin: 0;
        }
        .page-header h1 span { color: #FF6B9D; }

        /* ── BUTTONS ── */
        .btn-snap-primary {
            background: linear-gradient(135deg, #FF6B9D, #C084FC);
            color: white; border: none;
            padding: 10px 22px; border-radius: 50px;
            font-weight: 700; font-size: 0.88rem;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex; align-items: center; gap: 7px;
            box-shadow: 0 4px 15px rgba(255,107,157,0.35);
        }
        .btn-snap-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,107,157,0.5);
            color: white;
        }
        .btn-snap-danger {
            background: linear-gradient(135deg, #FF6B9D, #FF4444);
            color: white; border: none;
            padding: 10px 22px; border-radius: 50px;
            font-weight: 700; font-size: 0.88rem;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex; align-items: center; gap: 7px;
            box-shadow: 0 4px 15px rgba(255,68,68,0.3);
        }
        .btn-snap-danger:hover { transform: translateY(-2px); color: white; }

        /* ── CARDS ── */
        .snap-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }
        .snap-card .card-body { padding: 24px; }

        /* ── STAT CARDS ── */
        .stat-card {
            border-radius: 20px;
            padding: 22px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }
        .stat-card::before {
            content: '';
            position: absolute; top: -30px; right: -30px;
            width: 100px; height: 100px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
        }
        .stat-card::after {
            content: '';
            position: absolute; top: 20px; right: 20px;
            width: 60px; height: 60px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .stat-card .stat-icon {
            font-size: 2.2rem;
            margin-bottom: 8px;
            opacity: 0.9;
        }
        .stat-card .stat-value {
            font-size: 2rem; font-weight: 800;
        }
        .stat-card .stat-label {
            font-size: 0.82rem; font-weight: 600;
            opacity: 0.85;
        }

        /* ── TABLE ── */
        .snap-table { width: 100%; border-collapse: collapse; }
        .snap-table thead tr {
            background: linear-gradient(135deg, #FF6B9D, #C084FC);
            color: white;
        }
        .snap-table thead th {
            padding: 14px 16px;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }
        .snap-table tbody tr {
            border-bottom: 1px solid #f0e0f0;
            transition: background 0.2s;
        }
        .snap-table tbody tr:hover { background: #FFF0F5; }
        .snap-table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            font-size: 0.88rem;
            color: var(--text-dark);
        }

        /* ── BADGES ── */
        .badge-snap {
            display: inline-block;
            padding: 4px 12px; border-radius: 50px;
            font-size: 0.75rem; font-weight: 700;
        }
        .badge-pink { background: #FFE0EC; color: #FF6B9D; }
        .badge-purple { background: #F3E8FF; color: #7C3AED; }
        .badge-teal { background: #E0F7FA; color: #0097A7; }
        .badge-yellow { background: #FFF5CC; color: #B8860B; }
        .badge-green { background: #DCFCE7; color: #16A34A; }
        .badge-red { background: #FFE4E4; color: #DC2626; }

        /* ── ACTION BUTTONS ── */
        .btn-action {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 5px;
            transition: all 0.25s;
        }
        .btn-edit {
            background: linear-gradient(135deg, #FFD93D, #FF8C69);
            color: white;
        }
        .btn-edit:hover { transform: scale(1.05); color: white; }
        .btn-delete {
            background: linear-gradient(135deg, #FF6B9D, #FF4444);
            color: white;
        }
        .btn-delete:hover { transform: scale(1.05); color: white; }

        /* ── FORMS ── */
        .form-label {
            font-weight: 700; font-size: 0.85rem;
            color: var(--text-dark); margin-bottom: 5px;
        }
        .form-control, .form-select {
            border: 2px solid #F0D0E8;
            border-radius: 12px;
            padding: 10px 14px;
            font-family: 'Nunito', sans-serif;
            font-size: 0.88rem;
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #FF6B9D;
            box-shadow: 0 0 0 4px rgba(255,107,157,0.15);
            outline: none;
        }
        textarea.form-control { resize: vertical; }

        /* ── IMAGE PREVIEW ── */
        .img-preview-wrap {
            border: 2px dashed #FFB3CE;
            border-radius: 16px;
            padding: 12px;
            text-align: center;
            background: #FFF0F5;
            min-height: 120px;
            display: flex; align-items: center; justify-content: center;
        }
        .img-preview-wrap img {
            max-width: 100%; max-height: 200px;
            border-radius: 12px;
            object-fit: cover;
        }

        /* ── TOASTR CUSTOM ── */
        .toast-title { font-family: 'Nunito', sans-serif; font-weight: 800; }
        .toast-message { font-family: 'Nunito', sans-serif; }
        #toast-container > .toast-success {
            background-color: #16A34A !important;
            border-left: 4px solid #FFD93D;
        }
        #toast-container > .toast-error {
            background-color: #DC2626 !important;
            border-left: 4px solid #FF6B9D;
        }
        #toast-container > .toast-info {
            background-color: #C084FC !important;
            border-left: 4px solid #FFD93D;
        }
        #toast-container > .toast-warning {
            background-color: #FF8C69 !important;
            border-left: 4px solid #FFD93D;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: 0.3s; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 16px; }
        }
    </style>
</head>
<body>

<nav class="navbar-snapstudio">
    <a href="/studio_foto_project/index.php" class="navbar-brand-text" style="display:flex;align-items:center;gap:10px;">
        <svg width="36" height="36" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" style="flex-shrink:0;">
            <rect width="64" height="64" rx="16" fill="rgba(255,255,255,0.25)"/>
            <rect x="10" y="23" width="44" height="27" rx="8" fill="white" fill-opacity="0.9"/>
            <rect x="22" y="15" width="20" height="12" rx="5" fill="white"/>
            <circle cx="32" cy="36" r="9" fill="rgba(6,182,212,0.5)"/>
            <circle cx="32" cy="36" r="6" fill="white"/>
            <circle cx="32" cy="36" r="3" fill="#06B6D4"/>
            <rect x="13" y="26" width="5" height="3" rx="1.5" fill="#FFD93D"/>
            <circle cx="48" cy="26" r="2.5" fill="#FF6B9D"/>
            <path d="M44 16 L45 18.5 L47.5 19.5 L45 20.5 L44 23 L43 20.5 L40.5 19.5 L43 18.5 Z" fill="#FFD93D"/>
            <path d="M18 18 L18.8 20 L21 20.8 L18.8 21.6 L18 23.6 L17.2 21.6 L15 20.8 L17.2 20 Z" fill="#FFD93D" fill-opacity="0.9"/>
        </svg>
        Snap<span>Studio</span>
    </a>
    <div class="navbar-right">
        <span class="welcome-badge"><i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="/studio_foto_project/login/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
    </div>
</nav>

<div class="sidebar">
    <div class="sidebar-section">Menu Utama</div>
    <a href="/studio_foto_project/index.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'class="active"' : ''; ?>>
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="/studio_foto_project/paket.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'paket.php') ? 'class="active"' : ''; ?>>
        <i class="fas fa-camera-retro"></i> Data Paket Foto
    </a>
    <a href="/studio_foto_project/report.php" target="_blank">
        <i class="fas fa-file-pdf"></i> Cetak Laporan PDF
    </a>
    <div class="sidebar-section">Pengaturan</div>
    <a href="/studio_foto_project/user.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'user.php') ? 'class="active"' : ''; ?>>
        <i class="fas fa-users-cog"></i> Manajemen User
    </a>
    <a href="/studio_foto_project/login/logout.php">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
    <div class="sidebar-footer">
        <p>© 2026 SnapStudio<br>Studio Foto Management</p>
    </div>
</div>
