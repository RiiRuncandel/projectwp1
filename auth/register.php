<?php
// auth/register.php
require_once __DIR__ . '/auth_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $nim = trim($_POST['nim'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_telp = trim($_POST['no_telp'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Comprehensive validation
    $errors = [];
    
    if (empty($nama)) $errors[] = "Nama harus diisi";
    if (empty($nim)) $errors[] = "NIM harus diisi";
    if (empty($kelas)) $errors[] = "Kelas harus diisi";
    if (empty($email)) $errors[] = "Email harus diisi";
    if (empty($no_telp)) $errors[] = "Nomor telepon harus diisi";
    if (empty($password)) $errors[] = "Password harus diisi";
    if (strlen($password) < 6) $errors[] = "Password minimal 6 karakter";
    if ($password !== $confirm_password) $errors[] = "Password tidak cocok";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format email tidak valid";
    
    if (empty($errors)) {
        // Test database connection (hidden from users)
        $connectionTest = testConnection();
        if (!$connectionTest['success']) {
            $errors[] = "Terjadi kesalahan sistem. Silakan coba lagi.";
            // Log the actual error for admin debugging
            error_log("Database connection failed: " . $connectionTest['message']);
        } else {
            $result = registerStudent($nama, $nim, $kelas, $email, $no_telp, $password);
            
            if ($result['success']) {
                header("Location: login.php?registered=1");
                exit();
            } else {
                $errors[] = $result['message'];
            }
        }
    }
}

// Hidden debug function - only show if debug parameter is set
$showDebug = isset($_GET['debug']) && $_GET['debug'] === 'true';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Mahasiswa - Universitas BSI</title>
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
            background: rgba(255, 71, 87, 0.1);
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #ff4757;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-link a {
            color: var(--main-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .login-link a:hover {
            color: var(--hover-color);
        }
        
        .debug-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 12px;
            color: #6c757d;
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
            <h2>Registrasi Mahasiswa</h2>
            
            <!-- Debug info (only shown with ?debug=true parameter) -->
            <?php if ($showDebug): ?>
                <div class="debug-info">
                    <strong>Debug Info:</strong><br>
                    <?php 
                    $connectionTest = testConnection();
                    echo "Database: " . ($connectionTest['success'] ? 'Connected ✓' : 'Failed ✗') . "<br>";
                    if (!$connectionTest['success']) {
                        echo "Error: " . $connectionTest['message'] . "<br>";
                    }
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form action="register.php" method="POST">
                <input type="text" name="nama" placeholder="Nama Lengkap" value="<?php echo htmlspecialchars($nama ?? ''); ?>" required>
                <input type="text" name="nim" placeholder="NIM" value="<?php echo htmlspecialchars($nim ?? ''); ?>" required>
                <input type="text" name="kelas" placeholder="Kelas (contoh: 16.4A.31)" value="<?php echo htmlspecialchars($kelas ?? ''); ?>" required>
                <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                <input type="tel" name="no_telp" placeholder="Nomor Telepon" value="<?php echo htmlspecialchars($no_telp ?? ''); ?>" required>
                <input type="password" name="password" placeholder="Password (minimal 6 karakter)" required>
                <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
                
                <button type="submit">Daftar</button>
            </form>
            
            <div class="login-link">
                Sudah punya akun? <a href="login.php">Login disini</a>
            </div>
        </div>
    </section>
</body>
</html>