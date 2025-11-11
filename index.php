<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/helpers.php';
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">

  <!-- ✅ Wajib untuk tampilan mobile -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Voting System</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="./assets/favicon.png">

  <style>
    body {
      background: linear-gradient(135deg, #eef2f3, #dfe9f3);
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 overflow-x-hidden">

  <!-- ✅ Card utama, responsif -->
  <div class="w-full max-w-sm sm:max-w-md bg-white p-6 sm:p-8 rounded-2xl shadow-lg text-center">

    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4">
      Sistem Voting
    </h1>

    <p class="mb-6 text-gray-600 text-sm sm:text-base">
      Pilih akses login yang sesuai:
    </p>

    <!-- ✅ Tombol responsif -->
    <div class="flex flex-col sm:flex-row gap-3">

      <a href="public/admin_login.php"
        class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition text-sm sm:text-base text-center">
        Admin Login
      </a>

      <a href="public/voter_login.php"
        class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition text-sm sm:text-base text-center">
        Voter Login
      </a>

    </div>

  </div>

</body>

</html>