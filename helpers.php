<?php
// helpers.php
function e($s)
{
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// simple flash messages
function flash($key, $msg = null)
{
  if ($msg === null) {
    if (isset($_SESSION['flash'][$key])) {
      $m = $_SESSION['flash'][$key];
      unset($_SESSION['flash'][$key]);
      return $m;
    }
    return null;
  } else {
    $_SESSION['flash'][$key] = $msg;
  }
}
