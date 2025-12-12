<?php

// Clean MySQL connection for local XAMPP — connects to existing `projekuas` database
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'projekuas';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    error_log('MySQL connect error: ' . $mysqli->connect_error);
    die('Database connection failed: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');

// Return the mysqli connection so other scripts can `require` this file
return $mysqli;

?>
