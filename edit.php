
<?php
include_once("config.php");

$message = "";

// Mengambil ID dari URL
if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header("Location: index.php");
    exit();
}

// PROSES UPDATE
if (isset($_POST['update'])) {

    $id = (int)$_POST['id'];
    $nama_alat = trim($_POST['nama_alat']);
    $tahun = trim($_POST['tahun']);
    $merek = trim($_POST['merek']);
    $lokasi = trim($_POST['lokasi']);

    if ($nama_alat == "" || $tahun == "" || $merek == "" || $lokasi == "") {

        $message = "Semua kolom wajib diisi!";

    } else {

        $sql = "UPDATE alat SET
                nama_alat = ?,
                tahun = ?,
                merek = ?,
                lokasi = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($mysqli, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssssi",
            $nama_alat,
            $tahun,
            $merek,
            $lokasi,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {

            header("Location: index.php");
            exit();

        } else {
            $message = "Data gagal diperbarui!";
        }

        mysqli_stmt_close($stmt);
    }

} else {

    $id = (int)$_GET['id'];

    // Mengambil data berdasarkan ID
    $sql = "SELECT * FROM alat WHERE id = ?";

    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user_data = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$user_data) {
        header("Location: index.php");
        exit();
    }

    $nama_alat = $user_data['nama_alat'];
    $tahun = $user_data['tahun'];
    $merek = $user_data['merek'];
    $lokasi = $user_data['lokasi'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Data Alat - SIMRS</title>

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
    min-height: 100vh;
    background: #f4f7fc;
    color: #333;
}

/* SIDEBAR */

.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: linear-gradient(180deg, #0d47a1, #1976d2);
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
}

.logo p {
    font-size: 10px;
    opacity: .8;
}

.menu-title {
    font-size: 11px;
    opacity: .6;
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
    transition: .3s;
}

.menu a:hover,
.menu a.active {
    background: rgba(255,255,255,.2);
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
    box-shadow: 0 3px 15px rgba(0,0,0,.04);
    margin-bottom: 30px;
}

.topbar h3 {
    color: #1565c0;
    font-size: 18px;
}

/* CONTENT */

.page-title {
    margin-bottom: 25px;
}

.page-title h2 {
    color: #263238;
    font-size: 23px;
}

.page-title p {
    color: #999;
    font-size: 13px;
    margin-top: 5px;
}

/* FORM CARD */

.form-card {
    background: white;
    max-width: 850px;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 3px 15px rgba(0,0,0,.04);
}

.form-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 28px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.form-icon {
    width: 55px;
    height: 55px;
    background: #e3f2fd;
    color: #1976d2;
    border-radius: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 23px;
}

.form-header h3 {
    font-size: 17px;
    color: #263238;
}

.form-header p {
    color: #999;
    font-size: 12px;
    margin-top: 4px;
}

/* INPUT */

.form-group {
    margin-bottom: 22px;
}

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #444;
    margin-bottom: 9px;
}

.input-box {
    position: relative;
}

.input-box i {
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    color: #1976d2;
}

.input-box input {
    width: 100%;
    padding: 13px 15px 13px 45px;
    border: 1px solid #ddd;
    border-radius: 9px;
    outline: none;
    font-size: 13px;
    transition: .3s;
}

.input-box input:focus {
    border-color: #1976d2;
    box-shadow: 0 0 0 3px rgba(25,118,210,.1);
}

/* BUTTON */

.button-group {
    display: flex;
    gap: 12px;
    margin-top: 30px;
    flex-wrap: wrap;
}

.btn-update {
    padding: 12px 22px;
    background: #1976d2;
    color: white;
    border: none;
    border-radius: 9px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: .3s;
}

.btn-update:hover {
    background: #0d47a1;
    transform: translateY(-2px);
}

.btn-back {
    padding: 12px 22px;
    background: #f1f3f5;
    color: #555;
    text-decoration: none;
    border-radius: 9px;
    font-size: 13px;
    transition: .3s;
}

.btn-back:hover {
    background: #e0e0e0;
}

/* MESSAGE */

.error-message {
    background: #ffebee;
    color: #c62828;
    padding: 13px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 12px;
}

/* FOOTER */

.footer {
    margin-top: 30px;
    text-align: center;
    color: #aaa;
    font-size: 11px;
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
}

@media(max-width: 600px) {

    .main {
        padding: 12px;
    }

    .form-card {
        padding: 20px;
    }

    .topbar {
        padding: 15px;
    }

    .page-title h2 {
        font-size: 19px;
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

        <a href="index.php">
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

    <div class="menu-title">AKUN</div>

    <div class="menu">

        <a href="logout.php">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>

    </div>

</div>


<!-- MAIN CONTENT -->

<div class="main">

    <div class="topbar">
        <h3>Dashboard SIMRS</h3>

        <div>
            <i class="fas fa-user-circle" style="color:#1976d2;font-size:28px;"></i>
        </div>
    </div>


    <div class="page-title">

        <h2>Edit Data Alat Elektromedis</h2>

        <p>Perbarui informasi inventaris alat elektromedis rumah sakit</p>

    </div>


    <!-- FORM CARD -->

    <div class="form-card">

        <div class="form-header">

            <div class="form-icon">
                <i class="fas fa-edit"></i>
            </div>

            <div>
                <h3>Form Edit Alat</h3>
                <p>Silakan ubah informasi alat di bawah ini</p>
            </div>

        </div>


        <?php if ($message != "") { ?>

            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>


        <form method="POST" action="edit.php">

            <input type="hidden" name="id" value="<?php echo (int)$id; ?>">

            <div class="form-group">

                <label>Nama Alat Elektromedis</label>

                <div class="input-box">
                    <i class="fas fa-tools"></i>

                    <input
                        type="text"
                        name="nama_alat"
                        value="<?php echo htmlspecialchars($nama_alat); ?>"
                        placeholder="Masukkan nama alat"
                        required
                    >
                </div>

            </div>


            <div class="form-group">

                <label>Tahun</label>

                <div class="input-box">
                    <i class="fas fa-calendar-alt"></i>

                    <input
                        type="text"
                        name="tahun"
                        value="<?php echo htmlspecialchars($tahun); ?>"
                        placeholder="Masukkan tahun"
                        required
                    >
                </div>

            </div>


            <div class="form-group">

                <label>Merek</label>

                <div class="input-box">
                    <i class="fas fa-tag"></i>

                    <input
                        type="text"
                        name="merek"
                        value="<?php echo htmlspecialchars($merek); ?>"
                        placeholder="Masukkan merek alat"
                        required
                    >
                </div>

            </div>


            <div class="form-group">

                <label>Lokasi</label>

                <div class="input-box">
                    <i class="fas fa-map-marker-alt"></i>

                    <input
                        type="text"
                        name="lokasi"
                        value="<?php echo htmlspecialchars($lokasi); ?>"
                        placeholder="Masukkan lokasi alat"
                        required
                    >
                </div>

            </div>


            <div class="button-group">

                <button type="submit" name="update" class="btn-update">
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
                </button>

                <a href="index.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>

            </div>

        </form>

    </div>


    <div class="footer">
        &copy; <?php echo date("Y"); ?> SIMRS - Sistem Informasi Manajemen Rumah Sakit
    </div>

</div>

</body>
</html>
