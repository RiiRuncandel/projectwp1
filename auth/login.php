<?php
// auth/login.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/auth_functions.php';

if (isset($_SESSION['user'])) {
    header("Location: ../auth/dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = $_POST['nim'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $user = loginStudent($nim, $password);
    
    if ($user) {
        $_SESSION['user'] = $user;
        header("Location: ../auth/dashboard.php");
        exit();
    } else {
        $error = "NIM atau password salah";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Mahasiswa - Universitas BSI</title>
    <link rel="icon" type="png" href="../img/bsi.png">
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* Simple header styles */
        .simple-header {
            background: var(--bg-color);
            padding: 20px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            border-bottom: 1px solid var(--main-color);
        }
        
        .simple-header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: center;
        }
        
        .simple-header .logo {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .simple-header .logo span {
            color: var(--main-color);
        }
        
        .simple-header .logo:hover {
            color: var(--main-color);
        }
        
        /* Auth container styles */
        .auth-section {
            margin-top: 100px;
            min-height: calc(100vh - 100px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .auth-container {
            max-width: 500px;
            width: 100%;
            padding: 40px;
            background: var(--second-bg-color);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid var(--main-color);
        }
        
        .auth-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--main-color);
            font-size: 2rem;
            font-weight: 600;
        }
        
        .auth-container form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .auth-container input {
            padding: 15px;
            border-radius: 8px;
            border: 2px solid #ddd;
            font-size: 16px;
            background: var(--bg-color);
            color: var(--text-color);
            transition: border-color 0.3s ease;
        }
        
        .auth-container input:focus {
            outline: none;
            border-color: var(--main-color);
        }
        
        .auth-container button {
            background: var(--main-color);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        
        .auth-container button:hover {
            background: var(--hover-color);
            transform: translateY(-2px);
        }
        
        .error {
            color: #ff4757;
            margin-bottom: 20px;
            text-align: center;
            background: rgba(255, 71, 87, 0.1);
            padding: 10px;
            border-radius: 5px;
        }
        
        .success {
            color: #2ed573;
            text-align: center;
            margin-bottom: 20px;
            background: rgba(46, 213, 115, 0.1);
            padding: 10px;
            border-radius: 5px;
        }
        
        .register-link, .forgot-link {
            text-align: center;
            margin-top: 15px;
        }
        
        .register-link a, .forgot-link a {
            color: var(--main-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .register-link a:hover, .forgot-link a:hover {
            color: var(--hover-color);
        }
    </style>
</head>
<body>
    <!-- Simple Header -->
    <header class="simple-header">
        <div class="container">
            <a href="../index.php" class="logo">Universitas<span> BSI</span></a>
        </div>
    </header>
    
    <section class="auth-section">
        <div class="auth-container">
            <h2>Login Mahasiswa</h2>
            
            <?php if (isset($_GET['registered'])): ?>
                <div class="success">
                    <p>Registrasi berhasil! Silakan login.</p>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['reset'])): ?>
                <div class="success">
                    <p>Password berhasil direset! Silakan login.</p>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="error">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>
            
            <form action="login.php" method="POST">
                <input type="text" name="nim" placeholder="NIM" required>
                <input type="password" name="password" placeholder="Password" required>
                
                <button type="submit">Login</button>
            </form>
            
            <div class="register-link">
                Belum punya akun? <a href="register.php">Daftar disini</a>
            </div>
            
            <div class="forgot-link">
                <a href="forgot_password.php">Lupa password?</a>
            </div>
        </div>
    </section>
</body>
</html>