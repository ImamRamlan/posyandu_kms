<?php
ob_start();
session_start();
require_once '../koneksi.php';
$title = "Edit User | Posyandu Melati";

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

require_once 'include/header.php';
require_once 'include/navbar.php';
require_once 'include/sidebar.php';

// Ambil data user berdasarkan ID
$id_user = $_GET['id'];
try {
    $stmt = $koneksi->prepare("SELECT username, nama_lengkap, email, no_telepon FROM tb_user WHERE id_user = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Data User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Edit User</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-md">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Edit User</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="proses_admin/p_edit_user.php" method="post">
                            <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="no_telepon">No Telepon</label>
                                <input type="text" id="no_telepon" name="no_telepon" class="form-control" value="<?php echo htmlspecialchars($user['no_telepon']); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="data_user.php" class="btn btn-warning">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once 'include/footer.php'; ?>
 