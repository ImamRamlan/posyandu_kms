<?php
ob_start();
session_start();
require_once '../koneksi.php';
$title = "Edit Balita | Posyandu Melati";

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Ambil ID balita dari parameter URL
$id_balita = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_balita <= 0) {
    echo "ID balita tidak valid.";
    exit();
}

// Ambil data balita untuk ditampilkan di form
try {
    $stmt = $koneksi->prepare("SELECT * FROM tb_balita WHERE id_balita = ?");
    $stmt->bind_param("i", $id_balita);
    $stmt->execute();
    $balita = $stmt->get_result()->fetch_assoc();

    if (!$balita) {
        echo "Data balita tidak ditemukan.";
        exit();
    }

    // Ambil data pengguna untuk dropdown
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
                    <h1 class="m-0">Edit Data Balita</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Edit Balita</li>
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
                            <h3 class="card-title">Form Edit Balita</h3>
                        </div>
                        <div class="card-body">
                            <form action="proses_admin/p_edit_balita.php" method="post">
                                <input type="hidden" name="id_balita" value="<?php echo htmlspecialchars($balita['id_balita']); ?>">

                                <div class="form-group">
                                    <label for="nama_balita">Nama Balita</label>
                                    <input type="text" id="nama_balita" name="nama_balita" class="form-control" value="<?php echo htmlspecialchars($balita['nama_balita']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required>
                                        <option value="L" <?php echo ($balita['jenis_kelamin'] === 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                        <option value="P" <?php echo ($balita['jenis_kelamin'] === 'P') ? 'selected' : ''; ?>>Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" value="<?php echo htmlspecialchars($balita['tanggal_lahir']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="tempat_lahir">Tempat Lahir</label>
                                    <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control" value="<?php echo htmlspecialchars($balita['tempat_lahir']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="bb_lahir">Lingkar Kepala Saat Lahir</label>
                                    <input type="text" id="lingkar_kepala" name="lingkar_kepala" class="form-control" value="<?php echo htmlspecialchars($balita['lingkar_kepala']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="bb_lahir">Berat Badan Saat Lahir</label>
                                    <input type="text" id="bb_lahir" name="bb_lahir" class="form-control" value="<?php echo htmlspecialchars($balita['bb_lahir']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="p_persalinan">Penolong Persalinan</label>
                                    <input type="text" id="p_persalinan" name="p_persalinan" class="form-control" value="<?php echo htmlspecialchars($balita['p_persalinan']); ?>" required>
                                </div>
                            
                                <div class="form-group">
                                    <label for="nama_ayah">Nama Ayah</label>
                                    <input type="text" id="nama_ayah" name="nama_ayah" class="form-control" value="<?php echo htmlspecialchars($balita['nama_ayah']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="nama_ibu">Nama Ibu</label>
                                    <input type="text" id="nama_ibu" name="nama_ibu" class="form-control" value="<?php echo htmlspecialchars($balita['nama_ibu']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="pekerjaan_ayah">Pekerjaan Ayah</label>
                                    <input type="text" id="pekerjaan_ayah" name="pekerjaan_ayah" class="form-control" value="<?php echo htmlspecialchars($balita['pekerjaan_ayah']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="pekerjaan_ibu">Pekerjaan Ibu</label>
                                    <input type="text" id="pekerjaan_ibu" name="pekerjaan_ibu" class="form-control" value="<?php echo htmlspecialchars($balita['pekerjaan_ibu']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="pendidikan_ayah">Pendidikan Ayah</label>
                                    <input type="text" id="pendidikan_ayah" name="pendidikan_ayah" class="form-control" value="<?php echo htmlspecialchars($balita['pendidikan_ayah']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="pendidikan_ibu">Pendidikan Ibu</label>
                                    <input type="text" id="pendidikan_ibu" name="pendidikan_ibu" class="form-control" value="<?php echo htmlspecialchars($balita['pendidikan_ibu']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_hp">No HP</label>
                                    <input type="text" id="no_hp" name="no_hp" class="form-control" value="<?php echo htmlspecialchars($balita['no_hp']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea id="alamat" name="alamat" class="form-control" rows="3" required><?php echo htmlspecialchars($balita['alamat']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah_anak">Jumlah Anak</label>
                                    <input type="text" id="jumlah_anak" name="jumlah_anak" class="form-control" value="<?php echo htmlspecialchars($balita['jumlah_anak']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="anak_ke">Anak Ke</label>
                                    <input type="text" id="anak_ke" name="anak_ke" class="form-control" value="<?php echo htmlspecialchars($balita['anak_ke']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="id_ortu">User</label>
                                    <select class="form-control" id="id_ortu" name="id_ortu" required>
                                        <?php while ($user = $users->fetch_assoc()): ?>
                                            <option value="<?php echo $user['id_user']; ?>" <?php echo ($user['id_user'] == $balita['id_ortu']) ? 'selected' : ''; ?>>
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

<?php require_once 'include/footer.php' ?>