<?php
ob_start();
session_start();
require_once '../koneksi.php';
$title = "Edit Admin | Posyandu Melati";

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Cek apakah id_admin ada di URL
if (!isset($_GET['id_admin']) || empty($_GET['id_admin'])) {
    header('Location: data_admin.php');
    exit();
}

$id_admin = $_GET['id_admin'];

// Validasi dan sanitasi input
$id_admin = intval($id_admin);

try {
    $stmt = $koneksi->prepare("SELECT * FROM tb_admin WHERE id_admin = ?");
    $stmt->bind_param('i', $id_admin);
    $stmt->execute();
    $data_admin = $stmt->get_result()->fetch_assoc();

    if (!$data_admin) {
        $_SESSION['error'] = 'Admin tidak ditemukan.';
        header('Location: data_admin.php');
        exit();
    }
} catch (Exception $e) {
    $_SESSION['error'] = 'Terjadi kesalahan: ' . $e->getMessage();
    header('Location: data_admin.php');
    exit();
}

require_once 'include/header.php';
require_once 'include/navbar.php';
require_once 'include/sidebar.php';
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Admin</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Edit Admin</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit Admin</h3>
                        </div>
                        <div class="card-body">
                            <form action="proses_admin/p_edit_admin.php" method="post">
                                <input type="hidden" name="id_admin" value="<?php echo htmlspecialchars($data_admin['id_admin'], ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($data_admin['username'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                                </div>
                                <div class="form-group">
                                    <label for="nama_lengkap">Nama Lengkap</label>
                                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" value="<?php echo htmlspecialchars($data_admin['nama_lengkap'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select id="role" name="role" class="form-control" required>
                                        <option value="Admin" <?php echo (isset($data_admin['role']) && $data_admin['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                                        <option value="Kader" <?php echo (isset($data_admin['role']) && $data_admin['role'] == 'Kader') ? 'selected' : ''; ?>>Kader</option>
                                        <option value="Bidan" <?php echo (isset($data_admin['role']) && $data_admin['role'] == 'Bidan') ? 'selected' : ''; ?>>Bidan</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="data_admin.php" class="btn btn-warning">Kembali</a>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once 'include/footer.php'; ?>
