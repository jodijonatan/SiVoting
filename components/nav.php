<?php
// components/nav.php
// Pastikan session sudah start di halaman yang include nav.php
require_once __DIR__ . '/../helpers.php'; // untuk fungsi e()

// Tentukan role dan nama user
$is_admin = is_admin();           // true jika admin
$is_voter = is_voter();           // true jika voter
$user_name = $is_admin ? $_SESSION['username'] : ($_SESSION['voter_name'] ?? 'Guest');

// Link dashboard sesuai role
$dashboard_link = $is_admin ? 'admin_dashboard.php' : 'voter_dashboard.php';
$dashboard_label = $is_admin ? 'Admin' : 'Voter';
?>
<nav class="bg-white shadow sticky top-0 z-50">
  <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
    <div class="flex items-center gap-2 text-indigo-600 font-bold text-xl">
      <a href="<?php echo $dashboard_link; ?>" class="text-xl font-semibold text-blue-600 hover:text-blue-800 transition">
        🗳️ <?php echo $dashboard_label; ?> - Dashboard
      </a>
    </div>
    <div class="flex items-center space-x-3">
      <!-- <span class="text-gray-600">Halo, <b><?php echo e($user_name); ?></b></span> -->
      <a href="../public/logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
        Logout
      </a>
    </div>
  </div>
</nav>