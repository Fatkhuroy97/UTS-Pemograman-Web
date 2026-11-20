
<?php
session_start();
include "config.php";

$message = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM alat WHERE email = ?";

    $stmt = mysqli_prepare($mysqli, $sql);

    if ($stmt) {

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];

            header("Location: index.php");
            exit();

        } else {
            $message = "Email atau password salah!";
        }

        mysqli_stmt_close($stmt);

    } else {
        $message = "Terjadi kesalahan pada database!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login SIMRS</title>

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
            background: linear-gradient(135deg, #0d47a1, #1976d2, #42a5f5);
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            animation: fadeIn 0.8s ease;
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
            font-size: 25px;
            font-weight: 700;
        }

        .logo p {
            color: #888;
            font-size: 13px;
            margin-top: 5px;
        }

        .welcome {
            text-align: center;
            margin-bottom: 25px;
        }

        .welcome h3 {
            color: #333;
            font-size: 20px;
            margin-bottom: 5px;
        }

        .welcome p {
            color: #999;
            font-size: 13px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            color: #444;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .input-box {
            position: relative;
        }

        .input-box i {
            position: absolute;
            left: 15px;
            top: 50%;
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
            cursor: pointer;
            color: #999;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1565c0, #2196f3);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
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
            margin-bottom: 20px;
            text-align: center;
        }

        .register {
            text-align: center;
            margin-top: 25px;
            font-size: 13px;
            color: #888;
        }

        .register a {
            color: #1976d2;
            text-decoration: none;
            font-weight: 600;
        }

        .register a:hover {
            text-decoration: underline;
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            font-size: 11px;
            color: #aaa;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 22px;
            }
        }

    </style>
</head>

<body>

<div class="login-container">

    <div class="logo">
        <i class="fas fa-hospital"></i>
        <h2>SIMRS</h2>
        <p>Sistem Informasi Manajemen Rumah Sakit</p>
    </div>

    <div class="welcome">
        <h3>Selamat Datang!</h3>
        <p>Silakan login untuk mengakses sistem</p>
    </div>

    <?php if ($message != "") { ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php } ?>

    <form method="POST">

        <div class="input-group">
            <label>Email</label>

            <div class="input-box">
                <i class="fas fa-envelope"></i>

                <input
                    type="email"
                    name="email"
                    placeholder="Masukkan email Anda"
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
                    placeholder="Masukkan password"
                    required
                >

                <span class="toggle-password" onclick="togglePassword()">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </span>
            </div>
        </div>

        <button type="submit" name="login" class="btn-login">
            <i class="fas fa-sign-in-alt"></i>
            Login Sekarang
        </button>

    </form>

    <div class="register">
        Belum punya akun?
        <a href="register.php">Daftar Sekarang</a>
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
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    } else {
        password.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    }

}

</script>

</body>
</html>
