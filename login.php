<?php
// Simple login handler
session_start();

$error = '';

function login_log(string $msg)
{
    $file = __DIR__ . '/login.log';
    $entry = '[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL;
    @file_put_contents($file, $entry, FILE_APPEND | LOCK_EX);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Email dan password wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email tidak valid.';
    } else {
        $mysqli = include 'koneksi.php';
        $stmt = $mysqli->prepare('SELECT user_id,nama,email,password FROM users WHERE email = ? LIMIT 1');
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->bind_result($user_id, $nama, $db_email, $hash);
            if ($stmt->fetch()) {
                if (password_verify($password, $hash)) {
                    // successful login
                    $_SESSION['user'] = ['user_id' => $user_id, 'nama' => $nama, 'email' => $db_email];
                    login_log("SUCCESS: user_id={$user_id} email={$db_email} nama={$nama}");
                    $stmt->close();
                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'Email atau password salah.';
                    login_log("FAIL: bad password for email={$email}");
                }
            } else {
                $error = 'Email atau password salah.';
                login_log("FAIL: no user for email={$email}");
            }
            $stmt->close();
        } else {
            $error = 'Kesalahan server: ' . $mysqli->error;
            login_log("ERROR: prepare failed email={$email} err=" . $mysqli->error);
        }
    }
}

include 'navbar.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Sociomile</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>.form-container{max-width:420px;margin:0 auto;padding:1rem}</style>
</head>
<body>

<h2 class="page-title">Login</h2>

<div class="form-container">
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="login.php">
        <label>Email</label>
        <input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

        <label>Password</label>
        <input type="password" name="password" required>

       <div class="remember-container">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Remember Me</label>
        </div>


        <button class="btn-primary" type="submit">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</div>

<div class="footer">
    <p>© 2025 Sociomile</p>
    </div>
=======
    <p>© 2025 Sociomile</p>
</div>


</body>
</html>
