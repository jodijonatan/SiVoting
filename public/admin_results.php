<?php
require_once __DIR__ . '/../init.php';
if (!is_admin()) {
  header('Location: admin_login.php');
  exit;
}
require_once __DIR__ . '/../helpers.php';

// Ambil data statistik
$total_voters_res = $mysqli->query("SELECT COUNT(*) AS cnt FROM voters");
$total_voters = $total_voters_res->fetch_assoc()['cnt'];

$total_votes_res = $mysqli->query("SELECT COUNT(*) AS cnt FROM votes");
$total_votes = $total_votes_res->fetch_assoc()['cnt'];

$options = $mysqli->query("SELECT * FROM options ORDER BY votes_count DESC");
$votes_list = $mysqli->query("SELECT v.*, o.title, vt.name AS voter_name 
  FROM votes v 
  JOIN options o ON v.option_id = o.id 
  JOIN voters vt ON v.voter_id = vt.id 
  ORDER BY v.created_at DESC");

if (!$votes_list) {
  die("Query Error: " . $mysqli->error);
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Hasil Voting</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen font-sans">
  <!-- Navbar -->
  <nav class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
      <a href="admin_dashboard.php" class="text-xl font-semibold text-blue-600 hover:text-blue-800 transition">
        🗳️ Admin - Hasil Voting
      </a>
      <div class="flex items-center space-x-3">
        <span class="text-gray-600">Halo, <b><?php echo e($_SESSION['username']); ?></b></span>
        <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
          Logout
        </a>
      </div>
    </div>
  </nav>

  <main class="max-w-6xl mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">📊 Hasil Voting</h1>
      <a href="admin_dashboard.php" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Dashboard</a>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
        <h3 class="text-gray-500 text-sm font-semibold uppercase mb-2">Total Voters</h3>
        <p class="text-3xl font-bold text-blue-600"><?php echo e($total_voters); ?></p>
      </div>
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
        <h3 class="text-gray-500 text-sm font-semibold uppercase mb-2">Total Votes</h3>
        <p class="text-3xl font-bold text-green-600"><?php echo e($total_votes); ?></p>
      </div>
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
        <h3 class="text-gray-500 text-sm font-semibold uppercase mb-2">Partisipasi</h3>
        <p class="text-3xl font-bold text-purple-600">
          <?php
          $percent = $total_voters > 0 ? round(($total_votes / $total_voters) * 100, 1) : 0;
          echo e($percent . '%');
          ?>
        </p>
      </div>
    </div>

    <!-- Hasil per opsi -->
    <div class="bg-white rounded-xl shadow mb-8 p-6">
      <h2 class="text-lg font-semibold text-gray-700 mb-4">Hasil Berdasarkan Opsi</h2>
      <?php
      $maxVotesRes = $mysqli->query("SELECT MAX(votes_count) as max_vote FROM options");
      $maxVote = $maxVotesRes->fetch_assoc()['max_vote'] ?? 0;

      while ($opt = $options->fetch_assoc()):
        $percentOpt = $total_votes > 0 ? round(($opt['votes_count'] / $total_votes) * 100, 1) : 0;
        $barWidth = $total_votes > 0 ? ($opt['votes_count'] / $total_votes) * 100 : 0;
      ?>
        <div class="mb-4">
          <div class="flex justify-between mb-1">
            <span class="font-medium text-gray-800"><?php echo e($opt['title']); ?></span>
            <span class="text-sm text-gray-500"><?php echo e($opt['votes_count']); ?> suara (<?php echo e($percentOpt); ?>%)</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="h-3 rounded-full bg-blue-500 transition-all duration-500" style="width: <?php echo $barWidth; ?>%;"></div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>


    <!-- Log suara -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <div class="p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-700">📋 Riwayat Voting (Audit Log)</h2>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
          <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
              <th class="py-3 px-4">ID</th>
              <th class="py-3 px-4">Nama Voter</th>
              <th class="py-3 px-4">Pilihan</th>
              <th class="py-3 px-4">Waktu</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <?php while ($v = $votes_list->fetch_assoc()): ?>
              <tr class="hover:bg-gray-50 transition">
                <td class="py-3 px-4 text-gray-500"><?php echo e($v['id']); ?></td>
                <td class="py-3 px-4 text-gray-800"><?php echo e($v['voter_name']); ?></td>
                <td class="py-3 px-4 text-blue-700 font-medium"><?php echo e($v['title']); ?></td>
                <td class="py-3 px-4 text-gray-600"><?php echo e(date('d M Y, H:i', strtotime($v['created_at']))); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</body>

</html>