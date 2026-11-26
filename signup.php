<?php session_start(); ?>
<html>
<head>
  <title>Sign Up Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #ffffff;
      color: #000000;
    }

    .topbar-signup {
      background-color: #6d3f2a;
      padding: 16px 32px;
      font-size: 1.5rem;
      font-weight: 700;
    }

    .logo image{
      width: 50px;
      height: 50px;
      align-items: center;
      justify-content: center;
    }

    .wire-input {
      background-color: #c7cfd6;
      border: none;
      border-radius: 10px;
      height: 38px;
      padding: 6px 14px;
    }

    .wire-input:focus {
      background-color: #bcc5cd;
      box-shadow: none;
      outline: 2px solid #8a949c;
    }

    .check-verified {
      color: #76b800;
      font-size: 2.3rem;
      line-height: 1;
    }

    .date-input {
      width: 82px;
      height: 52px;
      border: 2px solid #6e6e6e;
      border-radius: 10px;
      text-align: center;
      color: #555;
    }

    .date-input:focus {
      border-color: #000;
      box-shadow: none;
    }

    .btn-wire {
      background-color: #c7cfd6;
      color: #000;
      border: none;
      border-radius: 12px;
      padding: 12px 36px;
      font-size: 1.2rem;
      font-weight: 500;
    }

    .btn-wire:hover {
      background-color: #b5bec7;
      color: #000;
    }
  </style>
</head>
<body>

  <header class="topbar-signup">
    Sign Up Page
  </header>

  <main class="container py-4 py-md-5">
    <div class="row align-items-center justify-content-center g-4">
      <div class="col-md-4 text-center d-none d-md-block">
        <div class="logo">
          <img src="img/logo.png" alt="Logo">
        </div>
        <div class="fw-bold fs-4 mt-3">Toko Karpettery</div>
      </div>

      <div class="col-12 col-md-7 col-lg-6">
        <h2 class="fw-bold mb-3">Sign up here:</h2>
        <div id="signupAlert" class="alert alert-danger d-none" style="max-width: 420px;"></div>

        <form id="signupForm">
          <div class="mb-3" style="max-width: 420px;">
            <label class="form-label fs-5 mb-1">Email:</label>
            <input type="email" id="email" name="email" class="form-control wire-input" required>
          </div>

          <div class="mb-3">
            <label class="form-label fs-5 mb-1">Verification code:</label>
            <div class="d-flex align-items-center gap-3">
              <input type="text" id="code" name="code" class="form-control wire-input" style="max-width: 310px;">
              <i class="bi bi-check-circle-fill check-verified" title="Verified"></i>
            </div>
          </div>

          <div class="mb-3" style="max-width: 420px;">
            <label class="form-label fs-5 mb-1">Username:</label>
            <input type="text" id="username" name="username" class="form-control wire-input" required>
          </div>

          <div class="mb-3" style="max-width: 420px;">
            <label class="form-label fs-5 mb-1">Phone Number:</label>
            <input type="tel" id="phone" name="phone" class="form-control wire-input" required>
          </div>

          <div class="mb-3">
            <label class="form-label fs-5 mb-1">Birthdate:</label>
            <div class="d-flex gap-2 gap-sm-3">
              <input type="text" name="dd" class="form-control date-input" placeholder="DD" maxlength="2" required>
              <input type="text" name="mm" class="form-control date-input" placeholder="MM" maxlength="2" required>
              <input type="text" name="yyyy" class="form-control date-input" placeholder="YYYY" maxlength="4" required>
            </div>
          </div>

          <div class="mb-3" style="max-width: 420px;">
            <label class="form-label fs-5 mb-1">Password:</label>
            <input type="password" id="password" name="password" class="form-control wire-input" required>
          </div>

          <div class="mb-4" style="max-width: 420px;">
            <label for="verifyPassword" class="form-label fs-5 mb-1">Verify Password:</label>
            <input type="password" id="verifyPassword" name="verifyPassword" class="form-control wire-input" required>
          </div>

          <div class="d-flex justify-content-end" style="max-width: 420px;">
            <button type="submit" class="btn btn-wire">Sign Up</button>
          </div>
        </form>
        <div id="signupSuccessBox" class="alert alert-success d-none mt-3" style="max-width: 420px;">
        <div class="fw-bold mb-1">Pendaftaran berhasil!</div>
        <div class="small mb-2">
          Mengalihkan ke Home Page dalam <span id="redirectCountdown">2</span> detik...
        </div>
        <a id="goHomeNowLink" href="home.php" class="btn btn-sm btn-dark">
        Lanjut ke Home Page sekarang
        </a>
      </div>
      </div>
    </div>
  </main>

  <script>
    const form = document.getElementById("signupForm");
    const alertBox = document.getElementById("signupAlert");

    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      alertBox.classList.add("d-none");

      const data = new FormData(form);
      const password = data.get("password");
      const verifyPassword = data.get("verifyPassword");

      if (password !== verifyPassword) {
        alertBox.textContent = "Password dan Verify Password tidak sama.";
        alertBox.classList.remove("d-none");
        return;
      }

      const birthdate = `${data.get("yyyy")}-${String(data.get("mm")).padStart(2, "0")}-${String(data.get("dd")).padStart(2, "0")}`;

      const payload = {
        email: data.get("email"),
        username: data.get("username"),
        phone: data.get("phone"),
        birthdate,
        password
      };

      const res = await fetch("signUp_process.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });

      const result = await res.json();
      if (!result.ok) {
        alertBox.textContent = result.message || "Registrasi gagal.";
        alertBox.classList.remove("d-none");
        return;
      }

      window.location.href = "home.php";
    });
  </script>
</body>
</html>