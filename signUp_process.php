<?php
session_start();
require_once 'includes/db_conn.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Method tidak diizinkan']);
    exit;
}

$rawInput = file_get_contents('php://input');
$data     = json_decode($rawInput, true);
if (!is_array($data)) {
    $data = $_POST;
}

$email     = trim($data['email'] ?? '');
$username  = trim($data['username'] ?? '');
$phone     = trim($data['phone'] ?? '');
$birthdate = trim($data['birthdate'] ?? '');
$password  = $data['password'] ?? '';

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

$dt = DateTime::createFromFormat('Y-m-d', $birthdate);
if (!$dt || $dt->format('Y-m-d') !== $birthdate) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Format tanggal lahir tidak valid']);
    exit;
}

try {
    $pdo = getDbConnection();

    $cek = $pdo->prepare('SELECT id FROM users WHERE email = :email OR username = :username LIMIT 1');
    $cek->execute([
        ':email'    => $email,
        ':username' => $username,
    ]);
    if ($cek->fetch()) {
        http_response_code(409);
        echo json_encode(['ok' => false, 'message' => 'Email atau username sudah digunakan']);
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('
        INSERT INTO users (email, username, phone, birthdate, password_hash)
        VALUES (:email, :username, :phone, :birthdate, :password_hash)
    ');
    $stmt->execute([
        ':email'         => $email,
        ':username'      => $username,
        ':phone'         => $phone,
        ':birthdate'     => $birthdate,
        ':password_hash' => $passwordHash,
    ]);

    $_SESSION['username'] = $username;
    $_SESSION['profile']  = [
        'username' => $username,
        'email'    => $email,
        'phone'    => $phone,
        'dd'       => substr($birthdate, 8, 2),
        'mm'       => substr($birthdate, 5, 2),
        'yyyy'     => substr($birthdate, 0, 4),
        'gender'   => 'Man',
        'avatar'   => '',
    ];

    http_response_code(201);
    echo json_encode(['ok' => true, 'message' => 'Registrasi berhasil', 'redirect' => 'home.php']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Gagal menyimpan data ke database']);
}