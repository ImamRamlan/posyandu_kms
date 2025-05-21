<?php
ob_start();
session_start();
require_once '../koneksi.php';
$title = "Tambah Balita | Posyandu Melati";

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

try {
    $stmt = $koneksi->prepare("SELECT id_user, nama_lengkap FROM tb_user");
    $stmt->execute();
    $users = $stmt->get_result();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
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
                    <h1 class="m-0">Tambah Data Balita</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tambah Balita</li>
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
                            <h3 class="card-title">Form Tambah Balita</h3>
                        </div>
                        <div class="card-body">
                            <form action="proses_admin/p_tambah_balita.php" method="post">
                                <div class="form-group">
                                    <label for="nama_balita">Nama Balita</label>
                                    <input type="text" id="nama_balita" name="nama_balita" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="tempat_lahir">Tempat Lahir</label>
                                    <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="bb_lahir">Berat Badan Lahir</label>
                                    <input type="text" id="bb_lahir" name="bb_lahir" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="bb_lahir">Lingkar Kepala</label>
                                    <input type="text" id="lingkar_kepala" name="lingkar_kepala" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="p_persalinan">Penolong Persalinan</label>
                                    <select id="p_persalinan" name="p_persalinan" class="form-control" required>
                                        <option value="Dokter">Dokter</option>
                                        <option value="Bidan">Bidan</option>
                                        <option value="Dukun">Dukun</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama_ayah">Nama Ayah</label>
                                    <input type="text" id="nama_ayah" name="nama_ayah" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="nama_ibu">Nama Ibu</label>
                                    <input type="text" id="nama_ibu" name="nama_ibu" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="pekerjaan_ayah">Pekerjaan Ayah</label>
                                    <input type="text" id="pekerjaan_ayah" name="pekerjaan_ayah" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="pekerjaan_ibu">Pekerjaan Ibu</label>
                                    <input type="text" id="pekerjaan_ibu" name="pekerjaan_ibu" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="pendidikan_ayah">Pendidikan Ayah</label>
                                    <input type="text" id="pendidikan_ayah" name="pendidikan_ayah" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="pendidikan_ibu">Pendidikan Ibu</label>
                                    <input type="text" id="pendidikan_ibu" name="pendidikan_ibu" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_hp">No HP</label>
                                    <input type="text" id="no_hp" name="no_hp" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea id="alamat" name="alamat" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah_anak">Jumlah Anak</label>
                                    <input type="text" id="jumlah_anak" name="jumlah_anak" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="anak_ke">Anak Ke</label>
                                    <input type="text" id="anak_ke" name="anak_ke" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="id_ortu">User.</label>
                                    <select class="form-control" id="id_ortu" name="id_ortu" required>
                                        <?php while ($user = $users->fetch_assoc()): ?>
                                            <option value="<?php echo $user['id_user']; ?>">
                                                <?php echo $user['nama_lengkap']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="data_balita.php" class="btn btn-warning">Kembali</a>
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