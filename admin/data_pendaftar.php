<?php 
include 'partials/header.php'; 

// Logika Filter
$filter_jalur = isset($_GET['jalur']) ? (int)$_GET['jalur'] : '';
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

$where_clauses = [];
if (!empty($filter_jalur)) {
    $where_clauses[] = "c.id_jalur = $filter_jalur";
}
if (!empty($filter_status)) {
    $where_clauses[] = "c.status_pendaftaran = '" . mysqli_real_escape_string($koneksi, $filter_status) . "'";
}

$sql = "SELECT c.id_calon_siswa, c.nomor_pendaftaran, c.nama_lengkap, c.asal_sekolah, c.status_pendaftaran, j.nama_jalur, n.rata_rata_rapor 
        FROM calon_siswa c
        JOIN jalur_pendaftaran j ON c.id_jalur = j.id_jalur
        LEFT JOIN nilai n ON c.id_calon_siswa = n.id_calon_siswa";

if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}

// Logika Urutan
$sort_order = isset($_GET['sort']) ? $_GET['sort'] : 'tanggal_daftar';
if ($sort_order == 'nilai_desc') {
    $sql .= " ORDER BY n.rata_rata_rapor DESC";
} else {
    $sql .= " ORDER BY c.tanggal_daftar DESC";
}

$pendaftar_query = mysqli_query($koneksi, $sql);

?>

<h2>Data Pendaftar</h2>
<hr>

<!-- Form Filter -->
<div class="card mb-4">
    <div class="card-header">Filter Data</div>
    <div class="card-body">
        <form method="GET" action="">
            <div class="row">
                <div class="col-md-5">
                    <label for="jalur" class="form-label">Jalur Pendaftaran</label>
                    <select name="jalur" id="jalur" class="form-select">
                        <option value="">Semua Jalur</option>
                        <?php
                        $jalur_query_filter = mysqli_query($koneksi, "SELECT * FROM jalur_pendaftaran");
                        while($jalur = mysqli_fetch_assoc($jalur_query_filter)) {
                            $selected = ($filter_jalur == $jalur['id_jalur']) ? 'selected' : '';
                            echo "<option value='{$jalur['id_jalur']}' $selected>{$jalur['nama_jalur']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">Status Pendaftaran</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <?php 
                        $statuses = ['Belum Diverifikasi', 'Diterima', 'Ditolak'];
                        foreach ($statuses as $status) {
                            $selected = ($filter_status == $status) ? 'selected' : '';
                            echo "<option value='$status' $selected>$status</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="sort" class="form-label">Urutkan Berdasarkan</label>
                    <select name="sort" id="sort" class="form-select">
                        <option value="tanggal_daftar" <?= ($sort_order == 'tanggal_daftar') ? 'selected' : '' ?>>Tanggal Daftar</option>
                        <option value="nilai_desc" <?= ($sort_order == 'nilai_desc') ? 'selected' : '' ?>>Nilai Tertinggi</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data Pendaftar -->
<div class="card">
    <div class="card-header">Daftar Calon Siswa</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No. Pendaftaran</th>
                        <th>Nama Lengkap</th>
                        <th>Asal Sekolah</th>
                        <th>Jalur</th>
                        <th>Nilai Rata-rata</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($pendaftar_query) > 0): ?>
                        <?php while($pendaftar = mysqli_fetch_assoc($pendaftar_query)): ?>
                        <tr>
                            <td><?= htmlspecialchars($pendaftar['nomor_pendaftaran']) ?></td>
                            <td><?= htmlspecialchars($pendaftar['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($pendaftar['asal_sekolah']) ?></td>
                            <td><?= htmlspecialchars($pendaftar['nama_jalur']) ?></td>
                            <td><strong><?= htmlspecialchars(number_format($pendaftar['rata_rata_rapor'], 2)) ?></strong></td>
                            <td>
                                <?php 
                                    $status = $pendaftar['status_pendaftaran'];
                                    $badge_class = 'bg-warning text-dark';
                                    if ($status == 'Diterima') $badge_class = 'bg-success';
                                    if ($status == 'Ditolak') $badge_class = 'bg-danger';
                                    echo "<span class='badge $badge_class'>$status</span>";
                                ?>
                            </td>
                            <td>
                                <a href="detail_pendaftar.php?id=<?= $pendaftar['id_calon_siswa'] ?>" class="btn btn-info btn-sm">Detail & Verifikasi</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data untuk ditampilkan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>