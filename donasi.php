<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Donasi - Kitabisa</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<h2 class="donation-title">Ayo Donasi Sekarang</h2>
<p class="donation-subtitle">Berikan bantuanmu untuk mereka yang membutuhkan</p>

<div class="form-container">
    <form>

        <!-- NAMA DONATUR -->
        <label>Nama Donatur</label>
        <input type="text" name="nama" placeholder="Masukkan nama" required>

        <!-- NOMINAL DONASI (AUTO RUPIAH) -->
        <label>Nominal Donasi</label>
        <div class="input-group">
            <input type="text" id="nominal" name="nominal" placeholder="Masukkan Nominal" required>
        </div>

        <!-- METODE PEMBAYARAN -->
        <label>Metode Pembayaran</label>
        <select name="metode" required>
            <option value="" disabled selected>Pilih metode pembayaran</option>
            <option value="transfer">Transfer Bank</option>
            <option value="ewallet">E-Wallet (Dana / OVO / Gopay)</option>
            <option value="qris">QRIS</option>
        </select>

        <button type="submit" class="btn-primary">Kirim Donasi</button>
    </form>
</div>

<div class="footer">
    <p>© 2025 WebDonasi</p>
</div>

<script src="assets/js/main.js"></script>
</body>
</html>
