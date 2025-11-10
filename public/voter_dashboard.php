<?php
require_once __DIR__ . '/../init.php';
if (!is_voter()) {
  header('Location: voter_login.php');
  exit;
}
require_once __DIR__ . '/../helpers.php';

$voter_id = (int)$_SESSION['voter_id'];
$stmt = $mysqli->prepare("SELECT voted FROM voters WHERE id = ?");
$stmt->bind_param('i', $voter_id);
$stmt->execute();
$voter = $stmt->get_result()->fetch_assoc();
$has_voted = (bool)$voter['voted'];

$res = $mysqli->query("SELECT * FROM options ORDER BY id ASC");
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Voter Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(10px);
      }

      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .fade-in-up {
      animation: fadeInUp 0.4s ease-out forwards;
    }
  </style>
</head>

<body class="bg-gradient-to-br from-gray-100 via-gray-50 to-white min-h-screen text-gray-800">
  <nav class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
      <div class="flex items-center gap-2 text-blue-600 font-bold text-xl">
        <a href="voter_dashboard.php" class="text-xl font-semibold text-blue-600 hover:text-blue-800 transition">
          🗳️ Voter - Dashboard
        </a>
      </div>
      <div class="flex items-center space-x-3">
        <span class="text-gray-600">Halo, <b><?php echo e($_SESSION['voter_name']); ?></b></span>
        <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
          Logout
        </a>
      </div>
    </div>
  </nav>


  <main class="max-w-3xl mx-auto mt-10 px-4 fade-in-up">
    <div class="bg-white rounded-2xl shadow p-6 mb-6 border border-gray-100">
      <h2 class="text-2xl font-semibold mb-2">Selamat datang, <?php echo e($_SESSION['voter_name']); ?>!</h2>
      <p class="text-gray-600">ID Voter: <span class="font-medium"><?php echo e($voter_id); ?></span></p>
    </div>

    <?php if ($has_voted): ?>
      <div class="bg-green-100 border border-green-300 text-green-800 rounded-xl p-5 mb-6 text-center shadow">
        <h3 class="text-lg font-semibold mb-1">✅ Kamu sudah memilih</h3>
        <p>Terima kasih sudah berpartisipasi dalam voting!</p>
      </div>
    <?php else: ?>
      <div class="bg-white rounded-2xl shadow p-6 border border-gray-100 mb-6">
        <h3 class="text-xl font-semibold mb-4 text-blue-700">🗳️ Pilih salah satu opsi:</h3>
        <form method="post" action="vote.php" class="space-y-3">
          <?php while ($opt = $res->fetch_assoc()): ?>
            <label class="block border rounded-xl p-4 hover:border-blue-500 hover:bg-blue-50 cursor-pointer transition-all">
              <input type="radio" name="option_id" value="<?php echo e($opt['id']); ?>" required class="mr-3 accent-blue-600">
              <span class="font-semibold text-gray-800"><?php echo e($opt['title']); ?></span>
              <?php if (trim($opt['description']) !== ''): ?>
                <div class="text-sm text-gray-600 ml-7"><?php echo e($opt['description']); ?></div>
              <?php endif; ?>
            </label>
          <?php endwhile; ?>
          <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl mt-3 transition-all">
            Kirim Vote
          </button>
        </form>
      </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
      <h3 class="text-lg font-semibold mb-2 text-gray-800">ℹ️ Informasi Penting</h3>
      <p class="text-gray-600 leading-relaxed">
        Setiap voter mendapatkan token unik dari admin. Token hanya bisa digunakan sekali untuk melakukan voting.
        Pastikan pilihanmu sudah benar sebelum mengirim.
      </p>
    </div>
  </main>

  <footer class="text-center text-gray-500 text-sm py-6 mt-10">
    &copy; <?php echo date('Y'); ?> SiVoting — Dibuat dengan ❤️ oleh JO DEV
  </footer>
</body>

</html>