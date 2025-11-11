<?php
require_once __DIR__ . '/../init.php';
if (!is_admin()) {
  header('Location: admin_login.php');
  exit;
}
require_once __DIR__ . '/../helpers.php';

$page_title = "Hasil Voting";

// Ambil data statistik
$total_voters_res = $mysqli->query("SELECT COUNT(*) AS cnt FROM voters");
$total_voters = $total_voters_res->fetch_assoc()['cnt'];

// Ambil total votes dari sum votes_count di tabel options untuk konsistensi
$total_votes_res = $mysqli->query("SELECT SUM(votes_count) AS cnt FROM options");
$total_votes = $total_votes_res->fetch_assoc()['cnt'] ?? 0;

// Ambil semua opsi
$options = $mysqli->query("SELECT * FROM options ORDER BY votes_count DESC");

// Ambil riwayat voting
$votes_list = $mysqli->query("
  SELECT v.*, o.title, vt.name AS voter_name 
  FROM votes v 
  JOIN options o ON v.option_id = o.id 
  JOIN voters vt ON v.voter_id = vt.id 
  ORDER BY v.created_at DESC
");

if (!$votes_list) {
  die("Query Error: " . $mysqli->error);
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title><?= $page_title ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs" defer></script>
  <link rel="icon" type="image/png" href="../assets/favicon.png">
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-gray-100 min-h-screen font-sans" x-data="{ open: true }">

  <!-- Sidebar -->
  <aside
    x-on:sidebar-toggle.window="open = !open"
    :class="open ? 'w-64 p-6' : 'w-20 p-2'"
    class="bg-white shadow-lg h-screen fixed left-0 top-0 transition-all duration-300 flex flex-col">
    <?php include __DIR__ . '/../components/admin_sidebar.php'; ?>
  </aside>

  <!-- Header -->
  <?php include __DIR__ . '/../components/admin_header.php'; ?>

  <!-- Main Content -->
  <main :class="open ? 'ml-64' : 'ml-20'" class="transition-all duration-300 mt-24 p-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">📊 Hasil Voting</h1>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
      <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
        <h3 class="text-gray-500 text-sm font-semibold uppercase mb-2">Total Voters</h3>
        <p class="text-3xl font-bold text-blue-600"><?= e($total_voters); ?></p>
      </div>

      <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
        <h3 class="text-gray-500 text-sm font-semibold uppercase mb-2">Total Votes</h3>
        <p class="text-3xl font-bold text-green-600"><?= e($total_votes); ?></p>
      </div>

      <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
        <h3 class="text-gray-500 text-sm font-semibold uppercase mb-2">Partisipasi</h3>
        <p class="text-3xl font-bold text-purple-600">
          <?php
          $percent = $total_voters > 0 ? round(($total_votes / $total_voters) * 100, 1) : 0;
          $percent = min($percent, 100); // batasi maksimum 100%
          echo e($percent . '%');
          ?>
        </p>
      </div>
    </div>

    <!-- Hasil per Opsi -->
    <div class="bg-white rounded-xl shadow mb-8 p-6">
      <h2 class="text-lg font-semibold text-gray-700 mb-4">Hasil Berdasarkan Opsi</h2>

      <?php
      while ($opt = $options->fetch_assoc()):
        $percentOpt = $total_votes > 0 ? round(($opt['votes_count'] / $total_votes) * 100, 1) : 0;
        $barWidth = min($percentOpt, 100); // batasi maksimum 100%
      ?>
        <div class="mb-4">
          <div class="flex justify-between mb-1">
            <span class="font-medium text-gray-800"><?= e($opt['title']); ?></span>
            <span class="text-sm text-gray-500"><?= e($opt['votes_count']); ?> suara (<?= e($percentOpt); ?>%)</span>
          </div>

          <div class="w-full bg-gray-200 rounded-full h-3">
            <div
              class="h-3 rounded-full bg-blue-500 transition-all duration-500"
              style="width: <?= $barWidth ?>%;">
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <!-- Riwayat Voting -->
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
                <td class="py-3 px-4 text-gray-500"><?= e($v['id']); ?></td>
                <td class="py-3 px-4 text-gray-800"><?= e($v['voter_name']); ?></td>
                <td class="py-3 px-4 text-blue-700 font-medium"><?= e($v['title']); ?></td>
                <td class="py-3 px-4 text-gray-600">
                  <?= e(date('d M Y, H:i', strtotime($v['created_at']))); ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

  </main>

</body>

</html>