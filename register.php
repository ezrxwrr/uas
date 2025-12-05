<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - WebDonasi</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<h2 class="page-title">Daftar Akun</h2>

<div class="form-container">
    <form>
        <label>Nama Lengkap</label>
        <input type="text" required>

        <label>Email</label>
        <input type="email" required>

        <label>Password</label>
        <input type="password" required>

        <label>Konfirmasi Password</label>
        <input type="password" required>

        <button class="btn-primary" type="submit">Daftar</button>
    </form>

    <p>Sudah punya akun? <a href="login.php">Login</a></p>
</div>

<div class="footer">
    <p>© 2025 WebDonasi</p>
</div>

</body>
</html>
