<?php
/**
 * Dynamic PWA Web App Manifest Handler
 * Serves manifest with explicit application/manifest+json header for all browsers and hosts.
 */
header('Content-Type: application/manifest+json; charset=utf-8');
header('Cache-Control: public, max-age=3600');
header('Access-Control-Allow-Origin: *');

$manifest_path = __DIR__ . '/manifest.json';
if (file_exists($manifest_path)) {
    echo file_get_contents($manifest_path);
} else {
    echo json_encode([
        "name" => "حمزة بوبكر الصديق — منصة هندسة البرمجيات",
        "short_name" => "Hamza Portfolio",
        "start_url" => "./",
        "scope" => "./",
        "display" => "standalone",
        "background_color" => "#0d1527",
        "theme_color" => "#009688"
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
exit;
