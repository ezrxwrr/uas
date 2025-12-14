<?php
include 'navbar.php';

// Require login
if (empty($_SESSION['user']['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = (int)$_SESSION['user']['user_id'];
$mysqli = include 'koneksi.php';

$stmt = $mysqli->prepare("SELECT d.donation_id, d.nominal, p.metode, p.payment_id
                          FROM donasi d
                          LEFT JOIN pembayaran p ON d.payment_id = p.payment_id
                          WHERE d.user_id = ?
                          ORDER BY d.donation_id DESC");

$history = [];
if ($stmt) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($donation_id, $nominal, $metode, $payment_id);
    while ($stmt->fetch()) {
        $history[] = ['donation_id' => $donation_id, 'nominal' => $nominal, 'metode' => $metode, 'payment_id' => $payment_id];
    }
    $stmt->close();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Donasi - Sociomile</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>table{width:100%;border-collapse:collapse}th,td{padding:.5rem;border:1px solid #ddd;text-align:left}.empty{color:#666;padding:1rem}</style>
</head>
<body>

<h2 class="page-title">Riwayat Donasi</h2>

<?php if (empty($history)): ?>
    <div class="empty">Belum ada riwayat donasi. <a href="donasi.php">Donasi sekarang</a></div>
<?php else: ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Nominal</th>
            <th>Metode</th>
            <th>Transaksi ID</th>
        </tr>
        <?php foreach ($history as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['donation_id']); ?></td>
                <td>Rp <?php echo number_format((int)$row['nominal'],0,',','.'); ?></td>
                <td><?php echo htmlspecialchars($row['metode'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($row['payment_id'] ?? '-'); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<div style="text-align:center;margin-top:1rem;">
    <a href="donasi.php" class="btn-primary">Donasi Lagi</a>
</div>

<div class="footer">
    <p>© 2025 Sociomile</p>
    </div>

</body>
</html>
