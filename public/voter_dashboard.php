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
  <link rel="icon" type="image/png" href="../assets/favicon.png">
  <style>
    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(12px);
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

  <!-- ✅ Navbar -->
  <?php include __DIR__ . '/../components/nav.php'; ?>

  <!-- ✅ Main Content -->
  <main class="max-w-3xl mx-auto mt-8 md:mt-12 px-4 fade-in-up">

    <!-- ✅ Welcome Card -->
    <div class="bg-white rounded-2xl shadow p-5 md:p-6 mb-6 border border-gray-100">
      <h2 class="text-xl md:text-2xl font-semibold mb-2">Selamat datang, <?= e($_SESSION['voter_name']); ?>!</h2>
      <p class="text-gray-600 text-sm md:text-base">
        ID Voter: <span class="font-medium"><?= e($voter_id); ?></span>
      </p>
    </div>

    <!-- ✅ Sudah Voting -->
    <?php if ($has_voted): ?>
      <div class="bg-green-100 border border-green-300 text-green-800 rounded-xl p-5 md:p-6 mb-6 text-center shadow">
        <h3 class="text-lg font-semibold mb-1">✅ Kamu sudah memilih</h3>
        <p class="text-sm md:text-base">Terima kasih sudah berpartisipasi dalam voting!</p>
      </div>

      <!-- ✅ Form Voting -->
    <?php else: ?>
      <div class="bg-white rounded-2xl shadow p-5 md:p-6 border border-gray-100 mb-6">

        <h3 class="text-lg md:text-xl font-semibold mb-4 text-blue-700">🗳️ Pilih salah satu opsi:</h3>

        <form method="post" action="vote.php" class="space-y-3">

          <?php while ($opt = $res->fetch_assoc()): ?>
            <label class="block border rounded-xl p-4 hover:border-blue-500 hover:bg-blue-50 cursor-pointer transition-all">

              <div class="flex items-start gap-3">
                <input
                  type="radio"
                  name="option_id"
                  value="<?= e($opt['id']); ?>"
                  required
                  class="mt-1 accent-blue-600 w-5 h-5">

                <div>
                  <span class="font-semibold text-gray-800 text-sm md:text-base">
                    <?= e($opt['title']); ?>
                  </span>

                  <?php if (trim($opt['description']) !== ''): ?>
                    <div class="text-xs md:text-sm text-gray-600 mt-1">
                      <?= e($opt['description']); ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

            </label>
          <?php endwhile; ?>

          <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl mt-3 transition-all text-sm md:text-base">
            Kirim Vote
          </button>

        </form>
      </div>
    <?php endif; ?>

    <!-- ✅ Informasi Penting -->
    <div class="bg-white rounded-2xl shadow p-5 md:p-6 border border-gray-100">
      <h3 class="text-base md:text-lg font-semibold mb-2 text-gray-800">ℹ️ Informasi Penting</h3>
      <p class="text-gray-600 leading-relaxed text-sm md:text-base">
        Setiap voter mendapatkan token unik dari admin. Token hanya bisa digunakan sekali untuk melakukan voting.
        Pastikan pilihanmu sudah benar sebelum mengirim.
      </p>
    </div>

  </main>

  <!-- ✅ Footer -->
  <footer class="text-center text-gray-500 text-xs md:text-sm py-6 mt-10">
    &copy; <?= date('Y'); ?> SiVoting — Dibuat dengan ❤️ oleh JO DEV
  </footer>

</body>

</html>