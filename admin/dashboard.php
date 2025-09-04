<?php 
include 'partials/header.php'; 

// Query untuk statistik
$total_pendaftar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM calon_siswa"))['total'];
$pendaftar_diterima = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM calon_siswa WHERE status_pendaftaran = 'Diterima'"))['total'];
$pendaftar_ditolak = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM calon_siswa WHERE status_pendaftaran = 'Ditolak'"))['total'];
$pendaftar_pending = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM calon_siswa WHERE status_pendaftaran = 'Belum Diverifikasi'"))['total'];

$jalur_query = mysqli_query($koneksi, "SELECT j.nama_jalur, COUNT(c.id_calon_siswa) as jumlah FROM jalur_pendaftaran j LEFT JOIN calon_siswa c ON j.id_jalur = c.id_jalur GROUP BY j.id_jalur");

?>

<h2>Dashboard</h2>
<hr>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card card-stats card-stats-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Pendaftar</h5>
                        <p class="card-text fs-2"><?= $total_pendaftar ?></p>
                    </div>
                    <div class="card-icon-container">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card card-stats card-stats-success h-100">
            <div class="card-body">
                 <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Diterima</h5>
                        <p class="card-text fs-2"><?= $pendaftar_diterima ?></p>
                    </div>
                    <div class="card-icon-container">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card card-stats card-stats-danger h-100">
            <div class="card-body">
                 <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Ditolak</h5>
                        <p class="card-text fs-2"><?= $pendaftar_ditolak ?></p>
                    </div>
                    <div class="card-icon-container">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card card-stats card-stats-warning h-100">
            <div class="card-body">
                 <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Belum Diverifikasi</h5>
                        <p class="card-text fs-2"><?= $pendaftar_pending ?></p>
                    </div>
                    <div class="card-icon-container">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Pendaftar per Jalur
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php while($jalur = mysqli_fetch_assoc($jalur_query)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($jalur['nama_jalur']) ?>
                            <span class="badge bg-primary rounded-pill"><?= $jalur['jumlah'] ?></span>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Aktivitas Terbaru
            </div>
            <div class="card-body">
                <p>Belum ada aktivitas terbaru.</p>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>