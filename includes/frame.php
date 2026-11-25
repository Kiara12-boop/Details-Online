<?php
$activePage = $activePage ?? 'home';
$pageTitle  = $pageTitle ?? 'Home';
$username   = $_SESSION['username'] ?? 'Username123';
?>
<!DOCTYPE html>
<html>
<head>
  <title>ND - <?= htmlspecialchars($pageTitle) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #000000;
      margin: 0;  
      background-image: url("img/background.jpg");
      background-size: cover;      
      background-position: center; 
      background-repeat: no-repeat;
      background-attachment: fixed;
      min-height: 100vh;
    }

    .app-shell {
      display: flex;
      min-height: 100vh;
    }

    .sidebar {
      width: 150px;
      flex-shrink: 0;
      background-color: #3f2618;
      display: flex;
      flex-direction: column;
    }

    .brand-box {
      height: 64px;
      background-color: #4d2f1d;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      font-weight: 700;
      color: #000000;
      text-decoration: none;
    }

    .side-link {
      display: block;
      padding: 22px 12px;
      text-align: center;
      text-decoration: none;
      color: #ffffff;
      font-size: 1.35rem;
      font-weight: 600;
      transition: background-color 0.15s;
    }

    .side-link:hover,
    .side-link.active {
      background-color: #2d1d12;
    }

    .main-area {
      flex: 1;
      min-width: 0;
    }

    .topbar-right {
      height: 64px;
      background-color: #3f2618;
      padding: 0 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }

    .page-heading {
      font-size: 1.45rem;
      font-weight: 700;
      margin: 0;
      color: #fff;
    }

    .user-pill {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 1.05rem;
      color: #fff;
      text-decoration: none;
      white-space: nowrap;
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: #fff;
      flex-shrink: 0;
    }

    .wire-img-box {
      width: 100%;
      aspect-ratio: 4 / 3;
      background-color: #a46f1b;
      border: 1px solid #9aa5b1;
      position: relative;
      overflow: hidden;
    }

    .wire-img-box::before,
    .wire-img-box::after {
      content: "";
      position: absolute;
      top: 50%;
      left: -15%;
      width: 130%;
      height: 2px;
      background-color: #ffffffd7; ;
    }

    .wire-img-box::before { transform: rotate(35deg); }
    .wire-img-box::after { transform: rotate(-35deg); }

    .btn-wire {
      background-color: #cbd2d8;
      color: #000000;
      border: none;
      border-radius: 10px;
      padding: 10px 26px;
      font-weight: 600;
      text-decoration: none;
      display: inline-block;
    }

    .btn-wire:hover {
      background-color: #b5bec7;
      color: #000000;
    }

    @media (max-width: 767.98px) {
      .sidebar { width: 96px; }
      .side-link { font-size: 1rem; padding: 16px 6px; }
      .page-heading { font-size: 1.1rem; }
      .topbar-right { padding: 0 14px; }
    }
  
  .boxing {
    max-width: 780px;
    border: 2px solid #000;
    border-radius: 14px;
    padding: 28px;
    background-color: #ffffff;
  }

.container {
    width: 450px;
    margin: 40px auto;
    background-color: #0000007e;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 10px #0000007e;
}

h2, h3 {
    text-align: center;
}

label {
    display: block;
    margin-top: 15px;
    margin-bottom: 5px;
}

input[type="text"],
input[type="password"],
input[type="file"],
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    box-sizing: border-box;
}

.file-input {
    display: flex;
    gap: 8px;
    align-items: center;
    margin-bottom: 10px;
}

.file-input input {
    flex: 1;
    min-width: 0;
}

button {
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

#tambahFile {
    margin-bottom: 15px;
}
  </style>
</head>
<body>

  <div class="app-shell">
    <aside class="sidebar">
      <a href="home.php" class="brand-box">ND</a>
      <nav>
        <a href="home.php" class="side-link <?= $activePage === 'home' ? 'active' : '' ?>">Home</a>
        <a href="store.php" class="side-link <?= $activePage === 'store' ? 'active' : '' ?>">Store</a>
        <a href="orders.php" class="side-link <?= $activePage === 'orders' ? 'active' : '' ?>">Orders</a>
        <a href="help.php" class="side-link <?= $activePage === 'help' ? 'active' : '' ?>">Help</a>
      </nav>
    </aside>

    <div class="main-area">
      <header class="topbar-right">
        <h1 class="page-heading"><?= htmlspecialchars($pageTitle) ?></h1>
        <a href="profile.php" class="user-pill side-link <?= $activePage === 'profile'? 'active':''?>">
          <span class="user-avatar"></span>
          <span><?= htmlspecialchars($username) ?></span>
        </a>
      </header>