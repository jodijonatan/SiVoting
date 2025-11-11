<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<aside
  class="bg-white shadow-lg h-screen fixed left-0 top-0 w-64 p-6 flex flex-col">

  <!-- Logo -->
  <div class="mb-10 flex items-center gap-3">
    <img src="../assets/favicon.png" alt="Logo" class="w-10 h-10 object-contain">
    <h1 class="text-2xl font-bold text-gray-800">SiVoting</h1>
  </div>

  <!-- Navigation -->
  <nav class="flex-1">
    <ul class="space-y-2">

      <!-- Dashboard -->
      <li>
        <a
          href="admin_dashboard.php"
          class="flex items-center gap-3 p-3 rounded-xl transition
            <?= $current == 'admin_dashboard.php'
              ? 'bg-indigo-600 text-white'
              : 'hover:bg-indigo-100 hover:text-indigo-600 text-gray-700' ?>">
          <i class="ph ph-house text-xl"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <!-- Manage Voters -->
      <li>
        <a
          href="admin_voters.php"
          class="flex items-center gap-3 p-3 rounded-xl transition
            <?= $current == 'admin_voters.php'
              ? 'bg-indigo-600 text-white'
              : 'hover:bg-indigo-100 hover:text-indigo-600 text-gray-700' ?>">
          <i class="ph ph-users text-xl"></i>
          <span>Manage Voters</span>
        </a>
      </li>

      <!-- Manage Options -->
      <li>
        <a
          href="admin_options.php"
          class="flex items-center gap-3 p-3 rounded-xl transition
            <?= $current == 'admin_options.php'
              ? 'bg-indigo-600 text-white'
              : 'hover:bg-indigo-100 hover:text-indigo-600 text-gray-700' ?>">
          <i class="ph ph-list text-xl"></i>
          <span>Manage Options</span>
        </a>
      </li>

      <!-- Results -->
      <li>
        <a
          href="admin_results.php"
          class="flex items-center gap-3 p-3 rounded-xl transition
            <?= $current == 'admin_results.php'
              ? 'bg-indigo-600 text-white'
              : 'hover:bg-indigo-100 hover:text-indigo-600 text-gray-700' ?>">
          <i class="ph ph-chart-bar text-xl"></i>
          <span>Results</span>
        </a>
      </li>

    </ul>
  </nav>

</aside>