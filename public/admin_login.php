<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE username = ? AND role='admin' LIMIT 1");
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($row = $res->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['user_role'] = 'admin';
      $_SESSION['username'] = $username;
      header('Location: admin_dashboard.php');
      exit;
    }
  }

  flash('error', 'Login gagal: username/password salah.');
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">

  <!-- ✅ Wajib agar responsif -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Admin Login</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="../assets/favicon.png">

  <style>
    body {
      background: linear-gradient(135deg, #e8ecef, #d0d9df);
    }
  </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4 overflow-x-hidden">

  <!-- ✅ Card Login Responsif -->
  <div class="bg-white w-full max-w-sm sm:max-w-md p-6 sm:p-8 rounded-2xl shadow-md 
              transition hover:shadow-xl duration-300">

    <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-gray-800 text-center">
      Admin Login
    </h2>

    <!-- ✅ Flash Error -->
    <?php if ($m = flash('error')): ?>
      <div class="bg-red-100 border border-red-300 text-red-700 p-3 mb-4 rounded-lg text-sm sm:text-base">
        <?= e($m); ?>
      </div>
    <?php endif; ?>

    <!-- ✅ Form Login -->
    <form method="post" class="space-y-5">

      <div>
        <label class="block text-gray-700 font-medium mb-1 text-sm sm:text-base">
          Username
        </label>
        <input name="username"
          required
          placeholder="Masukkan username"
          class="w-full p-3 border border-gray-300 rounded-lg text-gray-800
                      focus:ring-2 focus:ring-blue-500 outline-none text-sm sm:text-base">
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1 text-sm sm:text-base">
          Password
        </label>
        <input name="password" type="password"
          required
          placeholder="Masukkan password"
          class="w-full p-3 border border-gray-300 rounded-lg text-gray-800
                      focus:ring-2 focus:ring-blue-500 outline-none text-sm sm:text-base">
      </div>

      <!-- ✅ Tombol Responsif -->
      <div class="flex flex-col sm:flex-row gap-3 pt-2">

        <button
          class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg 
                 font-semibold transition text-sm sm:text-base text-center">
          Login
        </button>

        <a href="../index.php"
          class="flex-1 px-4 py-3 border border-gray-400 rounded-lg text-center
                  hover:bg-gray-100 transition font-semibold text-sm sm:text-base">
          Kembali
        </a>

      </div>
    </form>

  </div>

</body>

</html>