<?php
include 'partials/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID Pendaftar tidak valid.</div>";
    include 'partials/footer.php';
    exit;
}

$id_calon_siswa = (int)$_GET['id'];

// Handle form update status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $status_baru = mysqli_real_escape_string($koneksi, $_POST['status_pendaftaran']);
    $update_query = "UPDATE calon_siswa SET status_pendaftaran = ? WHERE id_calon_siswa = ?";
    $stmt = mysqli_prepare($koneksi, $update_query);
    mysqli_stmt_bind_param($stmt, 'si', $status_baru, $id_calon_siswa);
    if (mysqli_stmt_execute($stmt)) {
        echo "<div class='alert alert-success'>Status pendaftaran berhasil diperbarui.</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal memperbarui status.</div>";
    }
}

// Ambil data pendaftar
$query_siswa = "SELECT c.*, j.nama_jalur FROM calon_siswa c JOIN jalur_pendaftaran j ON c.id_jalur = j.id_jalur WHERE c.id_calon_siswa = ?";
$stmt_siswa = mysqli_prepare($koneksi, $query_siswa);
mysqli_stmt_bind_param($stmt_siswa, 'i', $id_calon_siswa);
mysqli_stmt_execute($stmt_siswa);
$result_siswa = mysqli_stmt_get_result($stmt_siswa);
$siswa = mysqli_fetch_assoc($result_siswa);

if (!$siswa) {
    echo "<div class='alert alert-danger'>Data pendaftar tidak ditemukan.</div>";
    include 'partials/footer.php';
    exit;
}

// Ambil data nilai
$query_nilai = "SELECT * FROM nilai WHERE id_calon_siswa = ?";
$stmt_nilai = mysqli_prepare($koneksi, $query_nilai);
mysqli_stmt_bind_param($stmt_nilai, 'i', $id_calon_siswa);
mysqli_stmt_execute($stmt_nilai);
$result_nilai = mysqli_stmt_get_result($stmt_nilai);
$nilai = mysqli_fetch_assoc($result_nilai);

// Ambil data berkas
$query_berkas = "SELECT * FROM berkas WHERE id_calon_siswa = ?";
$stmt_berkas = mysqli_prepare($koneksi, $query_berkas);
mysqli_stmt_bind_param($stmt_berkas, 'i', $id_calon_siswa);
mysqli_stmt_execute($stmt_berkas);
$result_berkas = mysqli_stmt_get_result($stmt_berkas);

?>

<a href="data_pendaftar.php" class="btn btn-secondary mb-3">Kembali ke Daftar Pendaftar</a>

<h2>Detail Pendaftar: <?= htmlspecialchars($siswa['nama_lengkap']) ?></h2>
<hr>

<div class="row">
    <!-- Kolom Kiri: Data Diri & Pendaftaran -->
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header">Data Diri</div>
            <div class="card-body">
                <p><strong>Nomor Pendaftaran:</strong> <?= htmlspecialchars($siswa['nomor_pendaftaran']) ?></p>
                <p><strong>Nama Lengkap:</strong> <?= htmlspecialchars($siswa['nama_lengkap']) ?></p>
                <p><strong>NISN / NIK:</strong> <?= htmlspecialchars($siswa['nisn']) ?> / <?= htmlspecialchars($siswa['nik']) ?></p>
                <p><strong>Tempat, Tanggal Lahir:</strong> <?= htmlspecialchars($siswa['tempat_lahir']) ?>, <?= date('d F Y', strtotime($siswa['tanggal_lahir'])) ?></p>
                <p><strong>Alamat:</strong> <?= htmlspecialchars($siswa['alamat']) ?></p>
                <p><strong>Asal Sekolah:</strong> <?= htmlspecialchars($siswa['asal_sekolah']) ?></p>
                <p><strong>No. Telepon:</strong> <?= htmlspecialchars($siswa['no_telepon']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($siswa['email']) ?></p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Data Pendaftaran & Nilai</div>
            <div class="card-body">
                <p><strong>Jalur Pendaftaran:</strong> <?= htmlspecialchars($siswa['nama_jalur']) ?></p>
                <p><strong>Tanggal Daftar:</strong> <?= date('d F Y H:i', strtotime($siswa['tanggal_daftar'])) ?></p>
                <p><strong>Nilai Rata-rata Rapor:</strong> <?= htmlspecialchars($nilai['rata_rata_rapor']) ?></p>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Berkas & Aksi -->
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header">Berkas Terunggah</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php while($berkas = mysqli_fetch_assoc($result_berkas)): ?>
                        <li class="list-group-item">
                            <a href="../<?= htmlspecialchars($berkas['path_berkas']) ?>" target="_blank">
                                <?= htmlspecialchars($berkas['jenis_berkas']) ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Aksi Verifikasi</div>
            <div class="card-body">
                <p><strong>Status Saat Ini:</strong> 
                    <?php 
                        $status = $siswa['status_pendaftaran'];
                        $badge_class = 'bg-warning text-dark';
                        if ($status == 'Diterima') $badge_class = 'bg-success';
                        if ($status == 'Ditolak') $badge_class = 'bg-danger';
                        echo "<span class='badge $badge_class'>$status</span>";
                    ?>
                </p>
                <hr>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="status_pendaftaran" class="form-label">Ubah Status Menjadi:</label>
                        <select name="status_pendaftaran" id="status_pendaftaran" class="form-select">
                            <?php 
                            $statuses = ['Belum Diverifikasi', 'Diterima', 'Ditolak'];
                            foreach ($statuses as $s) {
                                $selected = ($siswa['status_pendaftaran'] == $s) ? 'selected' : '';
                                echo "<option value='$s' $selected>$s</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>