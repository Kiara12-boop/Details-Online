<?php
session_start();
require_once 'includes/prod_show.php';

$kode = trim($_GET['kode'] ?? 'test1');
$product = null;
$dbError = null;

try {
    $product = findProductByCode($kode);
} catch (Throwable $e) {
    $dbError = 'Gagal mengambil data produk dari database.';
}

$activePage = 'store';
$pageTitle  = $product
    ? 'Store / ' . ucfirst((string) $product['category_slug']) . ' / ' . $product['kode']
    : 'Produk Tidak Ditemukan';

include 'includes/frame.php';
?>

<style>
  .gallery-strip {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    padding-bottom: 6px;
    position: relative;
  }

  .gallery-strip .wire-img-box {
    flex: 0 0 calc(33.333% - 11px);
    min-width: 220px;
  }

  .gallery-next-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background-color: #ffffff;
    border: 2px solid #222222;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: #000000;
    cursor: pointer;
    z-index: 5;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
  }

  .spec-table td {
    padding: 3px 10px 3px 0;
    vertical-align: top;
    font-size: 1rem;
  }

  .spec-label { white-space: nowrap; }
  .spec-colon { width: 14px; text-align: center; }

  .btn-action-pill {
    width: 170px;
    background-color: #c7cfd6;
    color: #000000;
    border: none;
    border-radius: 999px;
    padding: 10px 20px;
    font-size: 1.15rem;
    font-weight: 500;
  }

  .btn-action-pill:hover {
    background-color: #b4bec7;
    color: #000000;
  }

  .consult-pill {
    background-color: #d7dee4;
    border-radius: 999px;
    padding: 10px 34px;
    font-size: 1.35rem;
    font-weight: 500;
    color: #000000;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .consult-logo-circle {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background-color: #c7cfd6;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    color: #222222;
    flex-shrink: 0;
  }

  @media (max-width: 767.98px) {
    .btn-action-pill { width: 145px; font-size: 1rem; }
  }
</style>

<main class="container-fluid px-3 px-md-4 py-4">
  <?php if (!$product): ?>
    <div class="py-5 text-center">
      <h2 class="fw-bold fs-3 mb-2">Produk Tidak Ditemukan</h2>
      <p class="text-muted mb-4">
        <?= htmlspecialchars($dbError ?? "Produk dengan kode '$kode' belum tersedia di database.") ?>
      </p>
      <a href="store.php" class="btn-wire">Kembali ke Store</a>
    </div>
  <?php else: ?>
    <h2 class="fw-bold fs-2 mb-3"><?= htmlspecialchars($product['nama']) ?></h2>

    <div class="position-relative mb-3">
      <div id="productGallery" class="gallery-strip">
        <div class="wire-img-box"></div>
        <div class="wire-img-box"></div>
        <div class="wire-img-box"></div>
        <div class="wire-img-box"></div>
      </div>
      <button type="button" id="nextSlideBtn" class="gallery-next-btn" aria-label="Geser gambar">
        <i class="bi bi-arrow-right"></i>
      </button>
    </div>

    <?php if (!empty($product['subtitle'])): ?>
      <div class="fs-5 mb-3"><?= htmlspecialchars($product['subtitle']) ?></div>
    <?php endif; ?>

    <div class="row gy-3 align-items-start mb-4">
      <div class="col-12 col-md-5 col-lg-4">
        <h3 class="fs-3 fw-normal mb-2">Details:</h3>
        <table class="spec-table">
          <tr>
            <td class="spec-label">Panjang x Lebar:</td>
            <td class="spec-colon">:</td>
            <td><?= htmlspecialchars((string) ($product['panjang_lebar'] ?? 'cm x cm')) ?></td>
          </tr>
          <tr>
            <td class="spec-label">Tinggi</td>
            <td class="spec-colon">:</td>
            <td><?= htmlspecialchars((string) ($product['tinggi'] ?? 'cm')) ?></td>
          </tr>
          <tr>
            <td class="spec-label">Berat</td>
            <td class="spec-colon">:</td>
            <td><?= htmlspecialchars((string) ($product['berat'] ?? 'kg')) ?></td>
          </tr>
          <tr>
            <td class="spec-label">Tebal</td>
            <td class="spec-colon">:</td>
            <td><?= htmlspecialchars((string) ($product['tebal'] ?? 'mm')) ?></td>
          </tr>
          <tr>
            <td class="spec-label">Attr</td>
            <td class="spec-colon">:</td>
            <td><?= htmlspecialchars((string) ($product['attr_info'] ?? '')) ?></td>
          </tr>
        </table>
      </div>

      <div class="col-12 col-md-3 col-lg-3 pt-md-4">
        <table class="spec-table">
          <tr>
            <td class="spec-label">Kode</td>
            <td class="spec-colon">:</td>
            <td><?= htmlspecialchars($product['kode']) ?></td>
          </tr>
          <tr>
            <td class="spec-label">Bahan</td>
            <td class="spec-colon">:</td>
            <td>
              <ul class="list-unstyled mb-0">
                <?php foreach ($product['materials_list'] as $mat): ?>
                  <li>&bull; <?= htmlspecialchars($mat) ?></li>
                <?php endforeach; ?>
              </ul>
            </td>
          </tr>
        </table>
      </div>

      <div class="col-12 col-md-4 col-lg-5 d-flex flex-column align-items-md-end">
        <div class="fs-3 mb-3">
          Harga: <span class="ms-2">Rp. <?= number_format((int) $product['harga'], 0, ',', '.') ?></span>
        </div>
        <div class="d-flex flex-column align-items-center gap-2" style="width: 170px;">
          <button type="button" id="buyNowBtn" class="btn btn-action-pill">Buy now</button>
          <span class="text-muted small">atau</span>
          <button type="button" id="saveLaterBtn" class="btn btn-action-pill">Nanti dulu</button>
        </div>
      </div>
    </div>

    <section class="mb-5">
      <h3 class="fs-3 fw-normal mb-2">Deskripsi:</h3>
      <?php foreach ($product['paragraphs'] as $index => $para): ?>
        <p class="<?= $index === count($product['paragraphs']) - 1 ? 'mb-0' : 'mb-3' ?>" style="line-height: 1.5;">
          <?= htmlspecialchars($para) ?>
        </p>
      <?php endforeach; ?>
    </section>

    <section class="text-center pb-3">
      <h3 class="fw-bold fs-2 mb-2">Masih ada Pertanyaan?</h3>
      <div class="fs-4 mb-3">Bisa langsung konsultasi ke nomor ini :</div>
      <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
        <?php
          $consultPhone = (string) ($product['consult_phone'] ?? '0123-4567-8901');
          $phoneDigits  = preg_replace('/\D+/', '', $consultPhone);
        ?>
        <a href="tel:<?= htmlspecialchars($phoneDigits) ?>" class="consult-pill">
          <?= htmlspecialchars($consultPhone) ?>
        </a>
        <span class="consult-logo-circle">logo</span>
      </div>
    </section>
  <?php endif; ?>
</main>

<script>
  const gallery = document.getElementById("productGallery");
  const nextSlideBtn = document.getElementById("nextSlideBtn");

  if (gallery && nextSlideBtn) {
    nextSlideBtn.addEventListener("click", () => {
      gallery.scrollBy({ left: 260, behavior: "smooth" });
    });
  }

  document.getElementById("buyNowBtn")?.addEventListener("click", () => {
    window.location.href = "includes/checkout.php?kode=<?= urlencode($product['kode'] ?? 'test1') ?>";
  });

  document.getElementById("saveLaterBtn")?.addEventListener("click", () => {
    window.location.href = "orders.php";
  });
</script>

<?php include 'includes/foot.php'; ?>