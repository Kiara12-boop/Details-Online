<?php
session_start();
$activePage = 'orders';
$pageTitle  = 'Orders';

// Data contoh pembelian user (bisa diganti query database MySQL/PDO nantinya)
$orders = [
    [
        'order_id'    => 'Order ID 1',
        'status'      => 'Sedang dikirim',
        'total_harga' => 396912,
        'items'       => [
            [
                'nama'   => 'Produk 2',
                'kode'   => 'ABC123',
                'jumlah' => 2,
                'harga'  => 123456,
            ],
            [
                'nama'   => 'Produk C',
                'kode'   => 'Test135',
                'jumlah' => 1,
                'harga'  => 150000,
            ],
        ],
        'steps' => [
            ['label' => 'Pembayaran Dikonfirmasi', 'waktu' => '24 Nov 2026, 09:15', 'done' => true],
            ['label' => 'Pesanan Dikemas',         'waktu' => '24 Nov 2026, 14:30', 'done' => true],
            ['label' => 'Sedang Dikirim',          'waktu' => '25 Nov 2026, 08:00', 'done' => true],
            ['label' => 'Sudah Diterima',          'waktu' => 'Estimasi tiba hari ini', 'done' => false],
        ],
    ],
    [
        'order_id'    => 'Order ID 01',
        'status'      => 'Sudah Diterima',
        'total_harga' => 469245,
        'items'       => [
            [
                'nama'   => 'Produk 1',
                'kode'   => 'ABC123',
                'jumlah' => 2,
                'harga'  => 123456,
            ],
            [
                'nama'   => 'Produk D',
                'kode'   => 'Test135',
                'jumlah' => 1,
                'harga'  => 222333,
            ],
        ],
        'steps' => [
            ['label' => 'Pembayaran Dikonfirmasi', 'waktu' => '18 Nov 2026, 10:05', 'done' => true],
            ['label' => 'Pesanan Dikemas',         'waktu' => '18 Nov 2026, 16:20', 'done' => true],
            ['label' => 'Sedang Dikirim',          'waktu' => '19 Nov 2026, 09:40', 'done' => true],
            ['label' => 'Sudah Diterima',          'waktu' => '20 Nov 2026, 15:12', 'done' => true],
        ],
    ],
];

include 'includes/frame.php';
?>

<style>
  .order-card {
    max-width: 860px;
    border-radius: 12px;
    padding: 14px 16px;
    cursor: pointer;
    transition: background-color 0.15s, border-color 0.15s;
    border: 1.5px solid transparent;
  }

  .order-card:hover {
    background-color: #f6f8fa;
    border-color: #c7cfd6;
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
    width: 220px;
    max-width: 100%;
    flex-shrink: 0;
  }

  .order-spec td {
    padding: 1px 8px 1px 0;
    font-size: 1rem;
  }

  /* Panel tahapan proses order saat diklik */
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
    .order-thumb {
      width: 140px;
    }
    .status-pill {
      font-size: 0.95rem;
      padding: 4px 10px;
    }
  }
</style>

<main class="container-fluid px-3 px-md-4 py-4">
  <h2 class="fw-bold fs-2 mb-3">List Nanti Dulu:</h2>

  <div class="d-flex flex-column gap-4">
    <?php foreach ($orders as $idx => $order): ?>
      <?php $collapseId = 'orderProgress_' . $idx; ?>

      <div
        class="order-card"
        data-bs-toggle="collapse"
        data-bs-target="#<?= $collapseId ?>"
        aria-expanded="false"
        aria-controls="<?= $collapseId ?>"
      >
        <!-- Header Order ID & Status -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
          <div class="d-flex align-items-center gap-2">
            <span class="fs-3"><?= htmlspecialchars($order['order_id']) ?></span>
            <small class="text-muted">(klik untuk cek proses)</small>
          </div>
          <div class="status-pill">
            Status: &nbsp;<?= htmlspecialchars($order['status']) ?>
          </div>
        </div>

        <!-- Daftar Produk dalam Order -->
        <div class="d-flex flex-column gap-3">
          <?php foreach ($order['items'] as $item): ?>
            <div class="d-flex align-items-start gap-3 gap-md-4">
              <div class="order-thumb">
                <div class="wire-img-box"></div>
              </div>

              <div>
                <div class="fs-3 lh-sm mb-1"><?= htmlspecialchars($item['nama']) ?></div>
                <table class="order-spec">
                  <tr>
                    <td>Kode</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($item['kode']) ?></td>
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

        <!-- Total Harga -->
        <div class="d-flex justify-content-between align-items-center border-top mt-3 pt-2">
          <span class="fw-semibold fs-5">Total Harga</span>
          <span class="fw-bold fs-5">Rp. <?= number_format((int) $order['total_harga'], 0, ',', '.') ?></span>
        </div>

        <!-- Tahap Proses Order (Muncul saat card diklik) -->
        <div class="collapse" id="<?= $collapseId ?>">
          <div class="order-tracker-box">
            <div class="fw-bold fs-5 mb-3">Tahap Proses Order (<?= htmlspecialchars($order['order_id']) ?>):</div>

            <div class="d-flex flex-column gap-3">
              <?php foreach ($order['steps'] as $step): ?>
                <div class="d-flex align-items-center gap-3">
                  <?php if ($step['done']): ?>
                    <i class="bi bi-check-circle-fill check-verified" title="Selesai"></i>
                  <?php else: ?>
                    <i class="bi bi-circle check-pending" title="Menunggu"></i>
                  <?php endif; ?>

                  <div>
                    <div class="<?= $step['done'] ? 'fw-semibold text-dark' : 'text-muted' ?>">
                      <?= htmlspecialchars($step['label']) ?>
                    </div>
                    <div class="small text-muted"><?= htmlspecialchars($step['waktu']) ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<?php include 'includes/foot.php'; ?>