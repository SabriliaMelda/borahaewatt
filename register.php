<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Sistem Pembayaran Listrik</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right,rgb(224, 197, 249),rgb(214, 180, 242));
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
      color: #333;
    }
    .login-card {
      background: #fff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
      max-width: 460px;
      width: 100%;
      animation: fadeIn 0.8s ease-in-out;
    }
    .login-card h2 {
      font-weight: 700;
      color: #7b2cbf;
      margin-bottom: 25px;
      text-align: center;
    }
    .form-control {
      border-radius: 10px;
    }
    .form-icon {
      position: absolute;
      top: 10px;
      left: 10px;
      color: #9d4edd;
    }
    .form-group {
      position: relative;
    }
    .form-group input {
      padding-left: 40px;
    }
    .btn-purple {
      background-color: #7b2cbf;
      color: #fff;
      border-radius: 10px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .btn-purple:hover {
      background-color: #5a189a;
    }
    .login-footer {
      margin-top: 20px;
      text-align: center;
    }
    .login-footer a {
      color: #7b2cbf;
      text-decoration: none;
    }
    .login-footer a:hover {
      text-decoration: underline;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <div class="login-card">
    <h2><i class="bi bi-person-plus-fill"></i> Daftar Akun</h2>
    <form method="POST" action="proses_register.php">
      <div class="mb-3 form-group">
        <i class="bi bi-person-fill form-icon"></i>
        <input type="text" class="form-control" name="username" placeholder="Username" required>
      </div>

      <div class="mb-3 form-group">
        <i class="bi bi-lightning-fill form-icon"></i>
        <input type="text" class="form-control" name="nomor_kwh" placeholder="Nomor KWH" required>
      </div>

      <div class="mb-3 form-group">
        <i class="bi bi-house-fill form-icon"></i>
        <input type="text" class="form-control" name="alamat" placeholder="Alamat" required>
      </div>

      <div class="mb-3 form-group">
        <i class="bi bi-envelope-fill form-icon"></i>
        <input type="email" class="form-control" name="email" placeholder="Email" required>
      </div>

      <div class="mb-3 form-group">
        <i class="bi bi-lock-fill form-icon"></i>
        <input type="password" class="form-control" name="password" placeholder="Password" required>
      </div>

      <button type="submit" class="btn btn-purple w-100">Daftar</button>
    </form>
    <div class="login-footer">
      <p class="mt-3">Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
    </div>
  </div>

</body>
</html>