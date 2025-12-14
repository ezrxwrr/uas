<?php
include 'navbar.php';

$mysqli = include 'koneksi.php';

// Fetch top donors by total donated amount
$sql = "SELECT u.user_id,u.nama,COALESCE(SUM(d.nominal),0) AS total, COUNT(d.donation_id) AS transaksi
        FROM users u
        LEFT JOIN donasi d ON u.user_id = d.user_id
        GROUP BY u.user_id
        ORDER BY total DESC, transaksi DESC
        LIMIT 50";
$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Leaderboard - Sociomile</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>.leaderboard-wrapper{max-width:900px;margin:0 auto;padding:1rem} table{width:100%;border-collapse:collapse}th,td{padding:.5rem;border:1px solid #ddd;text-align:left}.badge{padding:.2rem .5rem;border-radius:4px;color:#fff}.badge-gold{background:#c99a2a}.badge-silver{background:#9ea7b0}.badge-bronze{background:#c07b3a}</style>
</head>
<body>

<h2 class="page-title">Leaderboard Donatur</h2>

<div class="leaderboard-wrapper">
<table>
    <tr>
        <th>Rank</th>
        <th>Nama</th>
        <th>Badge</th>
        <th>Total Donasi</th>
        <th>Transaksi</th>
    </tr>

    <?php
    $rank = 1;
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $total = (int)$row['total'];
            $transaksi = (int)$row['transaksi'];
            // Simple badge logic based on total donations
            if ($total >= 100000) {
                $badge = '<span class="badge badge-gold">Gold</span>';
            } elseif ($total >= 50000) {
                $badge = '<span class="badge badge-silver">Silver</span>';
            } elseif ($total > 0) {
                $badge = '<span class="badge badge-bronze">Bronze</span>';
            } else {
                $badge = '<span>-</span>';
            }
            echo '<tr>';
            echo '<td>' . $rank++ . '</td>';
            echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
            echo '<td>' . $badge . '</td>';
            echo '<td>Rp ' . number_format($total,0,',','.') . '</td>';
            echo '<td>' . $transaksi . '</td>';
            echo '</tr>';
        }
        $result->free();
    } else {
        echo '<tr><td colspan="5">Tidak ada data.</td></tr>';
    }
    ?>
</table>
</div>

<div class="footer">
    <p>© 2025 Sociomile</p>
    </div>
=======
    <p>© 2025 Sociomile</p>
</div>

</body>
</html>
