<?php
include 'config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran - PPDB Online SMPN 1 Banjarnegara</title>
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
                    <li class="nav-item"><a class="nav-link active" href="daftar.php">Pendaftaran</a></li>
                    <li class="nav-item"><a class="nav-link" href="cek_status.php">Cek Status</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="card info-card">
            <div class="card-header">
                <h3 class="mb-0 text-center">Formulir Pendaftaran Siswa Baru</h3>
            </div>
            <div class="card-body p-4">
                <form action="proses_daftar.php" method="POST" enctype="multipart/form-data">
                    
                    <h5 class="mb-3">Data Diri Calon Siswa</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nisn" class="form-label">NISN</label>
                            <input type="text" class="form-control" id="nisn" name="nisn" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik" required>
                        </div>
                         <div class="col-md-3 mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                    </div>
                     <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="asal_sekolah" class="form-label">Asal Sekolah</label>
                            <input type="text" class="form-control" id="asal_sekolah" name="asal_sekolah" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_telepon" class="form-label">No. Telepon/HP (WhatsApp)</label>
                            <input type="tel" class="form-control" id="no_telepon" name="no_telepon" required>
                        </div>
                    </div>
                     <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Pilihan Jalur Pendaftaran</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_jalur" class="form-label">Jalur Pendaftaran</label>
                            <select class="form-select" id="id_jalur" name="id_jalur" required>
                                <option selected disabled value="">Pilih Jalur...</option>
                                <?php
                                $query_jalur = mysqli_query($koneksi, "SELECT * FROM jalur_pendaftaran");
                                while($jalur = mysqli_fetch_assoc($query_jalur)){
                                    echo "<option value='{$jalur['id_jalur']}'>{$jalur['nama_jalur']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="rata_rata_rapor" class="form-label">Nilai Rata-rata Rapor (Semester 4-5)</label>
                            <input type="number" step="0.01" class="form-control" id="rata_rata_rapor" name="rata_rata_rapor" required>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Unggah Berkas (Format: PDF, JPG, PNG - Maks 2MB)</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="berkas_kk" class="form-label">Kartu Keluarga</label>
                            <input class="form-control" type="file" id="berkas_kk" name="berkas_kk" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="berkas_akta" class="form-label">Akta Kelahiran</label>
                            <input class="form-control" type="file" id="berkas_akta" name="berkas_akta" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="berkas_ijazah" class="form-label">Ijazah / Surat Keterangan Lulus</label>
                            <input class="form-control" type="file" id="berkas_ijazah" name="berkas_ijazah" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="berkas_rapor" class="form-label">Scan Rapor (Semester 4-5)</label>
                            <input class="form-control" type="file" id="berkas_rapor" name="berkas_rapor" required>
                        </div>
                    </div>
                     <div class="mb-3">
                        <label for="berkas_prestasi" class="form-label">Sertifikat Prestasi (Jika ada)</label>
                        <input class="form-control" type="file" id="berkas_prestasi" name="berkas_prestasi">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Daftar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="bg-light text-center text-lg-start mt-5">
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2025 - PPDB Online SMPN 1 Banjarnegara
        </div>
    </footer>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>