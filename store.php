<?php
session_start();
$activePage = 'store';
$pageTitle  = 'Store';

include 'includes/frame.php';
$products = [
    [
        'kode'   => 'ABC123',
        'nama'   => 'Nama Produk 1',
        'type'   => 'Vinyl Plank',
        'ukuran' => 'Floor tiles, cm x cm',
        'motif'  => 10,
        'stock'  => 1234,
        'harga'  => 8293792,
    ],
    [
        'kode'   => 'ABC124',
        'nama'   => 'Nama Produk 2',
        'type'   => 'SPC Flooring',
        'ukuran' => 'Floor tiles, cm x cm',
        'motif'  => 10,
        'stock'  => 1234,
        'harga'  => 123456,
    ],
    [
        'kode'   => 'ABC125',
        'nama'   => 'Nama Produk 3',
        'type'   => 'Parquet Kayu',
        'ukuran' => 'Floor tiles, cm x cm',
        'motif'  => 10,
        'stock'  => 1234,
        'harga'  => 150000,
    ],
    [
        'kode'   => 'ABC126',
        'nama'   => 'Nama Produk 4',
        'type'   => 'WPC Decking',
        'ukuran' => 'Floor tiles, cm x cm',
        'motif'  => 10,
        'stock'  => 1234,
        'harga'  => 222333,
    ],
    [
        'kode'   => 'ABC127',
        'nama'   => 'Nama Produk 5',
        'type'   => 'Vinyl Plank',
        'ukuran' => 'Floor tiles, cm x cm',
        'motif'  => 8,
        'stock'  => 540,
        'harga'  => 195000,
    ],
    [
        'kode'   => 'ABC128',
        'nama'   => 'Nama Produk 6',
        'type'   => 'SPC Flooring',
        'ukuran' => 'Floor tiles, cm x cm',
        'motif'  => 12,
        'stock'  => 890,
        'harga'  => 310000,
    ],
];

$categories = [
    'Vinyl Plank',
    'SPC Flooring',
    'Parquet Kayu',
    'WPC Decking',
    'Laminate',
    'Floor Tile',
    'Carpet Tile',
    'Rubber Mat',
    'Epoxy Floor',
    'Aksesoris',
];

?>

<style>
  .browse-toolbar {
    padding: 18px 28px 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .filter-btn {
    background: transparent;
    border: none;
    font-size: 1.85rem;
    color: #7c8082;
    line-height: 1;
    cursor: pointer;
    padding: 4px;
  }

  .filter-btn:hover {
    color: #000000;
  }

  .product-title {
    font-size: 1.35rem;
    font-weight: 600;
    margin-top: 10px;
    margin-bottom: 4px;
  }

  .product-meta {
    font-size: 1rem;
    line-height: 1.4;
    color: #111111;
  }

  /* Panel Filter & Sort mengambang di kanan atas katalog */
  .filter-popover {
    display: none;
    position: absolute;
    top: 122px;
    right: 24px;
    width: 340px;
    max-width: calc(100% - 32px);
    background-color: #cbd2d8;
    border: 2px solid #7c8082;
    padding: 18px 20px;
    z-index: 20;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.14);
  }

  .filter-popover.show {
    display: block;
  }

  .filter-section-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 10px;
  }

  .custom-square-check,
  .custom-radio-lg {
    width: 20px;
    height: 20px;
    accent-color: #2b6cb0;
    cursor: pointer;
  }

  @media (max-width: 767.98px) {
    .browse-toolbar {
      padding-left: 14px;
      padding-right: 14px;
    }
  }
</style>

<!-- Judul Kategori & Tombol Filter -->
<div class="browse-toolbar">
  <h2 class="fw-bold fs-3 m-0">Flooring - All Types</h2>
  <button type="button" id="toggleFilterBtn" class="filter-btn" aria-label="Filter dan Sort">
    <i class="bi bi-funnel-fill"></i>
  </button>
</div>

<!-- Panel Filter & Sort -->
<div id="filterPanel" class="filter-popover">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <div class="filter-section-title m-0">Filter:</div>
    <button type="button" id="resetFilterBtn" class="btn btn-sm btn-outline-dark py-0 px-2">Reset</button>
  </div>

  <div class="row g-2 mb-3">
    <?php foreach ($categories as $cat): ?>
      <div class="col-6">
        <label class="d-flex align-items-center gap-2 small">
          <input
            type="checkbox"
            class="custom-square-check category-checkbox"
            value="<?= htmlspecialchars($cat) ?>"
          >
          <span><?= htmlspecialchars($cat) ?></span>
        </label>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="filter-section-title">Sort:</div>
  <div class="d-flex flex-column gap-2">
    <label class="d-flex align-items-center gap-2">
      <input type="radio" name="sortOption" value="az" class="custom-radio-lg" checked> A - Z
    </label>
    <label class="d-flex align-items-center gap-2">
      <input type="radio" name="sortOption" value="za" class="custom-radio-lg"> Z - A
    </label>
    <label class="d-flex align-items-center gap-2">
      <input type="radio" name="sortOption" value="price-asc" class="custom-radio-lg"> Low - High Priced
    </label>
    <label class="d-flex align-items-center gap-2">
      <input type="radio" name="sortOption" value="price-desc" class="custom-radio-lg"> High - Low Priced
    </label>
  </div>
</div>

<!-- Grid Produk (2 Kolom) -->
<main class="container-fluid px-3 px-md-4 pb-5">
  <div id="productGrid" class="row g-4">
    <?php foreach ($products as $item): ?>
      <div
        class="col-6 product-item"
        data-type="<?= htmlspecialchars($item['type']) ?>"
        data-name="<?= htmlspecialchars($item['nama']) ?>"
        data-price="<?= (int) $item['harga'] ?>"
      >
        <a href="product.php?kode=<?= urlencode($item['kode']) ?>" class="text-decoration-none text-dark d-block">
          <div class="wire-img-box"></div>
          <div class="product-title"><?= htmlspecialchars($item['nama']) ?></div>
          <span class="product-type-badge"><?= htmlspecialchars($item['type']) ?></span>
          <div class="product-meta">
            <div><?= htmlspecialchars($item['ukuran']) ?></div>
            <div>Motif tersedia: <?= (int) $item['motif'] ?></div>
            <div>In Stock: <?= (int) $item['stock'] ?></div>
            <div class="fw-semibold mt-1">Rp. <?= number_format((int) $item['harga'], 0, ',', '.') ?></div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>

  <div id="emptyState" class="text-center py-5 d-none">
    <p class="fs-5 text-muted mb-2">Tidak ada produk yang cocok dengan tipe barang yang dipilih.</p>
    <button type="button" id="emptyResetBtn" class="btn-wire">Tampilkan Semua Produk</button>
  </div>
</main>

<script>
  const toggleFilterBtn = document.getElementById("toggleFilterBtn");
  const filterPanel = document.getElementById("filterPanel");
  const categoryCheckboxes = document.querySelectorAll(".category-checkbox");
  const sortRadios = document.querySelectorAll('input[name="sortOption"]');
  const productGrid = document.getElementById("productGrid");
  const productItems = Array.from(document.querySelectorAll(".product-item"));
  const emptyState = document.getElementById("emptyState");
  const resetFilterBtn = document.getElementById("resetFilterBtn");
  const emptyResetBtn = document.getElementById("emptyResetBtn");

  toggleFilterBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    filterPanel.classList.toggle("show");
  });

  document.addEventListener("click", (e) => {
    if (!filterPanel.contains(e.target) && !toggleFilterBtn.contains(e.target)) {
      filterPanel.classList.remove("show");
    }
  });

  function applyFilterAndSort() {
    const selectedTypes = Array.from(categoryCheckboxes)
      .filter((cb) => cb.checked)
      .map((cb) => cb.value);

    const activeSort = document.querySelector('input[name="sortOption"]:checked')?.value || "az";

    // Urutkan item produk
    const sortedItems = [...productItems].sort((a, b) => {
      const nameA = a.dataset.name.toLowerCase();
      const nameB = b.dataset.name.toLowerCase();
      const priceA = Number(a.dataset.price);
      const priceB = Number(b.dataset.price);

      if (activeSort === "az") return nameA.localeCompare(nameB);
      if (activeSort === "za") return nameB.localeCompare(nameA);
      if (activeSort === "price-asc") return priceA - priceB;
      if (activeSort === "price-desc") return priceB - priceA;
      return 0;
    });

    let visibleCount = 0;

    sortedItems.forEach((item) => {
      productGrid.appendChild(item);
      const itemType = item.dataset.type;
      const matchesType = selectedTypes.length === 0 || selectedTypes.includes(itemType);

      if (matchesType) {
        item.classList.remove("d-none");
        visibleCount++;
      } else {
        item.classList.add("d-none");
      }
    });

    emptyState.classList.toggle("d-none", visibleCount > 0);
  }

  function resetFilters() {
    categoryCheckboxes.forEach((cb) => {
      cb.checked = false;
    });
    applyFilterAndSort();
  }

  categoryCheckboxes.forEach((cb) => cb.addEventListener("change", applyFilterAndSort));
  sortRadios.forEach((radio) => radio.addEventListener("change", applyFilterAndSort));
  resetFilterBtn.addEventListener("click", resetFilters);
  emptyResetBtn.addEventListener("click", resetFilters);
</script>

<?php include 'includes/foot.php'; ?>