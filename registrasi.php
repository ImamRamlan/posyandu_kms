<?php
session_start();
include 'koneksi.php';

$error = "";
$success = "";

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $no_telepon = trim($_POST['no_telepon']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validasi input
    if (empty($nama_lengkap) || empty($username) || empty($email) || empty($no_telepon) || empty($password) || empty($confirm_password)) {
        $error = "Semua field harus diisi.";
    } elseif ($password !== $confirm_password) {
        $error = "Password tidak cocok.";
    } else {
        // Cek apakah username sudah ada
        $stmt = $koneksi->prepare("SELECT id_user FROM tb_user WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username atau Email sudah digunakan, silakan pilih yang lain.";
        } else {
            // Simpan password secara langsung (tidak disarankan)
            $stmt = $koneksi->prepare("INSERT INTO tb_user (username, nama_lengkap, email, no_telepon, password, password_default) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $username, $nama_lengkap, $email, $no_telepon, $password, $password);

            if ($stmt->execute()) {
                $success = "Registrasi berhasil, silakan login.";
            } else {
                $error = "Terjadi kesalahan, silakan coba lagi.";
            }
        }

        $stmt->close();
    }
    $koneksi->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Posyandu Melati | Registrasi Akun</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="admin/assets/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="admin/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="admin/assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="index.php" class="h1"><b>Posyandu</b> Melati</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Registrasi Akun Baru</p>

                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <form action="registrasi.php" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="nama_lengkap" placeholder="Nama Lengkap" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="no_telepon" placeholder="No Telepon" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-phone"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Konfirmasi Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <a href="login.php" class="text-center">Sudah punya akun?</a>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="admin/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="admin/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="admin/assets/dist/js/adminlte.min.js"></script>
</body>

</html>
