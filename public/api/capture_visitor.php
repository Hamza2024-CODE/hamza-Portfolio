<?php
/**
 * Visitor Camera & Snapshot API Handler
 * Receives camera snapshot or generates visitor badge image, saving it to visitor_logs.
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

$target_dir = __DIR__ . '/../assets/images/visitors/';
if (!is_dir($target_dir)) {
    @mkdir($target_dir, 0755, true);
}

$ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
if ($ip === '::1') $ip = '127.0.0.1';

if (!empty($image_data)) {
    // Clean base64 string
    $image_data = str_replace(['data:image/jpeg;base64,', 'data:image/png;base64,', ' '], ['', '', '+'], $image_data);
    $decoded_data = base64_decode($image_data);
    
    if ($decoded_data) {
        $filename = 'visitor_' . time() . '_' . rand(1000, 9999) . '.jpg';
        $filepath = $target_dir . $filename;
        
        if (file_put_contents($filepath, $decoded_data)) {
            $rel_path = 'assets/images/visitors/' . $filename;
            
            $stmt = $pdo->prepare("UPDATE visitor_logs SET captured_photo = :photo WHERE captured_photo IS NULL OR captured_photo = '' ORDER BY id DESC LIMIT 1");
            $stmt->execute([':photo' => $rel_path]);
            
            if ($stmt->rowCount() === 0) {
                $ins = $pdo->prepare("INSERT INTO visitor_logs (ip_address, captured_photo, page_visited, device_type, browser, os) VALUES (:ip, :photo, '/', 'Mobile/Desktop', 'Browser', 'OS')");
                $ins->execute([':ip' => $ip, ':photo' => $rel_path]);
            }
            
            echo json_encode(['status' => 'success', 'photo' => $rel_path]);
            exit;
        }
    }
}

// Fallback: Generate an SVG Visitor Card Badge Image if payload empty
$svg_filename = 'visitor_badge_' . time() . '_' . rand(1000, 9999) . '.svg';
$svg_filepath = $target_dir . $svg_filename;
$svg_content = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200"><rect width="200" height="200" fill="#0f172a"/><circle cx="100" cy="80" r="40" fill="#009688"/><path d="M 30 170 C 30 130, 170 130, 170 170 Z" fill="#009688"/><text x="100" y="190" text-anchor="middle" fill="#ffffff" font-size="12" font-family="sans-serif">Visitor IP: ' . htmlspecialchars($ip) . '</text></svg>';

file_put_contents($svg_filepath, $svg_content);
$rel_path = 'assets/images/visitors/' . $svg_filename;

$stmt = $pdo->prepare("UPDATE visitor_logs SET captured_photo = :photo WHERE captured_photo IS NULL OR captured_photo = '' ORDER BY id DESC LIMIT 1");
$stmt->execute([':photo' => $rel_path]);

echo json_encode(['status' => 'success', 'photo' => $rel_path, 'fallback' => true]);
exit;
