<?php
include 'config/koneksi.php';

// Fungsi untuk upload file
function upload_file($file_input_name, $id_calon_siswa, $jenis_berkas) {
    global $koneksi;
    $target_dir = "uploads/";
    $file_name = basename($_FILES[$file_input_name]["name"]);
    $target_file = $target_dir . time() . '-' . $file_name;
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek jika file ada
    if (file_exists($target_file)) {
        echo "Maaf, file sudah ada.";
        $uploadOk = 0;
    }

    // Cek ukuran file (misal, maks 2MB)
    if ($_FILES[$file_input_name]["size"] > 2000000) {
        echo "Maaf, ukuran file terlalu besar.";
        $uploadOk = 0;
    }

    // Izinkan format tertentu
    if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "pdf" ) {
        echo "Maaf, hanya format JPG, JPEG, PNG & PDF yang diizinkan.";
        $uploadOk = 0;
    }

    // Cek jika $uploadOk adalah 0
    if ($uploadOk == 0) {
        return false;
    } else {
        if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $target_file)) {
            // Simpan path ke database
            $query = "INSERT INTO berkas (id_calon_siswa, jenis_berkas, path_berkas) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, 'iss', $id_calon_siswa, $jenis_berkas, $target_file);
            mysqli_stmt_execute($stmt);
            return true;
        } else {
            return false;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data dari form
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $nisn = mysqli_real_escape_string($koneksi, $_POST['nisn']);
    $nik = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $tempat_lahir = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $asal_sekolah = mysqli_real_escape_string($koneksi, $_POST['asal_sekolah']);
    $no_telepon = mysqli_real_escape_string($koneksi, $_POST['no_telepon']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $id_jalur = (int)$_POST['id_jalur'];
    $rata_rata_rapor = (float)$_POST['rata_rata_rapor'];

    // Generate nomor pendaftaran unik (Contoh: PPDB-2025-0001)
    $query_max_id = mysqli_query($koneksi, "SELECT MAX(id_calon_siswa) as max_id FROM calon_siswa");
    $row = mysqli_fetch_assoc($query_max_id);
    $next_id = $row['max_id'] + 1;
    $nomor_pendaftaran = 'PPDB-' . date('Y') . '-' . str_pad($next_id, 4, '0', STR_PAD_LEFT);

    // Mulai transaksi
    mysqli_begin_transaction($koneksi);

    try {
        // Insert ke tabel calon_siswa
        $query_siswa = "INSERT INTO calon_siswa (nomor_pendaftaran, nama_lengkap, nisn, nik, tempat_lahir, tanggal_lahir, alamat, asal_sekolah, no_telepon, email, id_jalur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_siswa = mysqli_prepare($koneksi, $query_siswa);
        mysqli_stmt_bind_param($stmt_siswa, 'ssssssssssi', $nomor_pendaftaran, $nama_lengkap, $nisn, $nik, $tempat_lahir, $tanggal_lahir, $alamat, $asal_sekolah, $no_telepon, $email, $id_jalur);
        mysqli_stmt_execute($stmt_siswa);
        $id_calon_siswa = mysqli_insert_id($koneksi);

        // Insert ke tabel nilai
        $query_nilai = "INSERT INTO nilai (id_calon_siswa, rata_rata_rapor) VALUES (?, ?)";
        $stmt_nilai = mysqli_prepare($koneksi, $query_nilai);
        mysqli_stmt_bind_param($stmt_nilai, 'id', $id_calon_siswa, $rata_rata_rapor);
        mysqli_stmt_execute($stmt_nilai);

        // Upload berkas
        $upload_kk = upload_file('berkas_kk', $id_calon_siswa, 'Kartu Keluarga');
        $upload_akta = upload_file('berkas_akta', $id_calon_siswa, 'Akta Kelahiran');
        $upload_ijazah = upload_file('berkas_ijazah', $id_calon_siswa, 'Ijazah/SKL');
        $upload_rapor = upload_file('berkas_rapor', $id_calon_siswa, 'Rapor');
        
        // Upload berkas prestasi jika ada
        $upload_prestasi = true; // Anggap berhasil jika tidak ada file
        if (isset($_FILES['berkas_prestasi']) && $_FILES['berkas_prestasi']['error'] == 0) {
            $upload_prestasi = upload_file('berkas_prestasi', $id_calon_siswa, 'Prestasi');
        }

        if (!$upload_kk || !$upload_akta || !$upload_ijazah || !$upload_rapor || !$upload_prestasi) {
            throw new Exception("Gagal mengunggah salah satu berkas.");
        }

        // Jika semua berhasil, commit transaksi
        mysqli_commit($koneksi);

        // Tampilkan halaman sukses
        echo "<html lang='id'><head><title>Pendaftaran Berhasil</title><link href='assets/css/bootstrap.min.css' rel='stylesheet'></head><body>";
        echo "<div class='container mt-5'><div class='alert alert-success' role='alert'>";
        echo "<h4 class='alert-heading'>Pendaftaran Berhasil!</h4>";
        echo "<p>Terima kasih, <strong>" . htmlspecialchars($nama_lengkap) . "</strong>. Data Anda telah berhasil kami simpan.</p>";
        echo "<hr><p class='mb-0'>Nomor pendaftaran Anda adalah: <strong>" . htmlspecialchars($nomor_pendaftaran) . "</strong></p>";
        echo "<p>Silakan simpan nomor ini untuk melakukan pengecekan status pendaftaran.</p>";
        echo "<a href='index.php' class='btn btn-primary'>Kembali ke Beranda</a>";
        echo "</div></div></body></html>";

    } catch (Exception $e) {
        // Jika ada error, rollback transaksi
        mysqli_rollback($koneksi);
        echo "Pendaftaran gagal: " . $e->getMessage();
    }

    mysqli_close($koneksi);
}
?>