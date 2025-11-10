<?php
require_once __DIR__ . '/../init.php';
if (!is_admin()) {
  header('Location: admin_login.php');
  exit;
}
require_once __DIR__ . '/../helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_option'])) {
  $title = trim($_POST['title']);
  $desc = trim($_POST['description']);
  if ($title === '') flash('error', 'Judul tidak boleh kosong.');
  else {
    $stmt = $mysqli->prepare("INSERT INTO options (title, description) VALUES (?, ?)");
    $stmt->bind_param('ss', $title, $desc);
    if ($stmt->execute()) flash('success', 'Opsi berhasil ditambahkan.');
    else flash('error', 'Gagal menambahkan opsi.');
  }
  header('Location: admin_options.php');
  exit;
}

if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $stmt = $mysqli->prepare("DELETE FROM options WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  flash('success', 'Opsi berhasil dihapus.');
  header('Location: admin_options.php');
  exit;
}

$res = $mysqli->query("SELECT * FROM options ORDER BY created_at DESC");
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Kelola Opsi Voting</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen font-sans">
  <!-- Navbar -->
  <nav class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
      <a href="admin_dashboard.php" class="text-xl font-semibold text-blue-600 hover:text-blue-800 transition">
        🗳️ Voting Admin Panel
      </a>
      <div class="flex items-center space-x-3">
        <span class="text-gray-600">Halo, <b><?php echo e($_SESSION['username']); ?></b></span>
        <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
          Logout
        </a>
      </div>
    </div>
  </nav>

  <main class="max-w-5xl mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Kelola Opsi Voting</h1>
      <a href="admin_dashboard.php" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Dashboard</a>
    </div>

    <!-- Flash messages -->
    <?php if ($m = flash('error')): ?>
      <div class="bg-red-100 text-red-700 p-3 mb-4 rounded-lg border border-red-300"><?php echo e($m); ?></div>
    <?php endif; ?>
    <?php if ($m = flash('success')): ?>
      <div class="bg-green-100 text-green-700 p-3 mb-4 rounded-lg border border-green-300"><?php echo e($m); ?></div>
    <?php endif; ?>

    <!-- Form Tambah Opsi -->
    <div class="bg-white p-6 rounded-xl shadow mb-8">
      <h2 class="text-lg font-semibold mb-4 text-gray-700">Tambah Opsi Baru</h2>
      <form method="post" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Judul Opsi</label>
          <input type="text" name="title" placeholder="Masukkan judul opsi..." required
            class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi (Opsional)</label>
          <textarea name="description" placeholder="Masukkan deskripsi singkat..."
            class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
        <button name="add_option"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
          + Tambah Opsi
        </button>
      </form>
    </div>

    <!-- Daftar Opsi -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-700">Daftar Opsi</h2>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
          <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
              <th class="py-3 px-4">#</th>
              <th class="py-3 px-4">Judul</th>
              <th class="py-3 px-4">Deskripsi</th>
              <th class="py-3 px-4">Jumlah Vote</th>
              <th class="py-3 px-4 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <?php while ($row = $res->fetch_assoc()): ?>
              <tr class="hover:bg-gray-50 transition">
                <td class="py-3 px-4 text-gray-500"><?php echo e($row['id']); ?></td>
                <td class="py-3 px-4 font-medium text-gray-800"><?php echo e($row['title']); ?></td>
                <td class="py-3 px-4 text-gray-600"><?php echo e($row['description']); ?></td>
                <td class="py-3 px-4 text-center text-gray-700 font-semibold"><?php echo e($row['votes_count']); ?></td>
                <td class="py-3 px-4 text-center">
                  <a href="?delete=<?php echo e($row['id']); ?>" onclick="return confirm('Hapus opsi ini?')"
                    class="text-red-600 hover:text-red-800 font-medium">Hapus</a>
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