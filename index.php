<?php
session_start();
require_once 'includes/db_conn.php';

$error = '';
$loginIdentifier = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginIdentifier = trim($_POST['login_id'] ?? '');
    $password        = $_POST['password'] ?? '';

    if ($loginIdentifier === '' || $password === '') {
        $error = 'Masukkan email/username dan password.';
    } else {
        try {
            $pdo = getDbConnection();
            $stmt = $pdo->prepare('
                SELECT id, email, username, phone, birthdate, password_hash
                FROM users
                WHERE email = :email_id OR username = :user_id
                LIMIT 1
            ');
            $stmt->execute([
                ':email_id' => $loginIdentifier,
                ':user_id'  => $loginIdentifier,
            ]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);

                $birthParts = explode('-', (string) ($user['birthdate'] ?? ''));
                $_SESSION['user_id']  = (int) $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['profile']  = [
                    'username' => $user['username'],
                    'email'    => $user['email'] ?? '',
                    'phone'    => $user['phone'] ?? '',
                    'dd'       => $birthParts[2] ?? '',
                    'mm'       => $birthParts[1] ?? '',
                    'yyyy'     => $birthParts[0] ?? '',
                    'gender'   => 'Man',
                    'avatar'   => '',
                ];

                header('Location: home.php');
                exit;
            }

            $error = 'Email/username atau password salah.';
        } catch (Throwable $e) {
            $error = 'Gagal memproses login. Periksa koneksi database.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ND - Login Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #ffffff;
      color: #000000;
    }

    .topbar-login {
      background-color: #9da4a8;
      padding: 16px 32px;
      font-size: 1.5rem;
      font-weight: 700;
    }

    .logo-circle {
      width: 190px;
      height: 190px;
      background-color: #c7cfd6;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
      font-size: 0.95rem;
      color: #222;
    }

    .wire-input {
      background-color: #c7cfd6;
      border: none;
      border-radius: 10px;
      height: 40px;
      padding: 6px 14px;
    }

    .wire-input:focus {
      background-color: #bcc5cd;
      box-shadow: none;
      outline: 2px solid #8a949c;
    }

    .btn-wire {
      background-color: #c7cfd6;
      color: #000;
      border: none;
      border-radius: 12px;
      padding: 11px 34px;
      font-size: 1.15rem;
      font-weight: 500;
    }

    .btn-wire:hover {
      background-color: #b5bec7;
      color: #000;
    }
  </style>
</head>
<body>

  <header class="topbar-login">
    Login Page
  </header>

  <main class="container py-4 py-md-5">
    <div class="row align-items-center justify-content-center g-4">
      <div class="col-md-4 text-center d-none d-md-block">
        <div class="logo-circle">LOGO</div>
        <div class="fw-bold fs-4 mt-3">ND Store</div>
      </div>

      <div class="col-12 col-md-7 col-lg-6">
        <h2 class="fw-bold mb-3">Log in here:</h2>

        <?php if ($error !== ''): ?>
          <div class="alert alert-danger py-2" style="max-width: 420px;">
            <?= htmlspecialchars($error) ?>
          </div>
        <?php endif; ?>

        <form method="POST" style="max-width: 420px;">
          <div class="mb-3">
            <label for="loginId" class="form-label fs-5 mb-1">Email or Username:</label>
            <input
              type="text"
              id="loginId"
              name="login_id"
              class="form-control wire-input"
              value="<?= htmlspecialchars($loginIdentifier) ?>"
              required
            >
          </div>

          <div class="mb-4">
            <label for="password" class="form-label fs-5 mb-1">Password:</label>
            <input
              type="password"
              id="password"
              name="password"
              class="form-control wire-input"
              required
            >
          </div>

          <div class="d-flex align-items-center justify-content-between gap-3">
            <a href="signup.php" class="text-dark">Belum punya akun? Sign Up</a>
            <button type="submit" class="btn btn-wire">Log In</button>
          </div>
        </form>
      </div>
    </div>
  </main>

</body>
</html>