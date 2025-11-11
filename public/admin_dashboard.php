<?php
require_once __DIR__ . '/../init.php';
if (!is_admin()) {
  header('Location: admin_login.php');
  exit;
}
require_once __DIR__ . '/../helpers.php';

// ✅ Judul untuk header dinamis
$page_title = "Dashboard";
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title><?= e($page_title) ?> - Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="../assets/favicon.png">
  <script src="https://unpkg.com/@phosphor-icons/web"></script>

  <!-- ✅ Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 min-h-screen font-sans flex">

  <!-- ✅ Include Sidebar -->
  <?php include __DIR__ . '/../components/admin_sidebar.php'; ?>

  <!-- ✅ Wrapper untuk Header + Main -->
  <div class="flex-1">

    <!-- ✅ HEADER (full width, fixed) -->
    <?php include __DIR__ . '/../components/admin_header.php'; ?>

    <!-- ✅ MAIN CONTENT -->
    <main
      x-data="{ open: true }"
      x-on:sidebar-toggle.window="open = !open"
      :class="open ? 'ml-64' : 'ml-20'"
      class="p-8 pt-32 min-h-screen transition-all duration-300">

      <!-- ✅ Cards Dashboard -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Card 1 -->
        <a href="admin_voters.php"
          class="group p-6 bg-white rounded-2xl shadow hover:shadow-lg transition transform hover:-translate-y-1">
          <div class="flex items-center gap-4">
            <div class="bg-indigo-100 p-3 rounded-xl text-indigo-600">
              <i class="ph ph-users text-2xl"></i>
            </div>
            <div>
              <h2 class="text-lg font-semibold text-gray-800 group-hover:text-indigo-600 transition">Manage Voters</h2>
              <p class="text-sm text-gray-500">Add, edit, or remove voter data</p>
            </div>
          </div>
        </a>

        <!-- Card 2 -->
        <a href="admin_options.php"
          class="group p-6 bg-white rounded-2xl shadow hover:shadow-lg transition transform hover:-translate-y-1">
          <div class="flex items-center gap-4">
            <div class="bg-emerald-100 p-3 rounded-xl text-emerald-600">
              <i class="ph ph-list text-2xl"></i>
            </div>
            <div>
              <h2 class="text-lg font-semibold text-gray-800 group-hover:text-emerald-600 transition">Manage Options</h2>
              <p class="text-sm text-gray-500">Set up or update voting options</p>
            </div>
          </div>
        </a>

        <!-- Card 3 -->
        <a href="admin_results.php"
          class="group p-6 bg-white rounded-2xl shadow hover:shadow-lg transition transform hover:-translate-y-1">
          <div class="flex items-center gap-4">
            <div class="bg-rose-100 p-3 rounded-xl text-rose-600">
              <i class="ph ph-chart-bar text-2xl"></i>
            </div>
            <div>
              <h2 class="text-lg font-semibold text-gray-800 group-hover:text-rose-600 transition">Results</h2>
              <p class="text-sm text-gray-500">View and analyze vote results</p>
            </div>
          </div>
        </a>

      </div>

      <!-- Footer -->
      <footer class="mt-12 text-center text-gray-500 text-sm">
        © <?= date('Y') ?> SiVoting. All rights reserved.
      </footer>

    </main>
  </div>

</body>

</html>