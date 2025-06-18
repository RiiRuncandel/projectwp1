<?php
// auth/reset_password.php
require_once __DIR__ . '/auth_functions.php';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($new_password) || empty($confirm_password)) {
        $error = "Password baru dan konfirmasi password harus diisi";
    } elseif ($new_password !== $confirm_password) {
        $error = "Password tidak cocok";
    } else {
        if (resetPassword($token, $new_password)) {
            header("Location: login.php?reset=1");
            exit();
        } else {
            $error = "Token tidak valid atau sudah kadaluarsa";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Universitas BSI</title>
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
            <h2>Reset Password</h2>
            
            <?php if (isset($error)): ?>
                <div class="error">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>
            
            <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
                <input type="password" name="new_password" placeholder="Password Baru" required>
                <input type="password" name="confirm_password" placeholder="Konfirmasi Password Baru" required>
                
                <button type="submit">Reset Password</button>
            </form>
            
            <div class="login-link">
                <a href="login.php">Kembali ke login</a>
            </div>
        </div>
    </section>
</body>
</html>