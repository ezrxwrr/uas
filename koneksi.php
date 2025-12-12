<?php

// Simple MySQL connection and SQL import helper for local XAMPP
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'projekuas';

$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_errno) {
    http_response_code(500);
    exit('Connect failed: ' . $mysqli->connect_error);
}

// Create database if it doesn't exist, then select it
$mysqli->query("CREATE DATABASE IF NOT EXISTS `" . $mysqli->real_escape_string($db) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$mysqli->select_db($db);
$mysqli->set_charset('utf8mb4');

function import_sql_file(mysqli $mysqli, string $filepath): array
{
    if (!is_readable($filepath)) {
        return ['ok' => false, 'msg' => "SQL file not found: $filepath"];
    }

    $sql = file_get_contents($filepath);
    if ($sql === false) {
        return ['ok' => false, 'msg' => "Failed to read SQL file: $filepath"];
    }

    // Split statements by semicolon followed by a newline (simple but works for typical dumps)
    $statements = array_filter(array_map('trim', preg_split('/;\s*\n/', $sql)));

    foreach ($statements as $stmt) {
        if ($stmt === '') continue;
        if (!$mysqli->query($stmt)) {
            return ['ok' => false, 'msg' => "Error executing statement: " . $mysqli->error];
        }
    }

    return ['ok' => true, 'msg' => 'Import completed'];
}

// Trigger import by visiting koneksi.php?import=1 in browser (or run via local HTTP)
if (isset($_GET['import']) && $_GET['import'] == '1') {
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'projekuas.sql';
    $res = import_sql_file($mysqli, $file);
    if ($res['ok']) {
        echo "Import successful: " . htmlspecialchars($res['msg']);
    } else {
        http_response_code(500);
        echo "Import failed: " . htmlspecialchars($res['msg']);
    }
    exit;
}

// Export the $mysqli connection for inclusion in other scripts
return $mysqli;

?>
