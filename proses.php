
<?php

header("Content-Type: application/json");

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "db_pendaftaran"
);

if (!$conn) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Koneksi database gagal."
    ]);
    exit;
}

// Mengambil data form
$nama = trim($_POST['nama'] ?? '');
$password = $_POST['password'] ?? '';
$gender = $_POST['gender'] ?? '';
$hobi = $_POST['hobi'] ?? [];
$prodi = $_POST['prodi'] ?? '';

// Validasi data wajib
if ($nama === '' || $password === '' ||
    $gender === '' || $prodi === '') {

    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Lengkapi semua data pendaftaran."
    ]);
    exit;
}

if (!is_array($hobi) || !is_array($_FILES['files']['name'] ?? null)) {
    // Checkbox boleh kosong, tetapi harus berbentuk array.
    if (!is_array($hobi)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Data hobi tidak valid."
        ]);
        exit;
    }
}

// Password di-hash
$password_hash = password_hash(
    $password,
    PASSWORD_DEFAULT
);

// Menggabungkan data checkbox
$hobi = implode(", ", $hobi);

// Membuat folder upload
$uploadDir = __DIR__ . "/uploads/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Membatasi jenis dan ukuran file
$allowedMime = [
    "application/pdf" => "pdf",
    "image/jpeg" => "jpg",
    "image/png" => "png"
];

$maxSize = 5 * 1024 * 1024;
$namaFile = [];

if (isset($_FILES['files'])) {

    $files = $_FILES['files'];

    $jumlah = count($files['name']);

    if ($jumlah > 5) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Maksimal 5 file."
        ]);
        exit;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);

    for ($i = 0; $i < $jumlah; $i++) {

        if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) {
            continue;
        }

        if ($files['error'][$i] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Terjadi kesalahan upload."
            ]);
            exit;
        }

        if ($files['size'][$i] > $maxSize) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Ukuran file maksimal 5 MB."
            ]);
            exit;
        }

        $mime = finfo_file(
            $finfo,
            $files['tmp_name'][$i]
        );

        if (!isset($allowedMime[$mime])) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Format file tidak diizinkan."
            ]);
            exit;
        }

        // Membuat nama file acak
        $ekstensi = $allowedMime[$mime];

        $fileBaru = bin2hex(random_bytes(16))
                    . "." . $ekstensi;

        $tujuan = $uploadDir . $fileBaru;

        if (!move_uploaded_file(
            $files['tmp_name'][$i],
            $tujuan
        )) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Gagal menyimpan file."
            ]);
            exit;
        }

        $namaFile[] = $fileBaru;
    }

    finfo_close($finfo);
}

// Simpan nama file dalam format JSON
$fileUpload = json_encode($namaFile);

// Menyimpan data ke database
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO mahasiswa
    (nama, password, gender, hobi, prodi, file_upload)
    VALUES (?, ?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "ssssss",
    $nama,
    $password_hash,
    $gender,
    $hobi,
    $prodi,
    $fileUpload
);

if (mysqli_stmt_execute($stmt)) {

    echo json_encode([
        "status" => "success",
        "message" => "Pendaftaran dan upload file berhasil!"
    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "status" => "error",
        "message" => "Gagal menyimpan data ke database."
    ]);

}

mysqli_stmt_close($stmt);
mysqli_close($conn);

?>
