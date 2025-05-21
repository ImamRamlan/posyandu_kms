<?php
ob_start();
session_start();
include 'koneksi.php';
include 'header.php';
include 'navbar.php';
include 'remember_me.php';
$title = "Menu";

// Ambil id_user dari session
$id_user = $_SESSION['id_user'];

// Query untuk menghitung jumlah balita berdasarkan id_ortu (yang terkait dengan id_user)
$balita_query = "
    SELECT COUNT(*) AS total_balita 
    FROM tb_balita 
    WHERE id_ortu = (
        SELECT id_user 
        FROM tb_user 
        WHERE id_user = ?
    )
";

// Siapkan statement dengan parameterized query
$stmt = $koneksi->prepare($balita_query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$balita_result = $stmt->get_result();
$total_balita = $balita_result->fetch_assoc()['total_balita'];

$stmt->close();


?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Posyandu Melati</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Menu</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <!-- Card Statistik Balita -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= htmlspecialchars($total_balita); ?></h3>
                            <p>Total Balita</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-baby"></i>
                        </div>
                        <a href="informasi_balita.php" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Card Imunisasi -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Imunisasi</h3>
                            <p>Daftar Imunisasi Balita</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-syringe"></i>
                        </div>
                        <a href="daftar_imunisasi.php" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Card KMS -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>KMS</h3>
                            <p>Kartu Menuju Sehat</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <a href="kartu_menuju_sehat.php" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Card Pengaturan -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>Pengaturan</h3>
                            <p>Pengaturan Akun Anda.</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <a href="akun.php" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
