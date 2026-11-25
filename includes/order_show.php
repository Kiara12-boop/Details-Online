<?php
require_once __DIR__ . '/db_conn.php';

function createPendingOrder(string $username, array $product, int $jumlah = 1): string
{
    $pdo       = getDbConnection();
    $orderCode = 'ORD-' . date('ymdHis') . '-' . random_int(10, 99);
    $harga     = (int) ($product['harga'] ?? 0);
    $jumlah    = max(1, $jumlah);
    $total     = $harga * $jumlah;

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('
            INSERT INTO orders (order_code, username, status, payment_method, total_harga)
            VALUES (:order_code, :username, :status, :payment_method, :total_harga)
        ');
        $stmt->execute([
            ':order_code'     => $orderCode,
            ':username'       => $username,
            ':status'         => 'Menunggu Pembayaran',
            ':payment_method' => 'QRIS',
            ':total_harga'    => $total,
        ]);

        $orderId = (int) $pdo->lastInsertId();

        $itemStmt = $pdo->prepare('
            INSERT INTO order_items (order_id, product_kode, product_nama, jumlah, harga)
            VALUES (:order_id, :product_kode, :product_nama, :jumlah, :harga)
        ');
        $itemStmt->execute([
            ':order_id'     => $orderId,
            ':product_kode' => (string) ($product['kode'] ?? 'ABC123'),
            ':product_nama' => (string) ($product['nama'] ?? 'Nama Produk 1'),
            ':jumlah'       => $jumlah,
            ':harga'        => $harga,
        ]);

        $pdo->commit();
        return $orderCode;
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function getOrdersByUsername(string $username): array
{
    $pdo  = getDbConnection();
    $stmt = $pdo->prepare('
        SELECT * FROM orders
        WHERE username = :username
        ORDER BY created_at DESC, id DESC
    ');
    $stmt->execute([':username' => $username]);
    $orders = $stmt->fetchAll();

    if (!$orders) {
        return [];
    }

    $orderIds     = array_column($orders, 'id');
    $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
    $itemStmt     = $pdo->prepare("SELECT * FROM order_items WHERE order_id IN ($placeholders)");
    $itemStmt->execute($orderIds);
    $allItems = $itemStmt->fetchAll();

    $itemsByOrder = [];
    foreach ($allItems as $item) {
        $itemsByOrder[(int) $item['order_id']][] = $item;
    }

    foreach ($orders as &$order) {
        $order['items'] = $itemsByOrder[(int) $order['id']] ?? [];
    }
    unset($order);

    return $orders;
}

function findOrderByCode(string $orderCode): ?array
{
    if ($orderCode === '') {
        return null;
    }

    $pdo  = getDbConnection();
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE order_code = :order_code LIMIT 1');
    $stmt->execute([':order_code' => $orderCode]);
    $order = $stmt->fetch();

    if (!$order) {
        return null;
    }

    $itemStmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = :order_id');
    $itemStmt->execute([':order_id' => $order['id']]);
    $order['items'] = $itemStmt->fetchAll();

    return $order;
}

function markOrderPaid(string $orderCode): bool
{
    $pdo  = getDbConnection();
    $stmt = $pdo->prepare('
        UPDATE orders
        SET status = :status, paid_at = NOW()
        WHERE order_code = :order_code
    ');
    return $stmt->execute([
        ':status'     => 'Sedang dikirim',
        ':order_code' => $orderCode,
    ]);
}