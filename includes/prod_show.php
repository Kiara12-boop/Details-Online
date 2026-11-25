<?php
require_once __DIR__ . '/db_conn.php';

function findProductByCode(string $kode): ?array
{
    if ($kode === '') {
        return null;
    }

    $stmt = getDbConnection()->prepare(
        'SELECT * FROM products WHERE kode = :kode LIMIT 1'
    );
    $stmt->execute([':kode' => $kode]);
    $product = $stmt->fetch();

    if (!$product) {
        return null;
    }

    // Ubah "PVC, Rocks, Wood" menjadi array ['PVC', 'Rocks', 'Wood']
    $materials = array_filter(array_map('trim', explode(',', (string) ($product['bahan'] ?? ''))));
    $product['materials_list'] = array_values($materials);

    // Pisahkan deskripsi per baris kosong/enter menjadi paragraf
    $paragraphs = preg_split('/\r\n|\r|\n/', trim((string) ($product['deskripsi'] ?? '')));
    $product['paragraphs'] = array_values(array_filter($paragraphs, fn($p) => trim($p) !== ''));

    return $product;
}