<?php
session_start();
require_once 'order_show.php';

// Ganti dengan nama/path file gambarmu, misal: 'qris.png' atau 'uploads/qris.jpg'
$qrisImg = 'http://localhost/works/details-online/img/the_qr.png';

$orderCode   = trim($_GET['order_id'] ?? '');
$order       = null;
$displayCode = $orderCode !== '' ? $orderCode : 'PREVIEW-001';
$totalHarga  = 0;

try {
    $order = findOrderByCode($orderCode);
    if ($order) {
        $displayCode = $order['order_code'];
        $totalHarga  = (int) $order['total_harga'];
    }
} catch (Throwable $e) {
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_paid'])) {
    if ($order) {
        try {
            markOrderPaid($order['order_code']);
        } catch (Throwable $e) {
        }
        header('Location: payment_finish.php?order_id=' . urlencode($order['order_code']));
        exit;
    }
    header('Location: payment_finish.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Process - QRIS</title>
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

    .pay-topbar {
      background-color: #c7cfd6;
      padding: 18px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
    }

    .pay-title {
      font-size: 2rem;
      font-weight: 700;
      margin: 0;
      line-height: 1.1;
    }

    .pay-method {
      font-size: 1.8rem;
      font-weight: 700;
      line-height: 1.1;
    }

    .qris-box {
      width: 240px;
      height: 240px;
      background-color: #c7cfd6;
      margin: 0 auto;
    }

    .qris-box img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .timer-text {
      font-size: 4.5rem;
      font-weight: 400;
      line-height: 1;
      letter-spacing: 2px;
    }

    .btn-paid {
      background-color: #c7cfd6;
      color: #000000;
      border: none;
      border-radius: 16px;
      padding: 14px 24px;
      font-size: 1.45rem;
      display: inline-flex;
      align-items: center;
      gap: 14px;
      cursor: pointer;
    }

    .btn-paid:hover {
      background-color: #b5bec7;
      color: #000000;
    }

    @media (min-width: 768px) {
      .qris-box {
        width: 290px;
        height: 290px;
      }

      .timer-text {
        font-size: 5.5rem;
      }
    }

    @media (max-width: 575.98px) {
      .pay-topbar {
        padding: 16px 18px;
      }

      .pay-title,
      .pay-method {
        font-size: 1.45rem;
      }

      .timer-text {
        font-size: 3.75rem;
      }

      .btn-paid {
        font-size: 1.25rem;
        padding: 12px 20px;
      }
    }
  </style>
</head>
<body>

  <header class="pay-topbar">
    <h1 class="pay-title">Payment Process</h1>
    <div class="pay-method">QRIS</div>
  </header>

  <main class="container py-4 py-md-5 flex-grow-1 d-flex flex-column justify-content-center">
    <div class="row align-items-center justify-content-center g-4 g-md-5">
      <div class="col-12 col-md-5 text-center">
        <div class="qris-box">
          <?php if ($qrisImg !== ''): ?>
            <img src="<?= htmlspecialchars($qrisImg) ?>" alt="QRIS">
          <?php endif; ?>
        </div>
        <div class="small text-muted mt-2">
          Order: <strong><?= htmlspecialchars($displayCode) ?></strong>
          <?php if ($totalHarga > 0): ?>
            &bull; Total: <strong>Rp. <?= number_format($totalHarga, 0, ',', '.') ?></strong>
          <?php endif; ?>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-5 text-center text-md-start">
        <div class="fw-bold fs-2 mb-2 d-none d-md-block">QRIS</div>
        <p class="fs-3 lh-sm mb-4 mx-auto mx-md-0" style="max-width: 360px;">
          Please do the payment before the timer runs out.
        </p>

        <div id="countdownTimer" class="timer-text mb-4">05:00</div>

        <form method="POST" class="d-flex justify-content-center justify-content-md-end">
          <button type="submit" name="confirm_paid" value="1" class="btn-paid">
            <span>I have paid</span>
            <svg width="34" height="34" viewBox="0 0 36 36" aria-hidden="true">
              <polygon points="4,4 32,18 4,32 10,18" fill="#5fb3a2" stroke="#111111" stroke-width="2" stroke-linejoin="round"/>
            </svg>
          </button>
        </form>
      </div>
    </div>
  </main>

  <script>
    let remainingSeconds = 5 * 60;
    const timerEl = document.getElementById("countdownTimer");

    const interval = setInterval(() => {
      if (remainingSeconds <= 0) {
        clearInterval(interval);
        timerEl.textContent = "00:00";
        return;
      }
      remainingSeconds--;
      const mins = String(Math.floor(remainingSeconds / 60)).padStart(2, "0");
      const secs = String(remainingSeconds % 60).padStart(2, "0");
      timerEl.textContent = `${mins}:${secs}`;
    }, 1000);
  </script>
</body>
</html>