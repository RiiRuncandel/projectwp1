<?php
// auth/dashboard.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/auth_functions.php';

$user = $_SESSION['user'];
$success_message = '';
$error_message = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $nama = trim($_POST['nama'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_telp = trim($_POST['no_telp'] ?? '');
    
    $errors = [];
    
    if (empty($nama)) $errors[] = "Nama harus diisi";
    if (empty($kelas)) $errors[] = "Kelas harus diisi";
    if (empty($email)) $errors[] = "Email harus diisi";
    if (empty($no_telp)) $errors[] = "Nomor telepon harus diisi";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format email tidak valid";
    
    if (empty($errors)) {
        if (updateProfile($user['id'], $nama, $kelas, $email, $no_telp)) {
            // Update session data
            $_SESSION['user']['nama'] = $nama;
            $_SESSION['user']['kelas'] = $kelas;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['no_telp'] = $no_telp;
            $user = $_SESSION['user'];
            $success_message = "Profile berhasil diperbarui!";
        } else {
            $error_message = "Gagal memperbarui profile. Silakan coba lagi.";
        }
    } else {
        $error_message = implode('<br>', $errors);
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    $errors = [];
    
    if (empty($current_password)) $errors[] = "Password lama harus diisi";
    if (empty($new_password)) $errors[] = "Password baru harus diisi";
    if (strlen($new_password) < 6) $errors[] = "Password baru minimal 6 karakter";
    if ($new_password !== $confirm_password) $errors[] = "Konfirmasi password tidak cocok";
    
    if (empty($errors)) {
        if (password_verify($current_password, $user['password'])) {
            if (changePassword($user['id'], $new_password)) {
                $success_message = "Password berhasil diubah!";
            } else {
                $error_message = "Gagal mengubah password. Silakan coba lagi.";
            }
        } else {
            $error_message = "Password lama tidak benar";
        }
    } else {
        $error_message = implode('<br>', $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Universitas BSI</title>
    <link rel="icon" type="png" href="../img/bsi.png">
    <link rel="stylesheet" href="../style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* Dashboard specific styles */
        .dashboard-header {
            background: var(--bg-color);
            padding: 20px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            border-bottom: 1px solid var(--main-color);
        }
        
        .dashboard-header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .dashboard-header .logo {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .dashboard-header .logo span {
            color: var(--main-color);
        }
        
        .dashboard-header .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .dashboard-header .user-name {
            color: var(--text-color);
            font-weight: 500;
        }
        
        .dashboard-header .nav-buttons {
            display: flex;
            gap: 10px;
        }
        
        .dashboard-header .nav-btn {
            background: var(--main-color);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .dashboard-header .nav-btn:hover {
            background: var(--hover-color);
            transform: translateY(-2px);
        }
        
        .dashboard-header .nav-btn.secondary {
            background: #6c757d;
        }
        
        .dashboard-header .nav-btn.secondary:hover {
            background: #5a6268;
        }
        
        .dashboard-section {
            margin-top: 100px;
            padding: 40px 20px;
            min-height: calc(100vh - 100px);
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .dashboard-title {
            text-align: center;
            margin-bottom: 40px;
            color: var(--main-color);
            font-size: 2.5rem;
            font-weight: 600;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .dashboard-card {
            background: var(--second-bg-color);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid var(--main-color);
        }
        
        .dashboard-card h3 {
            color: var(--main-color);
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .profile-info {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--text-color);
        }
        
        .info-value {
            color: var(--text-color);
        }
        
        .dashboard-card form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .dashboard-card input {
            padding: 12px;
            border-radius: 8px;
            border: 2px solid #ddd;
            font-size: 14px;
            background: var(--bg-color);
            color: var(--text-color);
            transition: border-color 0.3s ease;
        }
        
        .dashboard-card input:focus {
            outline: none;
            border-color: var(--main-color);
        }
        
        .dashboard-card button {
            background: var(--main-color);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .dashboard-card button:hover {
            background: var(--hover-color);
            transform: translateY(-2px);
        }
        
        .success {
            color: #2ed573;
            background: rgba(46, 213, 115, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #2ed573;
        }
        
        .error {
            color: #ff4757;
            background: rgba(255, 71, 87, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #ff4757;
        }
        
        .profile-management {
            background: var(--second-bg-color);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid var(--main-color);
        }
        
        .profile-management h3 {
            color: var(--main-color);
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
        }
        
        .management-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .mgmt-btn {
            background: var(--main-color);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .mgmt-btn:hover {
            background: var(--hover-color);
            transform: translateY(-2px);
        }
        
        .mgmt-btn.view {
            background: #3742fa;
        }
        
        .mgmt-btn.view:hover {
            background: #2f3542;
        }
        
        .mgmt-btn.edit {
            background: #ffa502;
        }
        
        .mgmt-btn.edit:hover {
            background: #ff7675;
        }
        
        .mgmt-btn.password {
            background: #ff6348;
        }
        
        .mgmt-btn.password:hover {
            background: #ff4757;
        }
        
        .mgmt-btn.home {
            background: #2ed573;
        }
        
        .mgmt-btn.home:hover {
            background: #26d0ce;
        }
        
        .mgmt-btn.logout {
            background: #6c757d;
        }
        
        .mgmt-btn.logout:hover {
            background: #5a6268;
        }
        
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .dashboard-header .container {
                flex-direction: column;
                gap: 10px;
            }
            
            .dashboard-header .nav-buttons {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .management-buttons {
                grid-template-columns: 1fr;
            }
        }
        
        /* Hide sections by default */
        .section-content {
            display: none;
        }
        
        .section-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Dashboard Header -->
    <header class="dashboard-header">
        <div class="container">
            <a href="../index.php" class="logo">Universitas<span> BSI</span></a>
            <div class="user-info">
                <span class="user-name">Halo, <?php echo htmlspecialchars($user['nama']); ?>!</span>
                <div class="nav-buttons">
                    <a href="../index.php" class="nav-btn">
                        <i class="bx bx-home"></i> Beranda
                    </a>
                    <a href="logout.php" class="nav-btn secondary">
                        <i class="bx bx-log-out"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <section class="dashboard-section">
        <div class="dashboard-container">
            <h1 class="dashboard-title">Dashboard Mahasiswa</h1>
            
            <!-- Profile Management Menu -->
            <div class="profile-management">
                <h3><i class="fas fa-user-cog"></i> Manajemen Profile</h3>
                <div class="management-buttons">
                    <a href="#" class="mgmt-btn view" onclick="showSection('view')">
                        <i class="fas fa-eye"></i> Lihat Profile
                    </a>
                    <a href="#" class="mgmt-btn edit" onclick="showSection('edit')">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                    <a href="#" class="mgmt-btn password" onclick="showSection('password')">
                        <i class="fas fa-key"></i> Ubah Password
                    </a>
                    <a href="../index.php" class="mgmt-btn home">
                        <i class="bx bx-home"></i> Kembali ke Beranda
                    </a>
                    <a href="logout.php" class="mgmt-btn logout">
                        <i class="bx bx-log-out"></i> Logout
                    </a>
                </div>
            </div>
            
            <?php if ($success_message): ?>
                <div class="success">
                    <p><?php echo $success_message; ?></p>
                </div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="error">
                    <p><?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>
            
            <!-- View Profile Section -->
            <div id="view-section" class="section-content active">
                <div class="dashboard-card" style="max-width: 600px; margin: 0 auto;">
                    <h3><i class="fas fa-user"></i> Informasi Profile</h3>
                    <div class="profile-info">
                        <div class="info-item">
                            <span class="info-label">Nama:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['nama']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">NIM:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['nim']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kelas:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['kelas']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">No. Telepon:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['no_telp']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Edit Profile Section -->
            <div id="edit-section" class="section-content">
                <div class="dashboard-card" style="max-width: 600px; margin: 0 auto;">
                    <h3><i class="fas fa-edit"></i> Update Profile</h3>
                    <form method="POST">
                        <input type="text" name="nama" placeholder="Nama Lengkap" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                        <input type="text" name="kelas" placeholder="Kelas" value="<?php echo htmlspecialchars($user['kelas']); ?>" required>
                        <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        <input type="tel" name="no_telp" placeholder="Nomor Telepon" value="<?php echo htmlspecialchars($user['no_telp']); ?>" required>
                        <button type="submit" name="update_profile">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Change Password Section -->
            <div id="password-section" class="section-content">
                <div class="dashboard-card" style="max-width: 600px; margin: 0 auto;">
                    <h3><i class="fas fa-lock"></i> Ubah Password</h3>
                    <form method="POST">
                        <input type="password" name="current_password" placeholder="Password Lama" required>
                        <input type="password" name="new_password" placeholder="Password Baru (minimal 6 karakter)" required>
                        <input type="password" name="confirm_password" placeholder="Konfirmasi Password Baru" required>
                        <button type="submit" name="change_password">
                            <i class="fas fa-key"></i> Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        function showSection(sectionName) {
            // Hide all sections
            document.querySelectorAll('.section-content').forEach(section => {
                section.classList.remove('active');
            });
            
            // Show selected section
            document.getElementById(sectionName + '-section').classList.add('active');
            
            // Update button states
            document.querySelectorAll('.mgmt-btn').forEach(btn => {
                btn.style.opacity = '0.7';
            });
            event.target.style.opacity = '1';
        }
    </script>
</body>
</html>