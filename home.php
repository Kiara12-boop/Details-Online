<?php
session_start();
$activePage = 'home';
$pageTitle  = 'Home';

include 'includes/frame.php';
?>

<main class="container-fluid px-3 px-md-4 py-4">
  <section class="p-4 p-md-5 mb-4 rounded-3" style="background-color: #550b055e;">
    <div class="row align-items-center g-4">
      <div class="col-12 col-md-7">
        <h2 class="fw-bold fs-1">Selamat Datang di Baguette Store</h2>
        <p class="fs-5">
          Temukan berbagai pilihan material lantai dan interior berkualitas untuk rumah maupun ruang usaha Anda.
        </p>
        <a href="store.php" class="btn-wire fs-5" style="background-color: #82000082;">
          Jelajahi Store
        </a>
      </div>
      <div class="col-12 col-md-5">
        <div class="image-wrapper">
          <img src="http://localhost/works/details-online/img/stuff/KK2.jpg" alt="picture">
        </div>
      </div>
    </div>
  </section>

  <section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="fw-bold fs-3 m-0">Kategori Pilihan</h3>
      <a href="store.php" class="text-dark fw-semibold">Lihat Semua</a>
    </div>

    <div class="row g-3">
      <div class="col-6 col-md-3">
        <a href="store.php?kategori=flooring" class="  text-dark d-block p-3 border rounded-3 text-center h-100">
          <div class="image-wrapper">
          <img src="http://localhost/works/details-online/img/stuff/KK1.jpg" alt="picture">
          </div>
          <div class="fw-bold fs-5">Flooring</div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="store.php?kategori=wall-panel" class="text-dark d-block p-3 border rounded-3 text-center h-100">
          <div class="image-wrapper">
          <img src="http://localhost/works/details-online/img/stuff/wall.jpg" alt="picture">
          </div>
          <div class="fw-bold fs-5">Wall Panel</div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="store.php?kategori=aksesoris" class="  text-dark d-block p-3 border rounded-3 text-center h-100">
          <div class="image-wrapper">
          <img src="http://localhost/works/details-online/img/stuff/siku.jpg" alt="picture">
          </div>
          <div class="fw-bold fs-5">Aksesoris</div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="store.php?kategori=Lainnya" class="  text-dark d-block p-3 border rounded-3 text-center h-100">
          <div class="image-wrapper">
          <img src="http://localhost/works/details-online/img/stuff/bracket.jpeg" alt="picture">
          </div>
          <div class="fw-bold fs-5">Lainnya</div>
        </a>
      </div>
    </div>
  </section>

  <section class="pb-4">
    <h3 class="fw-bold fs-3 mb-3">Produk Rekomendasi</h3>
    <div class="row g-4">
      <div class="col-6 col-md-3">
        <a href="product.php?kode=test1" class="text-dark d-block">
          <div class="image-wrapper">
            <img src="http://localhost/works/details-online/img/stuff/bracket.jpeg">
          </div>
          <div class="fw-semibold fs-5 mt-2">placer 1</div>
          <div class="small">misc, 5 x 10 cm</div>
          <div class="fw-bold mt-1">Rp. 100.000</div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="product.php?kode=weld" class="text-dark d-block">
          <div class="image-wrapper">
            <img src="http://localhost/works/details-online/img/stuff/weld.jpeg">
          </div>
          <div class="fw-semibold fs-5 mt-2">welding rod</div>
          <div class="small">misc, 100m</div>
          <div class="fw-bold mt-1">Rp. 1.500.000</div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="product.php?kode=GlueKrisGlue" class="text-dark d-block">
          <div class="image-wrapper">
            <img src="http://localhost/works/details-online/img/stuff/images.jpeg">
          </div>
          <div class="fw-semibold fs-5 mt-2">Glue spotted</div>
          <div class="small">misc, 10kg</div>
          <div class="fw-bold mt-1">Rp. 750.000</div>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="product.php?kode=test1" class="text-dark d-block">
          <div class="image-wrapper">
            <img src="http://localhost/works/details-online/img/stuff/Yajo3.jpg">
          </div>
          <div class="fw-semibold fs-5 mt-2">wood flor</div>
          <div class="small">misc, 20 x 120 x 0.3 cm x 20pcs</div>
          <div class="fw-bold mt-1">Rp. 500.000</div>
        </a>
      </div>
    </div>
  </section>
</main>

<?php include 'includes/foot.php'; ?>