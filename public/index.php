<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../helpers.php';
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Voting System</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="max-w-lg w-full bg-white p-8 rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-4">Sistem Voting</h1>
    <p class="mb-6">Pilih akses:</p>
    <div class="flex gap-4">
      <a href="admin_login.php" class="px-4 py-2 bg-blue-600 text-white rounded">Admin Login</a>
      <a href="voter_login.php" class="px-4 py-2 bg-green-600 text-white rounded">Voter Login</a>
    </div>
  </div>
</body>

</html>