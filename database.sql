-- Membuat Database
CREATE DATABASE IF NOT EXISTS ppdbonline;

-- Menggunakan Database
USE ppdbonline;

-- Tabel untuk Admin
CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert admin default
INSERT INTO `admin` (`nama_lengkap`, `username`, `password`) VALUES
('Administrator', 'admin', '21232f297a57a5a743894a0e4a801fc3'); -- password default: admin (md5)

-- Tabel untuk Jalur Pendaftaran
CREATE TABLE `jalur_pendaftaran` (
  `id_jalur` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jalur` varchar(100) NOT NULL,
  `kuota` int(11) NOT NULL,
  PRIMARY KEY (`id_jalur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert jalur pendaftaran default
INSERT INTO `jalur_pendaftaran` (`nama_jalur`, `kuota`) VALUES
('Prestasi', 50),
('Afirmasi', 30),
('Zonasi', 100);

-- Tabel utama untuk Calon Siswa
CREATE TABLE `calon_siswa` (
  `id_calon_siswa` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_pendaftaran` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `asal_sekolah` varchar(100) NOT NULL,
  `no_telepon` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id_jalur` int(11) NOT NULL,
  `status_pendaftaran` enum('Belum Diverifikasi','Diterima','Ditolak') NOT NULL DEFAULT 'Belum Diverifikasi',
  `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_calon_siswa`),
  UNIQUE KEY `nomor_pendaftaran` (`nomor_pendaftaran`),
  KEY `id_jalur` (`id_jalur`),
  CONSTRAINT `calon_siswa_ibfk_1` FOREIGN KEY (`id_jalur`) REFERENCES `jalur_pendaftaran` (`id_jalur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk Berkas
CREATE TABLE `berkas` (
  `id_berkas` int(11) NOT NULL AUTO_INCREMENT,
  `id_calon_siswa` int(11) NOT NULL,
  `jenis_berkas` varchar(50) NOT NULL,
  `path_berkas` varchar(255) NOT NULL,
  PRIMARY KEY (`id_berkas`),
  KEY `id_calon_siswa` (`id_calon_siswa`),
  CONSTRAINT `berkas_ibfk_1` FOREIGN KEY (`id_calon_siswa`) REFERENCES `calon_siswa` (`id_calon_siswa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk Nilai
CREATE TABLE `nilai` (
  `id_nilai` int(11) NOT NULL AUTO_INCREMENT,
  `id_calon_siswa` int(11) NOT NULL,
  `rata_rata_rapor` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id_nilai`),
  KEY `id_calon_siswa` (`id_calon_siswa`),
  CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`id_calon_siswa`) REFERENCES `calon_siswa` (`id_calon_siswa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel untuk Pengumuman
CREATE TABLE `pengumuman` (
  `id_pengumuman` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `tanggal_terbit` date NOT NULL,
  `id_admin` int(11) NOT NULL,
  PRIMARY KEY (`id_pengumuman`),
  KEY `id_admin` (`id_admin`),
  CONSTRAINT `pengumuman_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
