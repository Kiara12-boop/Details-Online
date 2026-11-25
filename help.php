<?php
session_start();
$activePage = 'help';$pageTitle  = 'Help & Developer Info';

// Data developer (bisa kamu ubah sesuai kebutuhan)
$developer = [
    'nama'      => 'Kiara Junaedy',
    'role'      => 'Web Developer of this web',
    'prodi'    => 'Informatika Angkatan 25',
    'foto'      => "img/dev.png",
    'NIM' => '412025012',
    'chat_url'  => 'https://example.com/chat-langsung',
];

include 'includes/frame.php';
?>

<style>
  .dev-card {
    max-width: 780px;
    border: 2px solid #8a8e91;
    border-radius: 14px;
    padding: 28px;
    background-color: #ffffff;
  }

  .dev-photo {
    width: 165px;
    height: 200px;
    border: 10%;
    background-color: #c7cfd6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: #222222;
    overflow: hidden;
    flex-shrink: 0;
  }

  .dev-photo img {
    width: 100%;
    height: 110%;
    object-fit: cover;
  }

  .dev-spec td {
    padding: 4px 10px 4px 0;
    vertical-align: top;
  }

  .floating-chat-btn {
    position: fixed;
    right: 24px;
    bottom: 24px;
    background-color: #7c8082;
    color: #ffffff;
    border: 2px solid #000000;
    border-radius: 999px;
    padding: 12px 24px;
    font-size: 1.05rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.18);
    z-index: 1000;
    transition: background-color 0.15s, transform 0.15s;
  }

  .floating-chat-btn:hover {
    background-color: #5f6365;
    color: #ffffff;
    transform: translateY(-2px);
  }
</style>

<main class="container-fluid px-3 px-md-4 py-4">
  <h2 class="fw-bold fs-3 mb-4">Tentang Developer</h2>

  <div class="dev-card">
    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4">
      <div class="dev-photo">
        <?php if ($developer['foto'] !== ''): ?>
          <img src="<?= htmlspecialchars($developer['foto']) ?>" alt="Foto Developer">
        <?php else: ?>
          FOTO DEV
        <?php endif; ?>
      </div>

      <!-- Nama & Detail Developer -->
      <div class="flex-grow-1 text-center text-md-start">
        <h3 class="fw-bold fs-2 mb-1"><?= htmlspecialchars($developer['nama']) ?></h3>
        <div class="text-muted fs-5 mb-3"><?= htmlspecialchars($developer['role']) ?></div>

        <p class="mb-3" style="line-height: 1.5;">
          <?= htmlspecialchars($developer['NIM']) ?>
        </p>

        <table class="dev-spec mx-auto mx-md-0 text-start">
          <tr>
            <td class="fw-semibold">Prodi</td>
            <td>:</td>
            <td><?= htmlspecialchars($developer['prodi']) ?></td>
          </tr>
        </table>
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