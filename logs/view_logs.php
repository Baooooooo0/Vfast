<?php
// Simple log viewer for debugging login issues
session_start();

// Basic security check - only allow admin or during development
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    // For development, you can comment out this check
    // die('Access denied. Admin access required.');
}

$logFile = __DIR__ . '/login_errors.log';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Debug Logs - VinFast</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #1a1a1a;
            color: #00ff00;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: #333;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #00ff00;
        }
        .log-content {
            background: #2a2a2a;
            padding: 20px;
            border-radius: 5px;
            white-space: pre-wrap;
            word-wrap: break-word;
            max-height: 600px;
            overflow-y: auto;
            border: 1px solid #444;
        }
        .controls {
            margin-bottom: 20px;
        }
        .btn {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
            display: inline-block;
        }
        .btn:hover {
            background: #45a049;
        }
        .btn.danger {
            background: #f44336;
        }
        .btn.danger:hover {
            background: #da190b;
        }
        .timestamp {
            color: #ffff00;
        }
        .error {
            color: #ff4444;
        }
        .success {
            color: #44ff44;
        }
        .info {
            color: #4444ff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 VinFast Login Debug Logs</h1>
            <p>Ngrok compatibility debugging - Real-time login monitoring</p>
        </div>

        <div class="controls">
            <a href="?action=refresh" class="btn">🔄 Refresh Logs</a>
            <a href="?action=clear" class="btn danger" onclick="return confirm('Bạn có chắc muốn xóa tất cả logs?')">🗑️ Clear Logs</a>
            <a href="../client/login.php" class="btn">🏠 Back to Login</a>
            <span style="margin-left: 20px;">Last updated: <?php echo date('H:i:s'); ?></span>
        </div>

        <?php
        // Handle actions
        if (isset($_GET['action'])) {
            if ($_GET['action'] === 'clear') {
                if (file_exists($logFile)) {
                    file_put_contents($logFile, '');
                    echo '<div style="background: #4CAF50; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px;">✅ Logs cleared successfully!</div>';
                }
            }
        }

        // Display logs
        echo '<div class="log-content">';
        
        if (file_exists($logFile)) {
            $logs = file_get_contents($logFile);
            
            if (empty(trim($logs))) {
                echo '<div class="info">📝 No logs yet. Try logging in to generate debug information.</div>';
            } else {
                // Add some basic highlighting
                $logs = htmlspecialchars($logs);
                
                // Highlight timestamps
                $logs = preg_replace('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', '<span class="timestamp">[$1]</span>', $logs);
                
                // Highlight errors
                $logs = preg_replace('/(failed|error|Error|ERROR)/i', '<span class="error">$1</span>', $logs);
                
                // Highlight success
                $logs = preg_replace('/(successful|success|Success|LOGIN successful)/i', '<span class="success">$1</span>', $logs);
                
                echo $logs;
            }
        } else {
            echo '<div class="error">❌ Log file not found: ' . htmlspecialchars($logFile) . '</div>';
            echo '<div class="info">💡 Try logging in first to create the log file.</div>';
        }
        
        echo '</div>';
        ?>

        <div style="margin-top: 20px; padding: 15px; background: #333; border-radius: 5px; font-size: 12px;">
            <strong>🛠️ Debug Information:</strong><br>
            Log file: <?php echo htmlspecialchars($logFile); ?><br>
            File exists: <?php echo file_exists($logFile) ? '✅ Yes' : '❌ No'; ?><br>
            File size: <?php echo file_exists($logFile) ? number_format(filesize($logFile)) . ' bytes' : 'N/A'; ?><br>
            Current time: <?php echo date('Y-m-d H:i:s'); ?><br>
            Server: <?php echo $_SERVER['HTTP_HOST'] ?? 'localhost'; ?><br>
            PHP Version: <?php echo phpversion(); ?>
        </div>
    </div>

    <script>
        // Auto-refresh every 30 seconds during active debugging
        // setTimeout(function() {
        //     window.location.reload();
        // }, 30000);
    </script>
</body>
</html>