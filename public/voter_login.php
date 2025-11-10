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
<html>

<head>
  <meta charset="utf-8">
  <title>Voter Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="bg-white p-8 rounded shadow max-w-md w-full">
    <h2 class="text-xl font-bold mb-4">Voter Login</h2>
    <?php if ($m = flash('error')): ?><div class="bg-red-100 p-2 text-red-700 mb-3 rounded"><?php echo e($m); ?></div><?php endif; ?>
    <?php if ($m = flash('success')): ?><div class="bg-green-100 p-2 text-green-700 mb-3 rounded"><?php echo e($m); ?></div><?php endif; ?>
    <form method="post">
      <label class="block mb-4">Token (dari admin)
        <input name="token" required class="w-full p-2 border rounded" />
      </label>
      <div class="flex gap-2">
        <button class="px-4 py-2 bg-green-600 text-white rounded">Login</button>
        <a href="index.php" class="px-4 py-2 border rounded">Back</a>
      </div>
    </form>
  </div>
</body>

</html>