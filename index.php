
<?php
session_start();
include_once("config.php");

// Mengambil data alat
$result = mysqli_query($mysqli, "SELECT * FROM alat ORDER BY id DESC");

// Menghitung jumlah alat
$totalQuery = mysqli_query($mysqli, "SELECT COUNT(*) AS total FROM alat");
$totalData = mysqli_fetch_assoc($totalQuery);
$totalAlat = $totalData['total'];

// Menghitung jumlah lokasi
$lokasiQuery = mysqli_query($mysqli, "SELECT COUNT(DISTINCT lokasi) AS total FROM alat");
$totalLokasi = mysqli_fetch_assoc($lokasiQuery);
$jumlahLokasi = $totalLokasi['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard SIMRS</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #f4f7fc;
    color: #333;
}

/* SIDEBAR */

.sidebar {
    width: 250px;
    height: 100vh;
    background: linear-gradient(180deg, #0d47a1, #1976d2);
    position: fixed;
    left: 0;
    top: 0;
    padding: 25px 18px;
    color: white;
}

.logo {
    text-align: center;
    margin-bottom: 40px;
}

.logo i {
    font-size: 40px;
    margin-bottom: 10px;
}

.logo h2 {
    font-size: 23px;
    letter-spacing: 1px;
}

.logo p {
    font-size: 10px;
    opacity: 0.8;
}

.menu-title {
    font-size: 11px;
    opacity: 0.6;
    margin: 25px 10px 12px;
}

.menu a {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 13px 15px;
    color: white;
    text-decoration: none;
    border-radius: 9px;
    margin-bottom: 8px;
    font-size: 13px;
    transition: 0.3s;
}

.menu a:hover,
.menu a.active {
    background: rgba(255,255,255,0.2);
}

.menu a i {
    width: 20px;
}

/* MAIN */

.main {
    margin-left: 250px;
    padding: 25px 35px;
}

/* TOPBAR */

.topbar {
    background: white;
    padding: 17px 25px;
    border-radius: 13px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 3px 15px rgba(0,0,0,0.04);
    margin-bottom: 30px;
}

.topbar h3 {
    color: #1565c0;
    font-size: 18px;
}

.admin {
    display: flex;
    align-items: center;
    gap: 12px;
}

.admin-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e3f2fd;
    color: #1976d2;
    display: flex;
    align-items: center;
    justify-content: center;
}

.admin p {
    font-size: 12px;
    font-weight: 600;
}

.admin small {
    color: #999;
}

/* WELCOME */

.welcome {
    margin-bottom: 25px;
}

.welcome h2 {
    font-size: 23px;
    color: #263238;
}

.welcome p {
    color: #999;
    font-size: 13px;
    margin-top: 5px;
}

/* STATISTIC */

.stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 22px;
    border-radius: 14px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.04);
    display: flex;
    align-items: center;
    gap: 17px;
}

.stat-icon {
    width: 55px;
    height: 55px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

.blue {
    background: #e3f2fd;
    color: #1976d2;
}

.green {
    background: #e8f5e9;
    color: #43a047;
}

.orange {
    background: #fff3e0;
    color: #fb8c00;
}

.stat-card h3 {
    font-size: 22px;
    color: #263238;
}

.stat-card p {
    font-size: 12px;
    color: #999;
}

/* TABLE CARD */

.table-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.04);
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 25px;
}

.table-header h3 {
    color: #263238;
    font-size: 17px;
}

.table-header p {
    color: #999;
    font-size: 12px;
    margin-top: 4px;
}

.btn-add {
    background: #1976d2;
    color: white;
    text-decoration: none;
    padding: 11px 17px;
    border-radius: 9px;
    font-size: 12px;
    transition: 0.3s;
}

.btn-add:hover {
    background: #0d47a1;
}

.search-box {
    margin-bottom: 18px;
    position: relative;
    max-width: 300px;
}

.search-box i {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
}

.search-box input {
    width: 100%;
    padding: 11px 12px 11px 38px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    outline: none;
    font-size: 12px;
}

.search-box input:focus {
    border-color: #1976d2;
}

.table-responsive {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 650px;
}

thead {
    background: #eaf3fc;
}

th {
    padding: 15px;
    text-align: left;
    color: #1565c0;
    font-size: 12px;
    font-weight: 600;
}

td {
    padding: 14px 15px;
    border-bottom: 1px solid #eee;
    font-size: 12px;
    color: #555;
}

tbody tr:hover {
    background: #f7fbff;
}

.number {
    color: #1976d2;
    font-weight: 600;
}

.action {
    display: flex;
    gap: 7px;
}

.btn-edit,
.btn-delete {
    padding: 7px 10px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 11px;
}

.btn-edit {
    background: #e3f2fd;
    color: #1976d2;
}

.btn-delete {
    background: #ffebee;
    color: #e53935;
}

.btn-edit:hover {
    background: #bbdefb;
}

.btn-delete:hover {
    background: #ffcdd2;
}

.empty {
    text-align: center;
    padding: 30px;
    color: #999;
}

/* FOOTER */

.footer {
    text-align: center;
    color: #aaa;
    font-size: 11px;
    margin-top: 30px;
}

/* RESPONSIVE */

@media(max-width: 900px) {

    .sidebar {
        width: 75px;
        padding: 20px 10px;
    }

    .logo h2,
    .logo p,
    .menu-title,
    .menu a span {
        display: none;
    }

    .logo i {
        font-size: 30px;
    }

    .menu a {
        justify-content: center;
        padding: 14px 5px;
    }

    .main {
        margin-left: 75px;
        padding: 20px;
    }

    .stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width: 600px) {

    .main {
        padding: 12px;
    }

    .stats {
        grid-template-columns: 1fr;
    }

    .topbar {
        padding: 15px;
    }

    .welcome h2 {
        font-size: 19px;
    }

    .table-card {
        padding: 15px;
    }

}

</style>
</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

    <div class="logo">
        <i class="fas fa-hospital"></i>
        <h2>SIMRS</h2>
        <p>Sistem Informasi Rumah Sakit</p>
    </div>

    <div class="menu-title">MENU UTAMA</div>

    <div class="menu">

        <a href="index.php" class="active">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>

        <a href="index.php">
            <i class="fas fa-tools"></i>
            <span>Data Alat</span>
        </a>

        <a href="add.php">
            <i class="fas fa-plus-circle"></i>
            <span>Tambah Alat</span>
        </a>

    </div>

    <!-- <div class="menu-title">AKUN</div> -->

    <!-- <div class="menu">
        <a href="logout.php">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div> -->

</div>


<!-- MAIN CONTENT -->

<div class="main">

    <!-- TOPBAR -->

    <div class="topbar">

        <h3>Dashboard SIMRS</h3>

        <div class="admin">

            <div class="admin-icon">
                <i class="fas fa-user"></i>
            </div>

            <div>
                <p><?php echo isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Administrator'; ?></p>
                <small>Administrator</small>
            </div>

        </div>

    </div>


    <!-- WELCOME -->

    <div class="welcome">

        <h2>Data Alat Elektromedis</h2>

        <p>Selamat datang di Sistem Informasi Manajemen Rumah Sakit</p>

    </div>


    <!-- STATISTICS -->

    <div class="stats">

        <div class="stat-card">

            <div class="stat-icon blue">
                <i class="fas fa-tools"></i>
            </div>

            <div>
                <h3><?php echo $totalAlat; ?></h3>
                <p>Total Alat Elektromedis</p>
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon green">
                <i class="fas fa-hospital"></i>
            </div>

            <div>
                <h3><?php echo $jumlahLokasi; ?></h3>
                <p>Jumlah Lokasi</p>
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon orange">
                <i class="fas fa-calendar-alt"></i>
            </div>

            <div>
                <h3><?php echo date("Y"); ?></h3>
                <p>Tahun Berjalan</p>
            </div>

        </div>

    </div>


    <!-- TABLE -->

    <div class="table-card">

        <div class="table-header">

            <div>
                <h3><i class="fas fa-list"></i> Daftar Alat Elektromedis</h3>
                <p>Kelola data inventaris alat elektromedis rumah sakit</p>
            </div>

            <a href="add.php" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Alat
            </a>

        </div>


        <!-- SEARCH -->

        <div class="search-box">

            <i class="fas fa-search"></i>

            <input
                type="text"
                id="searchInput"
                placeholder="Cari nama alat, merek, lokasi..."
            >

        </div>


        <!-- TABLE -->

        <div class="table-responsive">

            <table id="alatTable">

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Nama Alat</th>
                        <th>Tahun</th>
                        <th>Merek</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                <?php
                $i = 1;

                if ($result && mysqli_num_rows($result) > 0) {

                    while ($user_data = mysqli_fetch_assoc($result)) {

                        $id = (int)$user_data['id'];

                        echo "<tr>";

                        echo "<td class='number'>" . $i . "</td>";

                        echo "<td>" . htmlspecialchars($user_data['nama_alat']) . "</td>";

                        echo "<td>" . htmlspecialchars($user_data['tahun']) . "</td>";

                        echo "<td>" . htmlspecialchars($user_data['merek']) . "</td>";

                        echo "<td>" . htmlspecialchars($user_data['lokasi']) . "</td>";

                        echo "<td>
                            <div class='action'>
                                <a class='btn-edit' href='edit.php?id=$id'>
                                    <i class='fas fa-edit'></i> Edit
                                </a>

                                <a class='btn-delete' href='delete.php?id=$id'
                                onclick=\"return confirm('Apakah Anda yakin ingin menghapus data ini?')\">
                                    <i class='fas fa-trash'></i> Hapus
                                </a>
                            </div>
                        </td>";

                        echo "</tr>";

                        $i++;
                    }

                } else {

                    echo "<tr>
                        <td colspan='6' class='empty'>
                            <i class='fas fa-folder-open'></i><br><br>
                            Belum ada data alat elektromedis
                        </td>
                    </tr>";

                }
                ?>

                </tbody>

            </table>

        </div>

    </div>


    <!-- FOOTER -->

    <div class="footer">
        &copy; <?php echo date("Y"); ?> SIMRS - Sistem Informasi Manajemen Rumah Sakit
    </div>

</div>


<!-- SEARCH SCRIPT -->

<script>

document.getElementById("searchInput").addEventListener("keyup", function() {

    let filter = this.value.toLowerCase();

    let rows = document.querySelectorAll("#alatTable tbody tr");

    rows.forEach(function(row) {

        let text = row.innerText.toLowerCase();

        row.style.display = text.includes(filter) ? "" : "none";

    });

});

</script>

</body>
</html>
