<?php
session_start();
require_once '../koneksi.php';

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Ambil ID KMS dari parameter URL
$id_kms = isset($_GET['id_kms']) ? intval($_GET['id_kms']) : 0;

if ($id_kms <= 0) { 
    echo "ID KMS tidak valid.";
    exit();
}

try {
    // Prepare SQL statement to fetch KMS details
    $stmt = $koneksi->prepare("SELECT * FROM tb_kms WHERE id_kms = ?");
    
    // Bind parameter
    $stmt->bind_param('i', $id_kms);
    
    // Execute the statement
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $kms = $result->fetch_assoc();
    } else {
        echo "Data KMS tidak ditemukan.";
        exit();
    }
    
    // Prepare SQL statement to fetch Balita details
    $stmt_balita = $koneksi->prepare("SELECT * FROM tb_balita WHERE id_balita = ?");
    $stmt_balita->bind_param('i', $kms['id_balita']);
    $stmt_balita->execute();
    $result_balita = $stmt_balita->get_result();
    
    if ($result_balita->num_rows > 0) {
        $balita = $result_balita->fetch_assoc();
    } else {
        echo "Data Balita tidak ditemukan.";
        exit();
    }
    
    // Close statements
    $stmt->close();
    $stmt_balita->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$koneksi->close();
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
                    <h1 class="m-0">Detail Data KMS</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Detail KMS</li>
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
                            <h3 class="card-title">Detail KMS</h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-3">Nama Balita</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($balita['nama_balita']); ?></dd>
                                
                                <dt class="col-sm-3">Jenis Kelamin</dt>
                                <dd class="col-sm-9"><?php echo $balita['jenis_kelamin'] === 'Laki Laki' ? 'Laki-laki' : 'Perempuan'; ?></dd>
                                
                                <dt class="col-sm-3">Tanggal Lahir</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($balita['tanggal_lahir']); ?></dd>
                                
                                <dt class="col-sm-3">Tempat Lahir</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($balita['tempat_lahir']); ?></dd>
                                
                                <dt class="col-sm-3">Berat Badan Lahir</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($balita['bb_lahir']); ?></dd>
                                
                                <dt class="col-sm-3">Lingkar Kepala</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($balita['lingkar_kepala']); ?></dd>
                                
                                <dt class="col-sm-3">Penolong Persalinan</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($balita['p_persalinan']); ?></dd>
                                
                                <dt class="col-sm-3">Nama Ayah</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($balita['nama_ayah']); ?></dd>
                                
                                <dt class="col-sm-3">Nama Ibu</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($balita['nama_ibu']); ?></dd>
                                
                                <dt class="col-sm-3">Pekerjaan Ayah</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($balita['pekerjaan_ayah']); ?></dd>
                                
                                <dt class="col-sm-3">Pekerjaan Ibu</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($balita['pekerjaan_ibu']); ?></dd>
                                
                                <dt class="col-sm-3">Pendidikan Ayah</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($balita['pendidikan_ayah']); ?></dd>
                                
                                <dt class="col-sm-3">Pendidikan Ibu</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($balita['pendidikan_ibu']); ?></dd>
                                
                                <dt class="col-sm-3">No HP</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($balita['no_hp']); ?></dd>
                                
                                <dt class="col-sm-3">Alamat</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($balita['alamat']); ?></dd>
                                
                                <dt class="col-sm-3">Jumlah Anak</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($balita['jumlah_anak']); ?></dd>
                                
                                <dt class="col-sm-3">Anak Ke</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($balita['anak_ke']); ?></dd>
                                
                                <dt class="col-sm-3">Status Gizi BB Umur</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($kms['status_gizi_bb_umur']); ?></dd>
                                
                                <dt class="col-sm-3">Status Gizi BB Tinggi</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($kms['status_gizi_bb_tinggi']); ?></dd>
                                
                                <dt class="col-sm-3">Tanggal KMS</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($kms['tanggal']); ?></dd>
                                
                                <dt class="col-sm-3">Tanggal Update</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($kms['tanggal_update']); ?></dd>
                            </dl>
                            <a href="data_kms.php" class="btn btn-warning">Kembali</a>
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
