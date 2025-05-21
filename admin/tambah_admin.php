<?php
ob_start();
session_start();
require_once '../koneksi.php';
$title = "Tambah Admin | Posyandu Melati";

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
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
                    <h1 class="m-0">Tambah Admin</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tambah Admin</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i>Pesan</h5>
                    <?php echo $_SESSION['error']; ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Form Tambah Admin</h3>
                        </div>
                        <div class="card-body">
                            <form action="proses_admin/p_tambah_admin.php" method="post">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" id="username" name="username" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" id="password" name="password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="nama_lengkap">Nama Lengkap</label>
                                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select id="role" name="role" class="form-control" required>
                                        <option value="Admin">Admin</option>
                                        <option value="Kader">Kader</option>
                                        <option value="Bidan">Bidan</option>
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