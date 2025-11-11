<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = trim($_POST['token'] ?? '');
  if ($token === '') flash('error', 'Token dibutuhkan.');
  else {
    $stmt = $mysqli->prepare("SELECT id, name, voted FROM voters WHERE token = ? LIMIT 1");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
      $_SESSION['user_role'] = 'voter';
      $_SESSION['voter_id'] = $row['id'];
      $_SESSION['voter_name'] = $row['name'];
      header('Location: voter_dashboard.php');
      exit;
    } else {
      flash('error', 'Token salah.');
    }
  }
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Voter Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="../assets/favicon.png">

  <style>
    /* Tambahan: Background gradient */
    body {
      background: linear-gradient(135deg, #eceff1, #cfd8dc);
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

  <!-- Card Login -->
  <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-8
              transition transform hover:shadow-2xl duration-300">

    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">
      Voter Login
    </h2>

    <!-- Flash Message -->
    <?php if ($m = flash('error')): ?>
      <div class="bg-red-100 border border-red-300 text-red-700 p-3 mb-4 rounded-lg text-sm">
        <?= e($m); ?>
      </div>
    <?php endif; ?>

    <?php if ($m = flash('success')): ?>
      <div class="bg-green-100 border border-green-300 text-green-700 p-3 mb-4 rounded-lg text-sm">
        <?= e($m); ?>
      </div>
    <?php endif; ?>

    <!-- Form -->
    <form method="post" class="space-y-5">

      <div>
        <label class="block text-gray-700 font-medium mb-1">
          Token (dari admin)
        </label>
        <input
          name="token"
          required
          placeholder="Masukkan token anda..."
          class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2
                 focus:ring-blue-500 outline-none text-gray-800" />
      </div>

      <div class="flex flex-col sm:flex-row gap-3 pt-2">

        <button
          class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg font-semibold
                 hover:bg-green-700 transition text-center">
          Login
        </button>

        <a href="../index.php"
          class="flex-1 px-4 py-3 border border-gray-400 rounded-lg text-center
                 hover:bg-gray-100 transition font-semibold">
          Kembali
        </a>

      </div>
    </form>

  </div>

</body>

</html>