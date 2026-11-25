<?php
session_start();
  require_once 'includes/order_show.php';

$activePage = 'orders';
$pageTitle  = 'Orders';
$username   = $_SESSION['username'] ?? 'Username123';

$orders  = [];
$dbError = null;

try {
    $orders = getOrdersByUsername($username);
} catch (Throwable $e) {
    $dbError = 'Gagal mengambil data orders dari database.';
}

function buildOrderSteps(string $status): array
{
    $normalized = strtolower(trim($status));
    $isUnpaid   = str_contains($normalized, 'menunggu') || str_contains($normalized, 'belum');
    $isShipped  = str_contains($normalized, 'kirim') || str_contains($normalized, 'terima');
    $isReceived = str_contains($normalized, 'terima');

    return [
        ['label' => 'Pembayaran Dikonfirmasi', 'done' => !$isUnpaid],
        ['label' => 'Pesanan Dikemas',         'done' => $isShipped || $isReceived],
        ['label' => 'Sedang Dikirim',          'done' => $isShipped || $isReceived],
        ['label' => 'Sudah Diterima',          'done' => $isReceived],
    ];
}

include 'includes/frame.php';
?>

<style>
  .order-card {
    max-width: 860px;
    border-radius: 12px;
    padding: 14px 16px;
    cursor: pointer;
    transition: background-color 0.15s, border-color 0.15s;
    border: 1.5px solid #e2e7ec;
    background-color: #ffffff;
  }

  .order-card:hover {
    background-color: #f6f8fa;
    border-color: #8a949c;
  }

  .status-pill {
    background-color: #cbd2d8;
    border: 2px solid #6e6e6e;
    border-radius: 10px;
    padding: 4px 16px;
    font-size: 1.15rem;
    color: #000000;
    white-space: nowrap;
  }

  .order-thumb {
    width: 200px;
    max-width: 100%;
    flex-shrink: 0;
  }

  .order-spec td {
    padding: 1px 8px 1px 0;
    font-size: 1rem;
  }

  .order-tracker-box {
    background-color: #f2f5f7;
    border: 1.5px solid #9aa5b1;
    border-radius: 10px;
    padding: 16px 20px;
    margin-top: 14px;
  }

  .check-verified {
    color: #76b800;
    font-size: 2rem;
    line-height: 1;
    flex-shrink: 0;
  }

  .check-pending {
    color: #b8c0c7;
    font-size: 2rem;
    line-height: 1;
    flex-shrink: 0;
  }

  @media (max-width: 575.98px) {
    .order-thumb { width: 130px; }
    .status-pill { font-size: 0.95rem; padding: 4px 10px; }
  }
</style>

<main class="container-fluid px-3 px-md-4 py-4">
  <h2 class="fw-bold fs-2 mb-3">Orders Kamu (<?= htmlspecialchars($username) ?>):</h2>

  <?php if ($dbError): ?>
    <div class="alert alert-warning"><?= htmlspecialchars($dbError) ?></div>
  <?php elseif (!$orders): ?>
    <div class="p-4 border rounded-3 text-muted">
      Belum ada data order di database untuk akun ini.
    </div>
  <?php else: ?>
    <div class="d-flex flex-column gap-4">
      <?php foreach ($orders as $idx => $order): ?>
        <?php
          $status     = (string) ($order['status'] ?? 'Menunggu Pembayaran');
          $isUnpaid   = str_contains(strtolower($status), 'menunggu') || str_contains(strtolower($status), 'belum');
          $paymentUrl = 'qris.php?order_id=' . urlencode($order['order_code']);
          $collapseId = 'orderProgress_' . $idx;
          $steps      = buildOrderSteps($status);
        ?>

        <div
          class="order-card"
          <?php if ($isUnpaid): ?>
            data-payment-url="<?= htmlspecialchars($paymentUrl) ?>"
          <?php else: ?>
            data-bs-toggle="collapse"
            data-bs-target="#<?= $collapseId ?>"
            aria-expanded="false"
            aria-controls="<?= $collapseId ?>"
          <?php endif; ?>
        >
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
              <span class="fs-3 fw-semibold"><?= htmlspecialchars($order['order_code']) ?></span>
              <?php if ($isUnpaid): ?>
                <span class="badge bg-warning text-dark">Klik untuk lanjut pembayaran</span>
              <?php else: ?>
                <small class="text-muted">(klik untuk cek proses)</small>
              <?php endif; ?>
            </div>
            <div class="status-pill">
              Status: &nbsp;<?= htmlspecialchars($status) ?>
            </div>
          </div>

          <div class="d-flex flex-column gap-3">
            <?php foreach ($order['items'] as $item): ?>
              <div class="d-flex align-items-start gap-3 gap-md-4">
                <div class="order-thumb">
                  <div class="wire-img-box"></div>
                </div>
                <div>
                  <div class="fs-3 lh-sm mb-1"><?= htmlspecialchars($item['product_nama']) ?></div>
                  <table class="order-spec">
                    <tr>
                      <td>Kode</td>
                      <td>:</td>
                      <td><?= htmlspecialchars($item['product_kode']) ?></td>
                    </tr>
                    <tr>
                      <td>Jumlah</td>
                      <td>:</td>
                      <td><?= (int) $item['jumlah'] ?></td>
                    </tr>
                    <tr>
                      <td>Harga</td>
                      <td>:</td>
                      <td>Rp. <?= number_format((int) $item['harga'], 0, ',', '.') ?></td>
                    </tr>
                  </table>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="d-flex justify-content-between align-items-center border-top mt-3 pt-2">
            <span class="fw-semibold fs-5">Total Harga</span>
            <span class="fw-bold fs-5">Rp. <?= number_format((int) $order['total_harga'], 0, ',', '.') ?></span>
          </div>

          <?php if (!$isUnpaid): ?>
            <div class="collapse" id="<?= $collapseId ?>">
              <div class="order-tracker-box">
                <div class="fw-bold fs-5 mb-3">Tahap Proses Order (<?= htmlspecialchars($order['order_code']) ?>):</div>
                <div class="d-flex flex-column gap-3">
                  <?php foreach ($steps as $step): ?>
                    <div class="d-flex align-items-center gap-3">
                      <?php if ($step['done']): ?>
                        <i class="bi bi-check-circle-fill check-verified" title="Selesai"></i>
                      <?php else: ?>
                        <i class="bi bi-circle check-pending" title="Menunggu"></i>
                      <?php endif; ?>
                      <div class="<?= $step['done'] ? 'fw-semibold text-dark' : 'text-muted' ?>">
                        <?= htmlspecialchars($step['label']) ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<script>
  document.querySelectorAll(".order-card[data-payment-url]").forEach((card) => {
    card.addEventListener("click", () => {
      const url = card.getAttribute("data-payment-url");
      if (url) {
        window.location.href = url;
      }
    });
  });
</script>

<?php include 'includes/foot.php'; ?>