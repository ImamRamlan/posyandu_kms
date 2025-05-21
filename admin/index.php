<?php
ob_start();
session_start();

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

include 'include/header.php';
$title = "Dashboard Admin || Posyandu Melati";

include 'include/navbar.php';
include 'include/sidebar.php';

// Ambil data dari database
require_once '../koneksi.php';

// Inisialisasi variabel jumlah
$jumlahBalita = 0;
$jumlahIbuBalita = 0;
$jumlahBidan = 0;
$jumlahKader = 0;
$jumlahKMS = 0;
$role = ''; // Inisialisasi variabel role

try {
    // Ambil data pengguna saat ini
    $userId = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0; // Pastikan ID pengguna disimpan di sesi saat login

    if ($userId > 0) {
        // Ambil role pengguna saat ini
        $stmt = $koneksi->prepare("SELECT role FROM tb_admin WHERE id_admin = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $data = $result->fetch_assoc();
            $role = $data['role'];
        }
        $stmt->close();
    } else {
        throw new Exception('User ID is not set or invalid.');
    }

    // Debugging: Print role
    echo "Role: " . $role;

    if ($role == 'Admin') {
        // Ambil jumlah balita
        $result = $koneksi->query("SELECT COUNT(*) AS total FROM tb_balita");
        if ($result) {
            $data = $result->fetch_assoc();
            $jumlahBalita = $data['total'];
        }

        // Ambil jumlah ibu balita
        $result = $koneksi->query("SELECT COUNT(*) AS total FROM tb_user WHERE role = 'ibu_balita'");
        if ($result) {
            $data = $result->fetch_assoc();
            $jumlahIbuBalita = $data['total'];
        }

        // Ambil jumlah bidan dan kader (tidak termasuk admin)
        $result = $koneksi->query("SELECT role, COUNT(*) AS total FROM tb_admin WHERE role IN ('Bidan', 'Kader') GROUP BY role");
        if ($result) {
            while ($data = $result->fetch_assoc()) {
                if ($data['role'] == 'Bidan') {
                    $jumlahBidan = $data['total'];
                } elseif ($data['role'] == 'Kader') {
                    $jumlahKader = $data['total'];
                }
            }
        }

        // Ambil jumlah Kartu Menuju Sehat
        $result = $koneksi->query("SELECT COUNT(*) AS total FROM tb_kms");
        if ($result) {
            $data = $result->fetch_assoc();
            $jumlahKMS = $data['total'];
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$koneksi->close();
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard Admin</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard Admin</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php if ($role == 'Admin') { ?>
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-baby"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Balita</span>
                                <span class="info-box-number">
                                    <?php echo $jumlahBalita; ?>
                                    <small>Jumlah</small>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-2">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-solid fa-user"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Ibu Balita</span>
                                <span class="info-box-number"><?php echo $jumlahIbuBalita; ?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>

                    <div class="col-12 col-sm-6 col-md-2">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-md"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Bidan</span>
                                <span class="info-box-number"><?php echo $jumlahBidan; ?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <div class="col-12 col-sm-6 col-md-2">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-users"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Kader</span>
                                <span class="info-box-number"><?php echo $jumlahKader; ?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-book-medical"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Kartu Menuju Sehat</span>
                                <span class="info-box-number"><?php echo $jumlahKMS; ?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h4>Selamat datang, <?php echo $_SESSION['username']; ?>!</h4>
                            <p>Semoga hari anda berjalan dengan baik!, jika ada masalah silahkan hubungi super admin. Terima kasih</p>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h4>Selamat datang, <?php echo $_SESSION['username']; ?>!</h4>
                            <p>Anda tidak memiliki akses untuk melihat statistik. Silakan hubungi admin jika Anda memerlukan informasi lebih lanjut.</p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include 'include/footer.php'; ?>