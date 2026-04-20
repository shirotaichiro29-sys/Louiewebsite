<?php
require 'config.php';
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id) {
  $mysqli->query("DELETE FROM students WHERE id=$id");
}
header('Location: dashboard.php');
exit;
?>
