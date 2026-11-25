<?php
session_start();
$activePage = 'profile';
$pageTitle  = 'Profile';

$profile = $_SESSION['profile'] ?? [
    'username' => 'Username123',
    'email'    => '',
    'phone'    => '',
    'dd'       => '',
    'mm'       => '',
    'yyyy'     => '',
    'gender'   => 'Man',
    'avatar'   => '',
];

$addresses = [
    ['label' => 'Alamat 1', 'detail' => 'Jl. dimana entah No 1 RT/RW A/B, Kecamatan, Kota, Provinsi, Negara', 'utama' => true],
    ['label' => 'Alamat 2', 'detail' => 'Jl. Spare road No 1 RT/RW A/B, Kecamatan, Kota, Provinsi, Negara', 'utama' => false],
];

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profile['username'] = trim($_POST['username'] ?: $profile['username']);
    $profile['email']    = trim($_POST['email'] ?? '');
    $profile['phone']    = trim($_POST['phone'] ?? '');
    $profile['dd']       = trim($_POST['dd'] ?? '');
    $profile['mm']       = trim($_POST['mm'] ?? '');
    $profile['yyyy']     = trim($_POST['yyyy'] ?? '');
    $profile['gender']   = $_POST['gender'] ?? 'Man';

    if (!empty($_FILES['avatar']['name']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $ext  = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        $size = $_FILES['avatar']['size'];

        if (in_array($ext, ['png', 'jpg', 'jpeg'], true) && $size <= 5 * 1024 * 1024) {
            if (!is_dir('uploads')) mkdir('uploads', 0775, true);
            $path = 'uploads/avatar_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $path)) {
                $profile['avatar'] = $path;
            }
        } else {
            $msg = 'File harus PNG/JPG dan kurang dari 5 MB.';
        }
    }

    $_SESSION['profile']  = $profile;
    $_SESSION['username'] = $profile['username'];
    if ($msg === '') $msg = 'Perubahan tersimpan.';
}

// Hitung umur sederhana
$age = '-';
if (checkdate((int)$profile['mm'], (int)$profile['dd'], (int)$profile['yyyy']) && strlen($profile['yyyy']) === 4) {
    $dob = new DateTime("{$profile['yyyy']}-{$profile['mm']}-{$profile['dd']}");
    $age = (string) $dob->diff(new DateTime('today'))->y;
}

include 'includes/frame.php';
?>

<style>
  .p-avatar {
    width: 180px; height: 180px; border-radius: 50%;
    background: #c7cfd6; display: flex; align-items: center; justify-content: center;
    overflow: hidden;
  }
  .p-avatar img { width: 100%; height: 100%; object-fit: cover; }
  .p-input { background: #c7cfd6; border: none; border-radius: 12px; height: 42px; }
  .p-input:focus { background: #bcc5cd; box-shadow: none; }
  .p-date { width: 72px; height: 48px; border: 2px solid #6e6e6e; border-radius: 10px; text-align: center; }
  .p-gender {
    min-width: 80px; height: 48px; border: 2px solid #6e6e6e; border-radius: 10px;
    background: #fff; display: inline-flex; align-items: center; justify-content: center; cursor: pointer;
  }
  .btn-check:checked + .p-gender { border-color: #000; border-width: 2.5px; font-weight: 600; }
  .p-addr { border: 2px solid #000; border-radius: 10px; padding: 10px 14px; flex: 1; }
  .p-save {
    width: 110px; height: 88px; border: 2px solid #8a8e91; border-radius: 12px;
    background: #fff; font-size: 1.1rem; line-height: 1.2;
  }
</style>

<main class="container-fluid px-3 px-md-4 py-4">
  <?php if ($msg): ?>
    <div class="alert alert-secondary py-2"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <div class="row gy-4 mb-3">
      <div class="col-md-3 d-flex justify-content-center justify-content-md-start">
        <div class="p-avatar">
          <?php if ($profile['avatar']): ?>
            <img src="<?= htmlspecialchars($profile['avatar']) ?>" alt="Avatar">
          <?php else: ?>
            LOGO
          <?php endif; ?>
        </div>
      </div>

      <div class="col-md-5">
        <div class="mb-3" style="max-width: 320px;">
          <label class="form-label fs-5 mb-1">Username:</label>
          <input type="text" name="username" class="form-control p-input" value="<?= htmlspecialchars($profile['username']) ?>">
        </div>

        <label class="form-label fs-5 mb-1">Profile Picture:</label>
        <div>
          <label class="btn btn-sm btn-light border border-dark me-2" style="cursor:pointer;">
            Browse... <input type="file" name="avatar" accept=".png,.jpg,.jpeg" class="d-none">
          </label>
          <span>PNG, JPG only</span>
        </div>
        <div class="small mt-1">File must be less than 5 MB.</div>
      </div>

      <div class="col-md-4">
        <div class="fs-5 mb-2">Age: <?= htmlspecialchars($age) ?></div>

        <label class="form-label fs-5 mb-1">Birthdate:</label>
        <div class="d-flex gap-2 mb-3">
          <input type="text" name="dd" class="form-control p-date" placeholder="DD" maxlength="2" value="<?= htmlspecialchars($profile['dd']) ?>">
          <input type="text" name="mm" class="form-control p-date" placeholder="MM" maxlength="2" value="<?= htmlspecialchars($profile['mm']) ?>">
          <input type="text" name="yyyy" class="form-control p-date" placeholder="YYYY" maxlength="4" value="<?= htmlspecialchars($profile['yyyy']) ?>">
        </div>

        <label class="form-label fs-5 mb-1">Gender:</label>
        <div class="d-flex gap-2">
          <?php foreach (['Man', 'Woman', 'Other'] as $g): ?>
            <input type="radio" class="btn-check" name="gender" id="g<?= $g ?>" value="<?= $g ?>" <?= $profile['gender'] === $g ? 'checked' : '' ?>>
            <label class="p-gender" for="g<?= $g ?>"><?= $g ?></label>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="row gy-3 mb-4">
      <div class="col-md-5">
        <label class="form-label fs-5 mb-1">Email:</label>
        <input type="email" name="email" class="form-control p-input" value="<?= htmlspecialchars($profile['email']) ?>">
      </div>
      <div class="col-md-5">
        <label class="form-label fs-5 mb-1">Phone number:</label>
        <input type="tel" name="phone" class="form-control p-input" value="<?= htmlspecialchars($profile['phone']) ?>">
      </div>
    </div>

    <h2 class="fs-3 fw-normal mb-3">Alamat - alamat Kamu:</h2>
    <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
      <div class="d-flex flex-column gap-3 flex-grow-1" style="max-width: 880px;">
        <?php foreach ($addresses as $i => $a): ?>
          <div class="d-flex align-items-center gap-3">
            <span class="fs-4"><?= $i + 1 ?></span>
            <div class="p-addr">
              <div class="d-flex justify-content-between align-items-center">
                <strong><?= htmlspecialchars($a['label']) ?></strong>
                <?php if ($a['utama']): ?>
                  <span class="badge text-dark fw-normal" style="background:#c7cfd6;">Alamat Utama</span>
                <?php endif; ?>
              </div>
              <div><?= htmlspecialchars($a['detail']) ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <button type="submit" class="p-save align-self-end">Save<br>Changes</button>
    </div>
  </form>
</main>

<?php include 'includes/foot.php'; ?>