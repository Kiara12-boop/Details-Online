<?php
session_start();
$activePage = 'help';
$pageTitle  = 'Help & Developer Info';

// Data developer (bisa kamu ubah sesuai kebutuhan)
$developer = [
    'nama'      => 'Kiara Junaedy',
    'role'      => 'Web Developer of this web',
    'prodi'    => 'Informatika Angkatan 25',
    'foto'      => "img/dev.png",
    'NIM' => '412025012',
];

include 'includes/frame.php';
?>

<style>
  .dev-card {
    max-width: 780px;
    border: 2px solid #000;
    border-radius: 14px;
    padding: 28px;
    background-color: #ffffff;
  }

  .dev-photo {
    width: 165px;
    height: 200px;
    border: 10%;
    background-color: #c7e6ec;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #222222;
    overflow: hidden;
    flex-shrink: 0;
  }

  .dev-photo img {
    width: 100%;
    height: 110%;
    object-fit: cover;
  }

  .floating-chat-btn {
    position: fixed;
    right: 24px;
    bottom: 24px;
    background-color: #7c8082;
    color: #ffffff;
    border: 4px solid #444444;
    border-radius: 999px;
    padding: 12px 24px;
    display: inline-flex;
    align-items: center;
    gap: 10px;  
  }

  .floating-chat-btn:hover {
    background-color: #454545;
    color: #ffffff;
    transform: translateY(-2px);
  }
</style>

<main class="container-fluid px-3 px-md-4 py-4">
  <h2 class="fw-bold fs-3 mb-4">Tentang Developer</h2>

  <div class="dev-card">
    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4">
      <div class="dev-photo">
        <img src="<?= htmlspecialchars($developer['foto']) ?>" alt="Foto Developer">
      </div>

      <!-- Nama & Detail Developer -->
      <div class="fw-bold flex-grow-1 text-center text-md-start">
        <h3 class="fs-2 mb-1">Developer Card</h3>
        Nama: <?= htmlspecialchars($developer['nama']) ?>
        <br><br>
        Role: <?= htmlspecialchars($developer['role']) ?>
        <br><br>
        NIM: <?= htmlspecialchars($developer['NIM']) ?>
        <br><br>
        Prodi: <?= htmlspecialchars($developer['prodi']) ?>
      </div>
    </div>
  </div>
</main>

<a
  href="https://wa.me/c/6283829829897"
  target="_blank"
  rel="noopener noreferrer"
  class="floating-chat-btn"
>
  <i class="bi bi-chat-dots-fill fs-5"></i>
  <span>Chat Langsung</span>
</a>

<?php include 'includes/foot.php'; ?>