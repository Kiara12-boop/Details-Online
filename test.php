<?php
session_start();
include 'koneksi.php';
include 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama = trim($_POST["nama"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format email tidak valid.";
    } elseif (strlen($password) < 8) {
        $message = "Password minimal 8 karakter.";
    } else {

        // Periksa apakah email sudah terdaftar
        $stmt = $conn->prepare(
            "SELECT id, is_verified FROM users WHERE email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $existing = $result->fetch_assoc();
        $stmt->close();

        if ($existing && $existing["is_verified"] == 1) {
            $message = "Email sudah terdaftar.";
        } else {

            // Membuat kode OTP 6 digit
            $otp = (string) random_int(100000, 999999);

            $otp_hash = password_hash(
                $otp,
                PASSWORD_DEFAULT
            );

            $password_hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $expiry = date(
                "Y-m-d H:i:s",
                time() + 300
            );

            // Simpan atau perbarui akun
            if ($existing) {

                $stmt = $conn->prepare(
                    "UPDATE users SET
                    nama = ?,
                    password = ?,
                    verification_code = ?,
                    code_expiry = ?
                    WHERE email = ? AND is_verified = 0"
                );

                $stmt->bind_param(
                    "sssss",
                    $nama,
                    $password_hash,
                    $otp_hash,
                    $expiry,
                    $email
                );

            } else {

                $stmt = $conn->prepare(
                    "INSERT INTO users
                    (nama, email, password,
                    verification_code, code_expiry)
                    VALUES (?, ?, ?, ?, ?)"
                );

                $stmt->bind_param(
                    "sssss",
                    $nama,
                    $email,
                    $password_hash,
                    $otp_hash,
                    $expiry
                );
            }

            if ($stmt->execute()) {

                try {

                    $mail = new PHPMailer(true);

                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;

                    $mail->Username = 'emailkamu@gmail.com';
                    $mail->Password = 'APP_PASSWORD_KAMU';

                    $mail->SMTPSecure =
                        PHPMailer::ENCRYPTION_STARTTLS;

                    $mail->Port = 587;

                    $mail->setFrom(
                        'emailkamu@gmail.com',
                        'Website Verification'
                    );

                    $mail->addAddress($email, $nama);

                    $mail->isHTML(true);
                    $mail->Subject = 'Kode Verifikasi Akun';

                    $mail->Body = "
                        <h2>Verifikasi Email</h2>
                        <p>Halo, $nama!</p>
                        <p>Kode verifikasi akun kamu adalah:</p>
                        <h1 style='color:blue;'>$otp</h1>
                        <p>Kode berlaku selama 5 menit.</p>
                        <p>Jangan berikan kode ini kepada siapa pun.</p>
                    ";

                    $mail->AltBody =
                        "Kode verifikasi kamu: $otp";

                    $mail->send();

                    $_SESSION["verify_email"] = $email;

                    header("Location: verify.php");
                    exit();

                } catch (Exception $e) {

                    $message =
                        "Email gagal dikirim. Silakan coba lagi.";

                    // Hapus akun yang belum diverifikasi
                    $del = $conn->prepare(
                        "DELETE FROM users
                        WHERE email = ? AND is_verified = 0"
                    );

                    $del->bind_param("s", $email);
                    $del->execute();
                    $del->close();
                }

            } else {
                $message = "Registrasi gagal.";
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Akun</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Buat Akun Baru</h2>

    <p class="message">
        <?php echo htmlspecialchars($message); ?>
    </p>

    <form method="POST">
        <input type="text"
               name="nama"
               placeholder="Nama lengkap"
               required>

        <input type="email"
               name="email"
               placeholder="Alamat email"
               required>

        <input type="password"
               name="password"
               placeholder="Password minimal 8 karakter"
               required>

        <button type="submit">Daftar Sekarang</button>
    </form>

    <p>Sudah punya akun?
        <a href="login.php">Login</a>
    </p>
</div>

</body>
</html>
