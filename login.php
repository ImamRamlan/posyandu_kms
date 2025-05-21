<?php
ob_start();
session_start();
include 'koneksi.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mendapatkan data pengguna berdasarkan username
    $stmt = $koneksi->prepare("SELECT * FROM tb_user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password yang diinputkan dengan password yang ada di database
        // Tidak menggunakan password_verify di sini, hanya membandingkan langsung
        if ($password === $user['password']) {
            // Set session untuk pengguna yang berhasil login
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];

            // Cek apakah pengguna memilih "Remember Me"
            if (isset($_POST['remember'])) {
                // Generate token acak untuk remember token
                $token = bin2hex(random_bytes(16));
                // Hash token sebelum disimpan ke database
                $hashedToken = password_hash($token, PASSWORD_DEFAULT);

                // Simpan token yang sudah di-hash ke database
                $stmt = $koneksi->prepare("UPDATE tb_user SET remember_token = ? WHERE id_user = ?");
                $stmt->bind_param("si", $hashedToken, $user['id_user']);
                $stmt->execute();

                // Simpan token ke dalam cookie untuk mengingat pengguna selama 30 hari
                setcookie('remember_token', $token, time() + (86400 * 30), "/"); // Cookie berlaku selama 30 hari
            }

            // Redirect ke halaman index.php setelah login berhasil
            header("Location: index.php");
            exit();
        } else {
            $error = "Kata sandi salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Posyandu Melati</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* CSS untuk latar belakang */
        body {
            background-image: url('bg_posyandu.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            height: 100vh;
            font-family: Arial, sans-serif;
        }

        /* CSS untuk card */
        .card {
            background-color: rgba(255, 255, 255, 0.9);
        }

        /* CSS untuk link registrasi */
        .btn-link {
            color: #007bff;
            text-decoration: none;
        }

        .btn-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <section class="vh-100">
        <div class="container py-5 h-90">
            <div class="row d-flex justify-content-center align-items-center h-110">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <h3 class="mb-5">Posyandu Melati</h3>

                            <!-- Tampilkan pesan error jika ada -->
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>

                            <form action="login.php" method="POST">
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <input type="text" id="username" name="username" class="form-control form-control-lg" required placeholder="Masukkan Username.." minlength="8" 
                                        maxlength="12">
                                    </div>

                                    <div class="form-group col-12">
                                        <input type="password" id="password" name="password" class="form-control form-control-lg" required placeholder="Masukkan Kata sandi.." minlength="8" 
                                        maxlength="12">
                                    </div>
                                </div>

                                <!-- Checkbox Remember Me -->
                                <div class="form-row mb-4">
                                    <div class="col-4 d-flex justify-content-right">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                            <label class="form-check-label" for="remember">Ingat Saya</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row mb-4">
                                    <div class="col-12">
                                        <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
                                    </div>
                                </div>
                                <hr class="my-2">

                                <a href="registrasi.php" class="btn btn-link">Registrasi Akun Anda.</a>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>