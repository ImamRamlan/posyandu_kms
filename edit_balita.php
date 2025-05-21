<?php
ob_start();
session_start();
include 'koneksi.php';  // Koneksi ke database
include 'header.php';   // Header halaman
include 'navbar.php';   // Navbar halaman
include 'remember_me.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php'); // Alihkan ke halaman login jika belum login
    exit();
}

// Ambil ID balita dari parameter URL
$id_balita = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mengambil data balita berdasarkan ID
$query = "SELECT * FROM tb_balita WHERE id_balita = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('i', $id_balita);
$stmt->execute();
$result = $stmt->get_result();

// Periksa apakah data balita ditemukan
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    // Alihkan jika data tidak ditemukan
    header('Location: informasi_balita.php');
    exit();
}

// Proses update data ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_balita = $_POST['nama_balita'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $bb_lahir = $_POST['bb_lahir'];
    $p_persalinan = $_POST['p_persalinan'];
    $nama_ibu = $_POST['nama_ibu'];
    $nama_ayah = $_POST['nama_ayah'];
    $pekerjaan_ayah = $_POST['pekerjaan_ayah'];
    $pekerjaan_ibu = $_POST['pekerjaan_ibu'];
    $pendidikan_ayah = $_POST['pendidikan_ayah'];
    $pendidikan_ibu = $_POST['pendidikan_ibu'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    $jumlah_anak = $_POST['jumlah_anak'];
    $anak_ke = $_POST['anak_ke'];
    $lingkar_kepala = $_POST['lingkar_kepala'];

    // Update query
    $update_query = "UPDATE tb_balita SET nama_balita=?, jenis_kelamin=?, tanggal_lahir=?, tempat_lahir=?, bb_lahir=?, p_persalinan=?, nama_ibu=?, nama_ayah=?, pekerjaan_ayah=?, pekerjaan_ibu=?, pendidikan_ayah=?, pendidikan_ibu=?, no_hp=?, alamat=?, jumlah_anak=?, anak_ke=?, lingkar_kepala=? WHERE id_balita=?";
    $stmt = $koneksi->prepare($update_query);
    $stmt->bind_param('sssssssssssssssssi', $nama_balita, $jenis_kelamin, $tanggal_lahir, $tempat_lahir, $bb_lahir, $p_persalinan, $nama_ibu, $nama_ayah, $pekerjaan_ayah, $pekerjaan_ibu, $pendidikan_ayah, $pendidikan_ibu, $no_hp, $alamat, $jumlah_anak, $anak_ke, $lingkar_kepala, $id_balita);
    $stmt->execute();

    // Redirect setelah update berhasil
    header('Location: detail_balita.php?id=' . $id_balita);
    exit();
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Informasi Balita</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="informasi_balita.php">Informasi Balita</a></li>
                        <li class="breadcrumb-item active">Edit Balita</li>
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
                            <h5 class="card-title">Edit <?= htmlspecialchars($row['nama_balita']); ?></h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="form-group">
                                    <label for="nama_balita">Nama Balita</label>
                                    <input type="text" name="nama_balita" class="form-control" value="<?= htmlspecialchars($row['nama_balita']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-control" required>
                                        <option value="Laki-laki" <?= $row['jenis_kelamin'] == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                                        <option value="Perempuan" <?= $row['jenis_kelamin'] == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control" value="<?= htmlspecialchars($row['tanggal_lahir']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="tempat_lahir">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control" value="<?= htmlspecialchars($row['tempat_lahir']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="bb_lahir">Berat Badan Lahir (kg)</label>
                                    <input type="text" name="bb_lahir" class="form-control" value="<?= htmlspecialchars($row['bb_lahir']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="lingkar_kepala">Lingkar Kepala Saat Lahir</label>
                                    <input type="text" name="lingkar_kepala" class="form-control" value="<?= htmlspecialchars($row['lingkar_kepala']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="p_persalinan">Jenis Persalinan</label>
                                    <input type="text" name="p_persalinan" class="form-control" value="<?= htmlspecialchars($row['p_persalinan']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="nama_ibu">Nama Ibu</label>
                                    <input type="text" name="nama_ibu" class="form-control" value="<?= htmlspecialchars($row['nama_ibu']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="nama_ayah">Nama Ayah</label>
                                    <input type="text" name="nama_ayah" class="form-control" value="<?= htmlspecialchars($row['nama_ayah']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="pekerjaan_ayah">Pekerjaan Ayah</label>
                                    <input type="text" name="pekerjaan_ayah" class="form-control" value="<?= htmlspecialchars($row['pekerjaan_ayah']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="pekerjaan_ibu">Pekerjaan Ibu</label>
                                    <input type="text" name="pekerjaan_ibu" class="form-control" value="<?= htmlspecialchars($row['pekerjaan_ibu']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="pendidikan_ayah">Pendidikan Ayah</label>
                                    <input type="text" name="pendidikan_ayah" class="form-control" value="<?= htmlspecialchars($row['pendidikan_ayah']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="pendidikan_ibu">Pendidikan Ibu</label>
                                    <input type="text" name="pendidikan_ibu" class="form-control" value="<?= htmlspecialchars($row['pendidikan_ibu']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_hp">No HP</label>
                                    <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($row['no_hp']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea name="alamat" class="form-control" required><?= htmlspecialchars($row['alamat']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah_anak">Jumlah Anak</label>
                                    <input type="text" name="jumlah_anak" class="form-control" value="<?= htmlspecialchars($row['jumlah_anak']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="anak_ke">Anak Ke</label>
                                    <input type="text" name="anak_ke" class="form-control" value="<?= htmlspecialchars($row['anak_ke']); ?>" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'footer.php'; // Footer halaman
?>
