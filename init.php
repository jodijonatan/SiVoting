<?php
// init.php
require_once __DIR__ . '/config.php';
session_start();

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
  die("DB Connection error: " . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

function is_admin()
{
  return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}
function is_voter()
{
  return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'voter';
}
