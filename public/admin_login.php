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
<html>

<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="../assets/favicon.png">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded shadow max-w-md w-full">
    <h2 class="text-xl font-bold mb-4">Admin Login</h2>
    <?php if ($m = flash('error')): ?>
      <div class="bg-red-100 text-red-700 p-2 mb-3 rounded"><?php echo e($m); ?></div>
    <?php endif; ?>
    <form method="post">
      <label class="block mb-2">Username<input name="username" required class="w-full p-2 border rounded"></label>
      <label class="block mb-4">Password<input name="password" type="password" required class="w-full p-2 border rounded"></label>
      <div class="flex gap-2">
        <button class="px-4 py-2 bg-blue-600 text-white rounded">Login</button>
        <a href="../index.php" class="px-4 py-2 border rounded">Back</a>
      </div>
    </form>
  </div>
</body>

</html>