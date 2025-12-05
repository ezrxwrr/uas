<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - WebDonasi</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<h2 class="page-title">Login</h2>

<div class="form-container">
    <form>
        <label>Email</label>
        <input type="email" required>

        <label>Password</label>
        <input type="password" required>

       <div class="remember-container">
            <input type="checkbox" id="remember">
            <label for="remember">Remember Me</label>
        </div>


        <button class="btn-primary" type="submit">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</div>

<div class="footer">
    <p>© 2025 WebDonasi</p>
</div>

</body>
</html>
