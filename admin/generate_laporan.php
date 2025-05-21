<?php
ob_start();
session_start();
require_once '../koneksi.php';
require_once 'include/header.php';
require_once 'include/navbar.php';
require_once 'include/sidebar.php';

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: ../login.php');
    exit();
}

// Ambil data dari formulir jika ada
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Generate Laporan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Generate Laporan</li>
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
                    <!-- Formulir untuk memilih rentang tanggal -->
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Pilih Rentang Tanggal</h3>
                        </div>
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="start_date">Tanggal Awal</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="end_date">Tanggal Akhir</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success btn-block">Tampilkan Data</button>
                            </form>
                        </div>
                    </div>

                    <!-- Tampilkan Data -->
                    <?php if ($start_date && $end_date): ?>
                        <?php
                        // Query untuk mengambil data berdasarkan tanggal dan bergabung dengan tb_balita
                        $query = "
                            SELECT k.id_kms, k.tanggal, k.umur, k.tinggi_badan, k.berat_badan, 
                                   k.status_gizi_bb_umur, k.status_gizi_bb_tinggi, 
                                   b.nama_balita, b.jenis_kelamin
                            FROM tb_kms k
                            JOIN tb_balita b ON k.id_balita = b.id_balita
                            WHERE k.tanggal BETWEEN ? AND ?
                        ";
                        $stmt = $koneksi->prepare($query);
                        $stmt->bind_param('ss', $start_date, $end_date);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        ?>

                        <div class="card card-secondary mt-4">
                            <div class="card-header">
                                <h3 class="card-title">Data KMS dari <?php echo htmlspecialchars($start_date); ?> hingga <?php echo htmlspecialchars($end_date); ?></h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Nama Balita</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Umur</th>
                                                <th>Tinggi Badan</th>
                                                <th>Berat Badan</th>
                                                <th>Status Gizi BB Umur</th>
                                                <th>Status Gizi BB Tinggi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['nama_balita']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['jenis_kelamin']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['umur']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['tinggi_badan']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['berat_badan']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['status_gizi_bb_umur']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['status_gizi_bb_tinggi']); ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 d-flex justify-content-start">
                                        <form method="post" action="export_pdf.php" class="mt-3 me-2" target="_blank">
                                            <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                                            <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                                            <button type="submit" class="btn btn-success" target="_blank" rel="noopener">Export to PDF</button>
                                        </form>
                                        
                                        <form method="post" action="export_excel.php" class="mt-3" target="_blank">
                                            <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                                            <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                                            <button type="submit" class="btn btn-warning" target="_blank" rel="noopener">Export to Excel</button>
                                        </form>
                                    </div>
                                </div>



                            </div>
                        </div>

                        <?php
                        $stmt->close();
                        $koneksi->close();
                        ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once 'include/footer.php'; ?>