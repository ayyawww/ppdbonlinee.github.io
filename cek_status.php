<?php
include 'config/koneksi.php';
$hasil = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomor_pendaftaran = mysqli_real_escape_string($koneksi, $_POST['nomor_pendaftaran']);

    $query = "SELECT a.nama_lengkap, a.nomor_pendaftaran, a.status_pendaftaran, b.nama_jalur 
              FROM calon_siswa a 
              JOIN jalur_pendaftaran b ON a.id_jalur = b.id_jalur
              WHERE a.nomor_pendaftaran = ?";
    
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 's', $nomor_pendaftaran);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $hasil = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pendaftaran - PPDB Online</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">SMPN 1 BANJARNEGARA</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="daftar.php">Pendaftaran</a></li>
                    <li class="nav-item"><a class="nav-link active" href="cek_status.php">Cek Status</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card info-card">
                    <div class="card-header">
                        <h4 class="text-center">Cek Status Pendaftaran Anda</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="nomor_pendaftaran" class="form-label">Masukkan Nomor Pendaftaran</label>
                                <input type="text" class="form-control form-control-lg" id="nomor_pendaftaran" name="nomor_pendaftaran" required placeholder="Contoh: PPDB-2025-0001">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Cek Status</button>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                    <div class="card info-card mt-4">
                        <div class="card-header">
                            <h5>Hasil Pengecekan</h5>
                        </div>
                        <div class="card-body p-4">
                            <?php if ($hasil): ?>
                                <p><strong>Nomor Pendaftaran:</strong> <?= htmlspecialchars($hasil['nomor_pendaftaran']) ?></p>
                                <p><strong>Nama Lengkap:</strong> <?= htmlspecialchars($hasil['nama_lengkap']) ?></p>
                                <p><strong>Jalur Pendaftaran:</strong> <?= htmlspecialchars($hasil['nama_jalur']) ?></p>
                                <?php 
                                    $status = $hasil['status_pendaftaran'];
                                    $alert_class = 'alert-info';
                                    if ($status == 'Diterima') {
                                        $alert_class = 'alert-success';
                                    } elseif ($status == 'Ditolak') {
                                        $alert_class = 'alert-danger';
                                    }
                                ?>
                                <div class="alert <?= $alert_class ?> mt-3" role="alert">
                                    <h4 class="alert-heading">Status: <?= htmlspecialchars($status) ?></h4>
                                </div>
                                <?php if ($status == 'Diterima'): ?>
                                    <p class="mt-3">Selamat! Anda dinyatakan diterima. Informasi mengenai jadwal dan persyaratan daftar ulang akan diumumkan lebih lanjut melalui website ini.</p>
                                <?php elseif ($status == 'Ditolak'): ?>
                                     <p class="mt-3">Mohon maaf, Anda belum diterima pada seleksi kali ini. Tetap semangat dan jangan putus asa.</p>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="alert alert-warning" role="alert">
                                    Nomor pendaftaran tidak ditemukan. Pastikan Anda memasukkan nomor yang benar.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer class="bg-light text-center text-lg-start mt-5 fixed-bottom">
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
            © 2025 - PPDB Online SMPN 1 Banjarnegara
        </div>
    </footer>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>