<?php
// auth/auth_functions.php
require_once __DIR__ . '/../config/db_connection.php';

function registerStudent($nama, $nim, $kelas, $email, $no_telp, $password) {
    global $conn;
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        // First check if NIM or email already exists
        $check_stmt = $conn->prepare("SELECT nim, email FROM mahasiswa WHERE nim = :nim OR email = :email");
        $check_stmt->bindParam(':nim', $nim);
        $check_stmt->bindParam(':email', $email);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() > 0) {
            $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);
            if ($existing['nim'] === $nim) {
                throw new Exception("NIM sudah terdaftar");
            }
            if ($existing['email'] === $email) {
                throw new Exception("Email sudah terdaftar");
            }
        }
        
        // Insert new student
        $stmt = $conn->prepare("INSERT INTO mahasiswa (nama, nim, kelas, email, no_telp, password) 
                               VALUES (:nama, :nim, :kelas, :email, :no_telp, :password)");
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':nim', $nim);
        $stmt->bindParam(':kelas', $kelas);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':no_telp', $no_telp);
        $stmt->bindParam(':password', $hashed_password);
        
        $result = $stmt->execute();
        
        if ($result) {
            return ['success' => true, 'message' => 'Registrasi berhasil'];
        } else {
            return ['success' => false, 'message' => 'Gagal menyimpan data'];
        }
        
    } catch(PDOException $e) {
        // Log the actual error for debugging
        error_log("Database Error: " . $e->getMessage());
        
        // Check for specific MySQL errors
        if ($e->getCode() == 23000) { // Integrity constraint violation
            if (strpos($e->getMessage(), 'nim') !== false) {
                return ['success' => false, 'message' => 'NIM sudah terdaftar'];
            } elseif (strpos($e->getMessage(), 'email') !== false) {
                return ['success' => false, 'message' => 'Email sudah terdaftar'];
            }
        }
        
        return ['success' => false, 'message' => 'Terjadi kesalahan database: ' . $e->getMessage()];
    } catch(Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

function loginStudent($nim, $password) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE nim = :nim");
        $stmt->bindParam(':nim', $nim);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    } catch(PDOException $e) {
        error_log("Login Error: " . $e->getMessage());
        return false;
    }
}

function generateResetToken($email) {
    global $conn;
    
    try {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM mahasiswa WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $stmt = $conn->prepare("UPDATE mahasiswa SET reset_token = :token, reset_expires = :expires WHERE email = :email");
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':expires', $expires);
            $stmt->bindParam(':email', $email);
            
            if ($stmt->execute()) {
                return $token;
            }
        }
        return false;
    } catch(PDOException $e) {
        error_log("Reset Token Error: " . $e->getMessage());
        return false;
    }
}

function resetPassword($token, $new_password) {
    global $conn;
    
    try {
        // Check if token is valid and not expired
        $stmt = $conn->prepare("SELECT id FROM mahasiswa WHERE reset_token = :token AND reset_expires > NOW()");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("UPDATE mahasiswa SET password = :password, reset_token = NULL, reset_expires = NULL WHERE reset_token = :token");
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':token', $token);
            
            return $stmt->execute();
        }
        return false;
    } catch(PDOException $e) {
        error_log("Reset Password Error: " . $e->getMessage());
        return false;
    }
}

// Test database connection function
function testConnection() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT 1");
        return ['success' => true, 'message' => 'Database connection successful'];
    } catch(PDOException $e) {
        return ['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()];
    }
}
?>