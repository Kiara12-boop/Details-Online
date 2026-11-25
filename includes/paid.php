<?php
session_start();
$orderCode = trim($_GET['order_id'] ?? '');
?>
<!DOCTYPE html>
<html>
<head>
  <title>Payment Finish</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #ffffff;
      color: #000000;
      margin: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .finish-topbar {
      background-color: #c7cfd6;
      padding: 18px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .finish-title {
      font-size: 2rem;
      font-weight: 700;
      margin: 0;
    }

    .success-circle {
      width: 220px;
      height: 220px;
      border-radius: 50%;
      background-color: #76b800;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
    }

    .btn-back-home {
      background-color: #c7cfd6;
      color: #000000;
      border: none;
      border-radius: 999px;
      padding: 12px 24px;
      font-size: 1.35rem;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 12px;
    }

    .btn-back-home:hover {
      background-color: #b5bec7;
      color: #000000;
    }

    @media (min-width: 768px) {
      .success-circle {
        width: 260px;
        height: 260px;
      }
    }
  </style>
</head>
<body>

  <header class="finish-topbar">
    <h1 class="finish-title">Payment Process</h1>
    <span class="fw-bold fs-4 d-md-none">QRIS</span>
  </header>

  <main class="container py-4 py-md-5 flex-grow-1 d-flex flex-column justify-content-between">
    <div class="row align-items-center justify-content-center g-4 my-auto">
      <div class="col-12 col-md-5 text-center">
        <div class="success-circle">
          <svg width="120" height="120" viewBox="0 0 120 120" aria-hidden="true">
            <polyline
              points="24,64 48,88 96,34"
              fill="none"
              stroke="#ffffff"
              stroke-width="14"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>
        </div>
      </div>

      <div class="col-12 col-md-7 col-lg-6 text-center">
        <h2 class="fw-bold display-5 mb-3">Payment Success!</h2>

        <p class="fs-4 lh-sm mb-4 mx-auto" style="max-width: 460px;">
          Thank you!<br>
          Any issues will be sent through whatsapp or the email of this account.
        </p>

        <p class="fs-4 lh-sm mb-2 mx-auto" style="max-width: 460px;">
          We will contact you on the day of shipping, please make sure to be reachable.
        </p>

        <?php if ($orderCode !== ''): ?>
          <div class="text-muted small mt-3">ID Order: <strong><?= htmlspecialchars($orderCode) ?></strong></div>
        <?php endif; ?>
      </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
      <a href="http://localhost/works/details-online/home.php" class="btn-back-home">
        <span>Back to Home</span>
        <svg width="36" height="36" viewBox="0 0 36 36" aria-hidden="true">
          <polygon points="5,4 33,18 5,32 11,18" fill="#5fb3a2" stroke="#111111" stroke-width="2" stroke-linejoin="round"/>
        </svg>
      </a>
    </div>
  </main>

</body>
</html>