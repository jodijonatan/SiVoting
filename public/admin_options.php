<?php
require_once __DIR__ . '/../init.php';
if (!is_admin()) {
  header('Location: admin_login.php');
  exit;
}
require_once __DIR__ . '/../helpers.php';

$page_title = "Kelola Opsi Voting";

/* --- LOGIKA CRUD TETAP SAMA --- */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_option'])) {
  $title = trim($_POST['title']);
  $desc = trim($_POST['description']);

  if ($title === '') {
    flash('error', 'Judul tidak boleh kosong.');
  } else {
    $stmt = $mysqli->prepare("INSERT INTO options (title, description) VALUES (?, ?)");
    $stmt->bind_param('ss', $title, $desc);
    $stmt->execute();
    flash('success', 'Opsi berhasil ditambahkan.');
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_option'])) {
  $id = (int)$_POST['id'];
  $title = trim($_POST['title']);
  $desc = trim($_POST['description']);

  if ($title === '') {
    flash('error', 'Judul tidak boleh kosong.');
  } else {
    $stmt = $mysqli->prepare("UPDATE options SET title = ?, description = ? WHERE id = ?");
    $stmt->bind_param('ssi', $title, $desc, $id);
    $stmt->execute();

    flash('success', 'Opsi berhasil diperbarui.');
  }

  header('Location: admin_options.php');
  exit;
}

$edit_data = null;
if (isset($_GET['edit'])) {
  $id = (int)$_GET['edit'];
  $stmt = $mysqli->prepare("SELECT * FROM options WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $edit_data = $stmt->get_result()->fetch_assoc();
}

$res = $mysqli->query("SELECT * FROM options ORDER BY created_at DESC");
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

<body class="bg-gray-100 min-h-screen font-sans"
  x-data="{ open: true }">

  <!-- SIDEBAR (harus toggle agar cocok dengan header) -->
  <aside
    x-on:sidebar-toggle.window="open = !open"
    :class="open ? 'w-64 p-6' : 'w-20 p-2'"
    class="bg-white shadow-lg h-screen fixed left-0 top-0 transition-all duration-300 flex flex-col">
    <?php include __DIR__ . '/../components/admin_sidebar.php'; ?>
  </aside>

  <!-- HEADER (kode kamu) -->
  <?php include __DIR__ . '/../components/admin_header.php'; ?>

  <!-- MAIN CONTENT -->
  <main
    :class="open ? 'ml-64' : 'ml-20'"
    class="transition-all duration-300 mt-24 p-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Kelola Opsi Voting</h1>

    <!-- FLASH -->
    <?php if ($m = flash('error')): ?>
      <div class="bg-red-100 text-red-700 p-3 mb-4 rounded-lg border border-red-300">
        <?= e($m); ?>
      </div>
    <?php endif; ?>

    <?php if ($m = flash('success')): ?>
      <div class="bg-green-100 text-green-700 p-3 mb-4 rounded-lg border border-green-300">
        <?= e($m); ?>
      </div>
    <?php endif; ?>

    <!-- FORM TAMBAH / EDIT -->
    <div class="bg-white p-6 rounded-xl shadow mb-8">

      <h2 class="text-lg font-semibold mb-4 text-gray-700">
        <?= $edit_data ? 'Edit Opsi' : 'Tambah Opsi Baru'; ?>
      </h2>

      <form method="post" class="space-y-4">
        <?php if ($edit_data): ?>
          <input type="hidden" name="id" value="<?= $edit_data['id']; ?>">
        <?php endif; ?>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Judul Opsi</label>
          <input
            type="text"
            name="title"
            required
            value="<?= $edit_data ? e($edit_data['title']) : ''; ?>"
            placeholder="Masukkan judul opsi..."
            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi (Opsional)</label>
          <textarea
            name="description"
            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500"
            placeholder="Masukkan deskripsi singkat..."><?= $edit_data ? e($edit_data['description']) : ''; ?></textarea>
        </div>

        <button
          name="<?= $edit_data ? 'update_option' : 'add_option'; ?>"
          class="px-4 py-2 text-white rounded-lg transition font-semibold
                 <?= $edit_data ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-blue-500 hover:bg-blue-600'; ?>">
          <?= $edit_data ? 'Simpan Perubahan' : '+ Tambah Opsi'; ?>
        </button>

        <?php if ($edit_data): ?>
          <a href="admin_options.php"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition font-semibold">
            Batal Edit
          </a>
        <?php endif; ?>
      </form>
    </div>

    <!-- TABEL OPSI -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <div class="p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-700">Daftar Opsi</h2>
      </div>

      <table class="min-w-full text-left text-sm">
        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
          <tr>
            <th class="py-3 px-4">ID</th>
            <th class="py-3 px-4">Judul</th>
            <th class="py-3 px-4">Deskripsi</th>
            <th class="py-3 px-4">Jumlah Vote</th>
            <th class="py-3 px-4 text-center">Aksi</th>
          </tr>
        </thead>

        <tbody class="divide-y">
          <?php while ($row = $res->fetch_assoc()): ?>
            <tr class="hover:bg-gray-50 transition">

              <td class="py-3 px-4 text-gray-500"><?= e($row['id']); ?></td>
              <td class="py-3 px-4 font-medium text-gray-800"><?= e($row['title']); ?></td>
              <td class="py-3 px-4 text-gray-600"><?= e($row['description']); ?></td>
              <td class="py-3 px-4 text-gray-700 font-semibold"><?= e($row['votes_count']); ?></td>

              <td class="py-3 px-4 text-center space-x-4">

                <a
                  href="?edit=<?= e($row['id']); ?>"
                  class="px-3 py-1 text-xs font-semibold text-white rounded-full bg-yellow-500 hover:bg-yellow-600">
                  Edit
                </a>

                <a
                  href="?delete=<?= e($row['id']); ?>"
                  onclick="return confirm('Hapus opsi ini?')"
                  class="px-3 py-1 text-xs font-semibold text-white rounded-full bg-red-500 hover:bg-red-600">
                  Hapus
                </a>

              </td>

            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </main>

</body>

</html>