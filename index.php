<?php
require 'config.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $mysqli->real_escape_string($_POST['email']);
    $password = md5($_POST['password']); // simple hashing for demo
    $res = $mysqli->query("SELECT * FROM users WHERE email='$email' AND password='$password'");
    if ($res && $res->num_rows === 1) {
        $_SESSION['user'] = $res->fetch_assoc();
        header('Location: dashboard.php');
        exit;
    } else {
        $msg = 'Invalid credentials';
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SUNN - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-theme-light">

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-4">

            <div class="card shadow-sm p-4">

                <!-- UNIVERSITY LOGO -->
                <div class="text-center">
                    <img src="assets/img/logo.png" style="width:130px; height:auto;" class="mb-3">
                    <h4 class="fw-bold">STATE UNIVERSITY OF NORTHERN NEGROS</h4>
                    <p class="text-muted small">Student Patient Record System</p>
                </div>

                <?php if($msg): ?>
                    <div class="alert alert-danger mt-2"><?php echo $msg; ?></div>
                <?php endif; ?>

                <form method="post" class="mt-3">

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button class="btn btn-primary w-100">Login</button>
                </form>

                <p class="text-center mt-3 small text-muted">
                    Default: admin@example.com / admin123
                </p>

            </div>

        </div>
    </div>
</div>

</body>
</html>
