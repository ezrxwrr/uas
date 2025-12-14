<?php
include 'navbar.php';

$error = '';
$success = '';

function register_log(string $msg)
{
    $file = __DIR__ . '/register.log';
    $entry = '[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL;
    @file_put_contents($file, $entry, FILE_APPEND | LOCK_EX);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $error = 'Semua field wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email tidak valid.';
    } elseif ($password !== $password2) {
        $error = 'Password dan konfirmasi tidak sama.';
    } else {
        $mysqli = include 'koneksi.php';

        // Check if email already exists
        $stmt = $mysqli->prepare('SELECT user_id FROM users WHERE email = ?');
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $error = 'Email sudah terdaftar.';
            }
            $stmt->close();
        }
    }

    if ($error === '') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare('INSERT INTO users (nama, email, password) VALUES (?, ?, ?)');
        if ($stmt) {
            $stmt->bind_param('sss', $name, $email, $hash);
            if ($stmt->execute()) {
                $newId = $stmt->insert_id;
                $success = 'Pendaftaran berhasil. Silakan <a href="login.php">login</a>.';
                register_log("SUCCESS: created user_id={$newId} email={$email} nama={$name}");
            } else {
                $error = 'Gagal menyimpan akun: ' . $stmt->error;
                register_log("ERROR: insert failed email={$email} err=" . $stmt->error);
            }
            $stmt->close();
        } else {
            $error = 'Gagal menyiapkan query: ' . $mysqli->error;
            register_log("ERROR: prepare failed email={$email} err=" . $mysqli->error);
        }
    }

    // If there was an error and not already logged above, log it
    if ($error !== '' && strpos($error, 'ERROR:') !== 0) {
        register_log("ERROR: registration failed email={$email} msg={$error}");
    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Sociomile</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>.form-container{max-width:420px;margin:0 auto;padding:1rem}</style>
</head>
<body>

<h2 class="page-title">Daftar Akun</h2>

<div class="form-container">
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="post" action="register.php">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" required value="<?php echo htmlspecialchars($_POST['nama'] ?? ''); ?>">

        <label>Email</label>
        <input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Konfirmasi Password</label>
        <input type="password" name="password2" required>

        <button class="btn-primary" type="submit">Daftar</button>
    </form>

    <p>Sudah punya akun? <a href="login.php">Login</a></p>
</div>

<div class="footer">
    <p>© 2025 Sociomile</p>
    </div>


</body>
</html>
