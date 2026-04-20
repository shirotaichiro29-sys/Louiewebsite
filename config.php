<?php
// config.php - database connection
$host = 'localhost';
$db   = 'sunn_system';
$user = 'root';
$pass = ''; // change if you have password
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    die('Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
session_start();
?>
