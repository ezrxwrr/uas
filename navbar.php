<?php
session_start(); // untuk cek login

if (isset($_SESSION['user'])) {
    // NAVBAR UNTUK USER YANG SUDAH LOGIN
    ?>
    <nav class="navbar">
        <div class="logo"><a href="index.php">Sociomile</a></div>
        <ul class="nav-links">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="donasi.php">Donasi</a></li>
            <li><a href="riwayat.php">Riwayat Donasi</a></li>
            <li><a href="leaderboard.php">Leaderboard</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php" class="btn-logout">Logout</a></li>
        </ul>
    </nav>
    <?php
} else {
    // NAVBAR UNTUK USER YANG BELUM LOGIN
    ?>
    <nav class="navbar">
        <div class="logo"><a href="index.php">Sociomile</a></div>
        <ul class="nav-links">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="leaderboard.php">Leaderboard</a></li>
            <li><a href="donasi.php">Donasi</a></li>
            <li><a href="login.php" class="btn-login">Login</a></li>
        </ul>
    </nav>
    <?php
}
?>
