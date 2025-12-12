<?php
session_start();
include 'navbar.php';

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Must be logged in to donate
    if (empty($_SESSION['user']['user_id'])) {
        $err = 'Silakan login terlebih dahulu untuk melakukan donasi.';
    } else {
        $user_id = (int)$_SESSION['user']['user_id'];
        $nama = trim($_POST['nama'] ?? '');
        $nominal_raw = str_replace(['.',',',' '], ['', '', ''], $_POST['nominal'] ?? '0');
        $nominal = (int)$nominal_raw;
        $metode = $_POST['metode'] ?? '';

        if ($nama === '' || $nominal <= 0 || $metode === '') {
            $err = 'Semua field wajib diisi dengan benar.';
        } else {
            $mysqli = include 'koneksi.php';

            // Insert donation
            $stmt = $mysqli->prepare('INSERT INTO donasi (user_id, payment_id, nominal) VALUES (?, 0, ?)');
            if ($stmt) {
                $stmt->bind_param('ii', $user_id, $nominal);
                if ($stmt->execute()) {
                    $donation_id = $stmt->insert_id;
                    $stmt->close();
                    // Insert pembayaran row linking donation
                    $stmt2 = $mysqli->prepare('INSERT INTO pembayaran (donation_id, user_id, metode) VALUES (?, ?, ?)');
                    if ($stmt2) {
                        $stmt2->bind_param('iis', $donation_id, $user_id, $metode);
                        if ($stmt2->execute()) {
                            // update donasi.payment_id with new payment id
                            $payment_id = $stmt2->insert_id;
                            $stmt2->close();
                            $up = $mysqli->prepare('UPDATE donasi SET payment_id = ? WHERE donation_id = ?');
                            if ($up) {
                                $up->bind_param('ii', $payment_id, $donation_id);
                                $up->execute();
                                $up->close();
                            }
                            $msg = 'Terima kasih, donasi Anda berhasil terkirim.';
                        } else {
                            $err = 'Gagal menyimpan pembayaran: ' . $stmt2->error;
                            $stmt2->close();
                        }
                    } else {
                        $err = 'Gagal menyiapkan penyimpanan pembayaran: ' . $mysqli->error;
                    }
                } else {
                    $err = 'Gagal menyimpan donasi: ' . $stmt->error;
                    $stmt->close();
                }
            } else {
                $err = 'Gagal menyiapkan query donasi: ' . $mysqli->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donasi - WebDonasi</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>.form-container{max-width:520px;margin:0 auto;padding:1rem}</style>
</head>
<body>

<h2 class="donation-title">Ayo Donasi Sekarang!</h2>
<p class="donation-subtitle">Berikan bantuanmu untuk mereka yang membutuhkan</p>

<div class="form-container">
    <?php if ($err): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($err); ?></div>
    <?php endif; ?>
    <?php if ($msg): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <form method="post" action="donasi.php">

        <!-- NAMA DONATUR -->
        <label>Nama Donatur</label>
        <input type="text" name="nama" placeholder="Masukkan nama" required value="<?php echo htmlspecialchars($_POST['nama'] ?? ($_SESSION['user']['nama'] ?? '')); ?>">

        <!-- NOMINAL DONASI (AUTO RUPIAH) -->
        <label>Nominal Donasi</label>
        <div class="input-group">
            <input type="text" id="nominal" name="nominal" placeholder="Masukkan Nominal" required value="<?php echo htmlspecialchars($_POST['nominal'] ?? ''); ?>">
        </div>

        <!-- METODE PEMBAYARAN -->
        <label>Metode Pembayaran</label>
        <select name="metode" required>
            <option value="" disabled <?php echo empty($_POST['metode']) ? 'selected' : ''; ?>>Pilih metode pembayaran</option>
            <option value="transfer" <?php echo (($_POST['metode'] ?? '')==='transfer') ? 'selected' : ''; ?>>Transfer Bank</option>
            <option value="ewallet" <?php echo (($_POST['metode'] ?? '')==='ewallet') ? 'selected' : ''; ?>>E-Wallet (Dana / OVO / Gopay)</option>
            <option value="qris" <?php echo (($_POST['metode'] ?? '')==='qris') ? 'selected' : ''; ?>>QRIS</option>
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
