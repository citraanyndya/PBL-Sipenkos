<?php
/** Header admin (komponen Admin/Header di Figma): logo, nama kos, notifikasi, profil. */
?>
<header class="topbar">
    <div class="topbar-left">
        <button type="button" class="icon-btn sidebar-toggle" data-sidebar-toggle aria-label="Buka menu">
            <i class="icon icon-menu" aria-hidden="true"></i>
        </button>
        <a href="dashboard.php" class="logo" aria-label="SIPENKOS - Dashboard">
            <span>SIPENKOS</span>
        </a>
        <span class="topbar-divider topbar-divider-sm"></span>
        <span class="topbar-kos">Kos Bu Nining</span>
    </div>
    <div class="topbar-right">
        <a href="notifikasi.php" class="icon-btn notif-btn" aria-label="Notifikasi">
            <i class="icon icon-bell" aria-hidden="true"></i>
            <span class="notif-dot" data-notif-dot></span>
        </a>
        <span class="topbar-divider"></span>
        <div class="profile-btn profile-static">
            <span class="profile-avatar">A</span>
            <span class="profile-name">Admin Kos</span>
        </div>
    </div>
</header>

<script src="../assets/js/common/sesi.js"></script>
