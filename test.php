<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Method tidak diizinkan']);
    exit;
}

// Ambil input (mendukung JSON dari fetch atau form POST biasa)
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);
if (!is_array($data)) {
    $data = $_POST;
}

$email = trim($data['email'] ?? '');
$username = trim($data['username'] ?? '');
$phone = trim($data['phone'] ?? '');
$birthdate = trim($data['birthdate'] ?? '');
$password = $data['password'] ?? '';

// Validasi dasar
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Format email tidak valid']);
    exit;
}

if ($username === '' || $phone === '' || strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Lengkapi semua data (password min. 6 karakter)']);
    exit;
}

// Validasi tanggal lahir format YYYY-MM-DD
$dt = DateTime::createFromFormat('Y-m-d', $birthdate);
if (!$dt || $dt->format('Y-m-d') !== $birthdate) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Format tanggal lahir tidak valid']);
    exit;
}

// Konfigurasi koneksi MySQL
$host = '127.0.0.1';
$db   = 'nd_store';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Cek apakah email atau username sudah terdaftar
    $cek = $pdo->prepare('SELECT id FROM users WHERE email = :email OR username = :username LIMIT 1');
    $cek->execute([
        ':email' => $email,
        ':username' => $username,
    ]);
    if ($cek->fetch()) {
        http_response_code(409);
        echo json_encode(['ok' => false, 'message' => 'Email atau username sudah digunakan']);
        exit;
    }

    // Hash password sebelum disimpan
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke database
    $stmt = $pdo->prepare('
        INSERT INTO users (email, username, phone, birthdate, password_hash)
        VALUES (:email, :username, :phone, :birthdate, :password_hash)
    ');
    $stmt->execute([
        ':email' => $email,
        ':username' => $username,
        ':phone' => $phone,
        ':birthdate' => $birthdate,
        ':password_hash' => $passwordHash,
    ]);

    http_response_code(201);
    echo json_encode(['ok' => true, 'message' => 'Registrasi berhasil']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Gagal menyimpan data ke database']);
}