<?php
session_start();
$activePage = 'home';
$pageTitle  = 'Home';

include 'includes/frame.php';
?>

<main class="container-fluid px-3 px-md-4 py-4">
  <!-- Hero / Banner Utama -->
  <section class="p-4 p-md-5 mb-4 rounded-3" style="background-color: #3401015f;;">
    <div class="row align-items-center g-4">
      <div class="col-12 col-md-7">
        <h2 class="fw-bold fs-1 mb-2">Selamat Datang di ND Store</h2>
        <p class="fs-5 mb-4">
          Temukan berbagai pilihan material lantai dan interior berkualitas untuk rumah maupun ruang usaha Anda.
        </p>
        <a href="store.php" class="btn-wire fs-5" style="background-color: #9da4a8;">
          Jelajahi Store
        </a>
      </div>
      <div class="col-12 col-md-5">
        <div class="wire-img-box" style="aspect-ratio: 16 / 10;"></div>
      </div>
    </div>
  </section>

  <!-- Kategori Populer -->
  <section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="fw-bold fs-3 m-0">Kategori Pilihan</h3>
      <a href="store.php" class="text-dark fw-semibold text-decoration-none">Lihat Semua &rarr;</a>
    </div>

    <div class="row g-3">
      <div class="col-6 col-md-3">
        <a href="store.php?kategori=flooring" class="text-decoration-none text-dark d-block p-3 border rounded-3 text-center h-100">
          <div class="wire-img-box mb-2"></div>
          <div class="fw-bold fs-5">Flooring</div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="store.php?kategori=wall-panel" class="text-decoration-none text-dark d-block p-3 border rounded-3 text-center h-100">
          <div class="wire-img-box mb-2"></div>
          <div class="fw-bold fs-5">Wall Panel</div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="store.php?kategori=decking" class="text-decoration-none text-dark d-block p-3 border rounded-3 text-center h-100">
          <div class="wire-img-box mb-2"></div>
          <div class="fw-bold fs-5">Decking</div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="store.php?kategori=aksesoris" class="text-decoration-none text-dark d-block p-3 border rounded-3 text-center h-100">
          <div class="wire-img-box mb-2"></div>
          <div class="fw-bold fs-5">Aksesoris</div>
        </a>
      </div>
    </div>
  </section>

  <!-- Produk Rekomendasi -->
  <section class="pb-4">
    <h3 class="fw-bold fs-3 mb-3">Produk Rekomendasi</h3>
    <div class="row g-4">
      <div class="col-6 col-md-3">
        <div class="wire-img-box"></div>
        <div class="fw-semibold fs-5 mt-2">Nama Produk 1</div>
        <div class="small">Floor tiles, cm x cm</div>
        <div class="fw-bold mt-1">Rp. 8.293.792</div>
      </div>
      <div class="col-6 col-md-3">
        <div class="wire-img-box"></div>
        <div class="fw-semibold fs-5 mt-2">Nama Produk 2</div>
        <div class="small">Floor tiles, cm x cm</div>
        <div class="fw-bold mt-1">Rp. 123.456</div>
      </div>
      <div class="col-6 col-md-3">
        <div class="wire-img-box"></div>
        <div class="fw-semibold fs-5 mt-2">Nama Produk C</div>
        <div class="small">Floor tiles, cm x cm</div>
        <div class="fw-bold mt-1">Rp. 150.000</div>
      </div>
      <div class="col-6 col-md-3">
        <div class="wire-img-box"></div>
        <div class="fw-semibold fs-5 mt-2">Nama Produk 4</div>
        <div class="small">Floor tiles, cm x cm</div>
        <div class="fw-bold mt-1">Rp. 222.333</div>
      </div>
    </div>
  </section>
</main>

<?php include 'includes/foot.php'; ?>