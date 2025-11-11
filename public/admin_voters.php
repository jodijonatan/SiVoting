<?php
require_once __DIR__ . '/../init.php';
if (!is_admin()) {
  header('Location: admin_login.php');
  exit;
}
require_once __DIR__ . '/../helpers.php';

// Tambah voter
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_voter'])) {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  if ($name === '') flash('error', 'Nama tidak boleh kosong.');
  else {
    $token = bin2hex(random_bytes(4));
    $stmt = $mysqli->prepare("INSERT INTO voters (name, email, token) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $name, $email, $token);
    if ($stmt->execute()) flash('success', "Voter berhasil ditambahkan! Token login: $token");
    else flash('error', 'Gagal menambahkan voter.');
  }
  header('Location: admin_voters.php');
  exit;
}

// Hapus voter
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $stmt = $mysqli->prepare("DELETE FROM voters WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  flash('success', 'Voter berhasil dihapus.');
  header('Location: admin_voters.php');
  exit;
}

// Toggle status voting
if (isset($_GET['toggle'])) {
  $id = (int)$_GET['toggle'];

  $stmt = $mysqli->prepare("SELECT voted FROM voters WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $resToggle = $stmt->get_result()->fetch_assoc();

  if ($resToggle) {
    $newStatus = $resToggle['voted'] ? 0 : 1;

    $stmt = $mysqli->prepare("UPDATE voters SET voted = ? WHERE id = ?");
    $stmt->bind_param('ii', $newStatus, $id);
    $stmt->execute();

    flash('success', 'Status voter berhasil diperbarui.');
  }

  header('Location: admin_voters.php');
  exit;
}

$res = $mysqli->query("SELECT * FROM voters ORDER BY created_at DESC");

$total_voters = $mysqli->query("SELECT COUNT(*) AS c FROM voters")->fetch_assoc()['c'] ?? 0;
$total_voted = $mysqli->query("SELECT COUNT(*) AS c FROM voters WHERE voted = 1")->fetch_assoc()['c'] ?? 0;
$percent = $total_voters > 0 ? round(($total_voted / $total_voters) * 100, 1) : 0;

$page_title = "Kelola Voters";
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title><?= e($page_title) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="../assets/favicon.png">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-gray-100 min-h-screen font-sans flex" x-data="{ sidebarOpen: true }">

  <!-- ✅ Sidebar -->
  <?php include __DIR__ . '/../components/admin_sidebar.php'; ?>

  <!-- ✅ Header -->
  <?php include __DIR__ . '/../components/admin_header.php'; ?>

  <!-- ✅ Konten utama -->
  <main
    x-on:sidebar-state.window="sidebarOpen = $event.detail"
    :class="sidebarOpen ? 'ml-64' : 'ml-20'"
    class="transition-all duration-300 p-8 pt-32 w-full">

    <!-- ✅ Wrapper konten agar ikut melebar -->
    <div
      :class="sidebarOpen ? 'max-w-[calc(100vw-256px)]' : 'max-w-[calc(100vw-80px)]'"
      class="transition-all duration-300 mx-auto">

      <h1 class="text-2xl font-bold text-gray-800 mb-6">👥 Kelola Voters</h1>

      <!-- Statistik -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-6 rounded-xl shadow">
          <h3 class="text-gray-500 text-sm font-semibold uppercase mb-2">Total Voters</h3>
          <p class="text-3xl font-bold text-blue-600"><?= e($total_voters) ?></p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
          <h3 class="text-gray-500 text-sm font-semibold uppercase mb-2">Sudah Voting</h3>
          <p class="text-3xl font-bold text-green-600"><?= e($total_voted) ?></p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
          <h3 class="text-gray-500 text-sm font-semibold uppercase mb-2">Partisipasi</h3>
          <p class="text-3xl font-bold text-purple-600"><?= e($percent) ?>%</p>
        </div>
      </div>

      <!-- Alerts -->
      <?php if ($m = flash('error')): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= e($m) ?></div>
      <?php endif; ?>

      <?php if ($m = flash('success')): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= e($m) ?></div>
      <?php endif; ?>

      <!-- Form tambah voter -->
      <div class="bg-white rounded-xl shadow p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">➕ Tambah Voter Baru</h2>
        <form method="post" class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <input name="name" placeholder="Nama Lengkap" required
            class="border border-gray-300 rounded-lg p-3" />
          <input name="email" placeholder="Email (opsional)"
            class="border border-gray-300 rounded-lg p-3" />
          <button name="add_voter"
            class="bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition p-3">
            Tambah
          </button>
        </form>
      </div>

      <!-- Tabel voter -->
      <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="p-4 border-b">
          <h2 class="text-lg font-semibold text-gray-700">📋 Daftar Voters</h2>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full text-left text-sm">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
              <tr>
                <th class="py-3 px-4">ID</th>
                <th class="py-3 px-4">Nama</th>
                <th class="py-3 px-4">Email</th>
                <th class="py-3 px-4">Token</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4 text-center">Aksi</th>
              </tr>
            </thead>

            <tbody class="divide-y">
              <?php while ($row = $res->fetch_assoc()): ?>
                <tr class="hover:bg-gray-50 transition">
                  <td class="py-3 px-4 text-gray-500"><?= e($row['id']) ?></td>
                  <td class="py-3 px-4 font-medium text-gray-800"><?= e($row['name']) ?></td>
                  <td class="py-3 px-4 text-gray-600"><?= e($row['email']) ?></td>
                  <td class="py-3 px-4 font-mono text-xs bg-gray-50 rounded"><?= e($row['token']) ?></td>

                  <!-- Status -->
                  <td class="py-3 px-4">
                    <?php if ($row['voted']): ?>
                      <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">Sudah Vote</span>
                    <?php else: ?>
                      <span class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-600 rounded-full">Belum Vote</span>
                    <?php endif; ?>
                  </td>

                  <!-- Aksi -->
                  <td class="py-3 px-4 text-center space-x-3">
                    <a href="?toggle=<?= e($row['id']) ?>"
                      class="px-3 py-1 text-xs font-semibold text-white rounded-full
                        <?= $row['voted'] ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' ?>">
                      <?= $row['voted'] ? 'Set Belum Vote' : 'Set Sudah Vote' ?>
                    </a>

                    <a href="?delete=<?= e($row['id']) ?>"
                      onclick="return confirm('Hapus voter ini?')"
                      class="px-3 py-1 text-xs font-semibold bg-red-400 hover:bg-red-500 text-white rounded-full">
                      Hapus
                    </a>
                  </td>

                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </main>

</body>

</html>