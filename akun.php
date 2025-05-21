<?php
ob_start();
session_start();
include 'koneksi.php';
include 'header.php';
include 'navbar.php';
include 'remember_me.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php'); // Alihkan ke halaman login jika belum login
    exit();
}

// Ambil ID pengguna dari session
$id_user = $_SESSION['id_user'];

// Query untuk mengambil data pengguna berdasarkan ID
$query = "SELECT * FROM tb_user WHERE id_user = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('i', $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<div class='alert alert-danger'>Data pengguna tidak ditemukan.</div>";
    exit();
}

$success_message = '';
$error_message = '';

// Proses form jika ada pengiriman data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telepon'];
    $password = $_POST['password']; // Tidak di-hash dalam contoh ini
    $password_default = $_POST['password_default']; // Tidak di-hash dalam contoh ini

    // Query untuk memperbarui data pengguna
    $update_query = "UPDATE tb_user SET username = ?, nama_lengkap = ?, email = ?, no_telepon = ?, password = ?, password_default = ? WHERE id_user = ?";
    $stmt = $koneksi->prepare($update_query);
    $stmt->bind_param('ssssssi', $username, $nama_lengkap, $email, $no_telepon, $password, $password_default, $id_user);

    if ($stmt->execute()) {
        $success_message = "Data berhasil diperbarui!";
        // Segarkan data pengguna setelah pembaruan
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param('i', $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    } else {
        $error_message = "Terjadi kesalahan: " . $stmt->error;
    }
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengaturan Akun</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pengaturan Akun</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Detail Akun</h5>
                        </div>
                        <div class="card-body">
                            <!-- Notifikasi -->
                            <?php if ($success_message): ?>
                                <div class="alert alert-success">
                                    <?= htmlspecialchars($success_message); ?>
                                </div>
                            <?php elseif ($error_message): ?>
                                <div class="alert alert-danger">
                                    <?= htmlspecialchars($error_message); ?>
                                </div>
                            <?php endif; ?>

                            <form method="post" action="">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="nama_lengkap">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= htmlspecialchars($user['nama_lengkap']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_telepon">No Telepon</label>
                                    <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?= htmlspecialchars($user['no_telepon']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" value="<?= htmlspecialchars($user['password']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="password_default">Password Default</label>
                                    <input type="password" class="form-control" id="password_default" name="password_default" value="<?= htmlspecialchars($user['password_default']); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
                        </div>
                        <div class="card-footer">
                            <a href="index.php" class="btn btn-secondary btn-sm">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
