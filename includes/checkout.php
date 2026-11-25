<?php
session_start();
require_once 'prod_show.php';
require_once 'order_show.php';

$kode     = trim($_GET['kode'] ?? 'test1');
$jumlah   = max(1, (int) ($_GET['qty'] ?? 1));
$username = $_SESSION['username'] ?? 'Username123';

try {
    $product = findProductByCode($kode);

    if (!$product) {
        $product = [
            'kode'  => $kode !== '' ? $kode : 'test1',
            'nama'  => 'JB Floor Vinyls',
            'harga' => 0,
        ];
    }

    $orderCode = createPendingOrder($username, $product, $jumlah);
    header('Location: payin.php?order_id=' . urlencode($orderCode));
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Gagal membuat order: ' . htmlspecialchars($e->getMessage());
}