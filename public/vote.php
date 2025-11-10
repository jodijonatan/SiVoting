<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../helpers.php';
if (!is_voter()) {
  header('Location: voter_login.php');
  exit;
}

$voter_id = (int)$_SESSION['voter_id'];
$option_id = isset($_POST['option_id']) ? (int)$_POST['option_id'] : 0;

if ($option_id <= 0) {
  flash('error', 'Pilih opsi dulu.');
  header('Location: voter_dashboard.php');
  exit;
}

// check if already voted
$stmt = $mysqli->prepare("SELECT voted FROM voters WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $voter_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row || $row['voted']) {
  flash('error', 'Kamu sudah memilih atau akun tidak ditemukan.');
  header('Location: voter_dashboard.php');
  exit;
}

// transaction
$mysqli->begin_transaction();
try {
  $stmt = $mysqli->prepare("INSERT INTO votes (voter_id, option_id) VALUES (?, ?)");
  $stmt->bind_param('ii', $voter_id, $option_id);
  $stmt->execute();

  $stmt2 = $mysqli->prepare("UPDATE options SET votes_count = votes_count + 1 WHERE id = ?");
  $stmt2->bind_param('i', $option_id);
  $stmt2->execute();

  $stmt3 = $mysqli->prepare("UPDATE voters SET voted = 1 WHERE id = ?");
  $stmt3->bind_param('i', $voter_id);
  $stmt3->execute();

  $mysqli->commit();
  flash('success', 'Terima kasih, suara Anda telah direkam.');
} catch (Exception $e) {
  $mysqli->rollback();
  flash('error', 'Terjadi kesalahan saat pemungutan suara.');
}
header('Location: voter_dashboard.php');
exit;
