<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to write detailed logs
function writeLoginLog($message, $data = []) {
    $logFile = __DIR__ . '/../logs/login_errors.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $host = $_SERVER['HTTP_HOST'] ?? 'unknown';
    $requestUri = $_SERVER['REQUEST_URI'] ?? 'unknown';
    
    $logEntry = "[$timestamp] [$ip] [$host] $message\n";
    $logEntry .= "User-Agent: $userAgent\n";
    $logEntry .= "Request URI: $requestUri\n";
    
    if (!empty($data)) {
        $logEntry .= "Additional Data: " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";
    }
    $logEntry .= str_repeat('-', 80) . "\n";
    
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

// Log environment info
writeLoginLog("Login attempt started", [
    'POST_data_received' => !empty($_POST),
    'session_id' => session_id(),
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
    'https_status' => isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'not_set'
]);

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '', // để PHP tự nhận domain hiện tại (localhost hoặc ngrok)
    'secure' => false, // đặt true nếu bạn dùng HTTPS thật, ngrok test để false
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

// Use the project's DB connection
require_once __DIR__ . '/../config/db_connect.php';

// Test database connection
if (!$conn) {
    writeLoginLog("Database connection failed", [
        'mysqli_error' => mysqli_connect_error()
    ]);
    die("Database connection failed");
}

writeLoginLog("Database connection successful");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    writeLoginLog("Login form submitted", [
        'email' => $email,
        'password_length' => strlen($password),
        'post_data_keys' => array_keys($_POST)
    ]);

    // Validate input
    if (empty($email) || empty($password)) {
        writeLoginLog("Missing email or password", [
            'email_empty' => empty($email),
            'password_empty' => empty($password)
        ]);
        $_SESSION['loginMessage'] = 'Vui lòng nhập đầy đủ email và password!';
        header('Location: login.php');
        exit();
    }

    // Prepare: get user row by email
    $sql = "SELECT id, email, password, usertype FROM users WHERE email = ? LIMIT 1";
    writeLoginLog("Executing SQL query", ['sql' => $sql, 'email_param' => $email]);
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 's', $email);
        
        if (!mysqli_stmt_execute($stmt)) {
            writeLoginLog("SQL execution failed", [
                'error' => mysqli_stmt_error($stmt)
            ]);
            mysqli_stmt_close($stmt);
            $_SESSION['loginMessage'] = 'Lỗi hệ thống. Vui lòng thử lại.';
            header('Location: login.php');
            exit();
        }
        
        $result = mysqli_stmt_get_result($stmt);
        $row = $result ? mysqli_fetch_assoc($result) : null;

        writeLoginLog("Query executed", [
            'user_found' => !is_null($row),
            'result_type' => gettype($result),
            'num_rows' => $result ? mysqli_num_rows($result) : 0
        ]);

        // Close prepared statement early to avoid unreachable code warnings
        mysqli_stmt_close($stmt);

        if ($row) {
            writeLoginLog("User found in database", [
                'user_id' => $row['id'],
                'email' => $row['email'],
                'usertype' => $row['usertype'],
                'password_hash_length' => strlen($row['password'])
            ]);
            
            // Verify password
            $passwordMatch = password_verify($password, $row['password']);
            writeLoginLog("Password verification", [
                'password_match' => $passwordMatch,
                'input_password_length' => strlen($password),
                'stored_hash_starts_with' => substr($row['password'], 0, 10)
            ]);
            
            if ($passwordMatch) {
                // Successful login
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['usertype'] = $row['usertype'];

                writeLoginLog("Login successful", [
                    'user_id' => $row['id'],
                    'usertype' => $row['usertype'],
                    'redirect_to' => $row['usertype'] === 'admin' ? '../admin/ad_home.php' : 'home.php'
                ]);

                if ($row['usertype'] === 'admin') {
                    header('Location: ../admin/ad_home.php');
                    exit();
                } else {
                    header('Location: home.php');
                    exit();
                }
            } else {
                // Password verification failed
                writeLoginLog("Login failed - wrong password", [
                    'email' => $email,
                    'attempt_count' => ($_SESSION['login_attempts'] ?? 0) + 1
                ]);
                $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
                $_SESSION['loginMessage'] = 'Email và Password không tồn tại!';
                header('Location: login.php');
                exit();
            }
        } else {
            // User not found
            writeLoginLog("Login failed - user not found", [
                'email' => $email,
                'sql_executed' => true
            ]);
            $_SESSION['loginMessage'] = 'Email và Password không tồn tại!';
            header('Location: login.php');
            exit();
        }
    } else {
        writeLoginLog("SQL prepare failed", [
            'mysqli_error' => mysqli_error($conn),
            'sql' => $sql
        ]);
        $_SESSION['loginMessage'] = 'Lỗi hệ thống. Vui lòng thử lại.';
        header('Location: login.php');
        exit();
    }
} else {
    writeLoginLog("Not a POST request", [
        'request_method' => $_SERVER['REQUEST_METHOD'],
        'redirect_to' => 'login.php'
    ]);
    // Not a POST request, redirect to login
    header('Location: login.php');
    exit();
}
?>