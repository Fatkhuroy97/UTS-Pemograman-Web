
<?php
include "config.php";

$message = "";
$messageType = "";

if (isset($_POST['register'])) {

    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($nama == "" || $email == "" || $password == "") {

        $message = "Semua kolom wajib diisi!";
        $messageType = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Format email tidak valid!";
        $messageType = "error";

    } elseif (strlen($password) < 6) {

        $message = "Password minimal 6 karakter!";
        $messageType = "error";

    } else {

        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $sql = "INSERT INTO alat (nama, email, password)
                VALUES (?, ?, ?)";

        $stmt = mysqli_prepare($mysqli, $sql);

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $nama,
                $email,
                $hashed_password
            );

            if (mysqli_stmt_execute($stmt)) {

                header("Location: login.php");
                exit();

            } else {

                if (mysqli_stmt_errno($stmt) == 1062) {
                    $message = "Email sudah terdaftar!";
                } else {
                    $message = "Registrasi gagal! Silakan coba lagi.";
                }

                $messageType = "error";
            }

            mysqli_stmt_close($stmt);

        } else {
            $message = "Terjadi kesalahan pada database!";
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register SIMRS</title>

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
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 25px 15px;
    background: linear-gradient(135deg, #0d47a1, #1976d2, #42a5f5);
}

.register-container {
    width: 100%;
    max-width: 450px;
    background: #fff;
    padding: 40px;
    border-radius: 22px;
    box-shadow: 0 15px 45px rgba(0,0,0,0.2);
    animation: fadeIn 0.7s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.logo {
    text-align: center;
    margin-bottom: 25px;
}

.logo i {
    font-size: 55px;
    color: #1976d2;
    margin-bottom: 10px;
}

.logo h2 {
    color: #1565c0;
    font-size: 27px;
    font-weight: 700;
}

.logo p {
    color: #999;
    font-size: 12px;
    margin-top: 5px;
}

.welcome {
    text-align: center;
    margin-bottom: 25px;
}

.welcome h3 {
    color: #333;
    font-size: 20px;
}

.welcome p {
    color: #999;
    font-size: 12px;
    margin-top: 5px;
}

.input-group {
    margin-bottom: 19px;
}

.input-group label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #444;
    margin-bottom: 8px;
}

.input-box {
    position: relative;
}

.input-box > i {
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    color: #1976d2;
}

.input-box input {
    width: 100%;
    padding: 13px 45px;
    border: 1px solid #ddd;
    border-radius: 10px;
    outline: none;
    font-size: 13px;
    transition: 0.3s;
}

.input-box input:focus {
    border-color: #1976d2;
    box-shadow: 0 0 0 3px rgba(25,118,210,0.1);
}

.toggle-password {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    cursor: pointer;
}

.password-strength {
    margin-top: 8px;
    height: 5px;
    background: #eee;
    border-radius: 10px;
    overflow: hidden;
}

.password-strength span {
    display: block;
    width: 0;
    height: 100%;
    transition: 0.3s;
}

.strength-text {
    font-size: 11px;
    color: #999;
    margin-top: 5px;
}

.btn-register {
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 10px;
    background: linear-gradient(135deg, #1565c0, #2196f3);
    color: white;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
    margin-top: 8px;
}

.btn-register:hover {
    background: linear-gradient(135deg, #0d47a1, #1976d2);
    transform: translateY(-2px);
    box-shadow: 0 7px 15px rgba(25,118,210,0.3);
}

.error-message {
    background: #ffebee;
    color: #c62828;
    padding: 12px;
    border-radius: 8px;
    font-size: 12px;
    text-align: center;
    margin-bottom: 18px;
}

.login-link {
    text-align: center;
    margin-top: 23px;
    font-size: 13px;
    color: #888;
}

.login-link a {
    color: #1976d2;
    font-weight: 600;
    text-decoration: none;
}

.login-link a:hover {
    text-decoration: underline;
}

.footer {
    text-align: center;
    margin-top: 25px;
    color: #aaa;
    font-size: 11px;
}

@media(max-width: 480px) {
    .register-container {
        padding: 30px 22px;
    }
}

</style>
</head>

<body>

<div class="register-container">

    <div class="logo">
        <i class="fas fa-hospital"></i>
        <h2>SIMRS</h2>
        <p>Sistem Informasi Manajemen Rumah Sakit</p>
    </div>

    <div class="welcome">
        <h3>Buat Akun Baru</h3>
        <p>Silakan lengkapi data untuk mendaftar</p>
    </div>

    <?php if ($message != "") { ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php } ?>

    <form method="POST">

        <div class="input-group">

            <label>Nama Lengkap</label>

            <div class="input-box">
                <i class="fas fa-user"></i>

                <input
                    type="text"
                    name="nama"
                    placeholder="Masukkan nama lengkap"
                    value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>"
                    required
                >
            </div>

        </div>

        <div class="input-group">

            <label>Email</label>

            <div class="input-box">
                <i class="fas fa-envelope"></i>

                <input
                    type="email"
                    name="email"
                    placeholder="Masukkan email"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                    required
                >
            </div>

        </div>

        <div class="input-group">

            <label>Password</label>

            <div class="input-box">
                <i class="fas fa-lock"></i>

                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Minimal 6 karakter"
                    minlength="6"
                    required
                >

                <span class="toggle-password" onclick="togglePassword()">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </span>
            </div>

            <div class="password-strength">
                <span id="strengthBar"></span>
            </div>

            <p class="strength-text" id="strengthText">
                Kekuatan password
            </p>

        </div>

        <button type="submit" name="register" class="btn-register">
            <i class="fas fa-user-plus"></i>
            Daftar Sekarang
        </button>

    </form>

    <div class="login-link">
        Sudah punya akun?
        <a href="login.php">Login di sini</a>
    </div>

    <div class="footer">
        &copy; 2026 SIMRS | Sistem Informasi Rumah Sakit
    </div>

</div>

<script>

function togglePassword() {

    const password = document.getElementById("password");
    const eyeIcon = document.getElementById("eyeIcon");

    if (password.type === "password") {

        password.type = "text";
        eyeIcon.classList.replace("fa-eye", "fa-eye-slash");

    } else {

        password.type = "password";
        eyeIcon.classList.replace("fa-eye-slash", "fa-eye");

    }
}

const password = document.getElementById("password");
const strengthBar = document.getElementById("strengthBar");
const strengthText = document.getElementById("strengthText");

password.addEventListener("input", function() {

    let value = password.value;
    let strength = 0;

    if (value.length >= 6) strength++;
    if (value.length >= 10) strength++;
    if (/[A-Z]/.test(value) && /[a-z]/.test(value)) strength++;
    if (/[0-9]/.test(value)) strength++;
    if (/[^A-Za-z0-9]/.test(value)) strength++;

    let colors = ["#ddd", "#ef5350", "#ff9800", "#2196f3", "#4caf50", "#2e7d32"];

    let labels = [
        "Kekuatan password",
        "Sangat lemah",
        "Lemah",
        "Sedang",
        "Kuat",
        "Sangat kuat"
    ];

    strengthBar.style.width = (strength * 20) + "%";
    strengthBar.style.background = colors[strength];
    strengthText.textContent = labels[strength];

});

</script>

</body>
</html>
