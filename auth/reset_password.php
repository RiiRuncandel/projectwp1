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
    <link rel="stylesheet" href="../style.css">
    <style>
        .auth-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: var(--second-bg-color);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .auth-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--main-color);
        }
        
        .auth-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .auth-container input {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .auth-container button {
            background: var(--main-color);
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .auth-container button:hover {
            opacity: 0.9;
        }
        
        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
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