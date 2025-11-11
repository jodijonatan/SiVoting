<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<aside class="w-64 bg-white shadow-lg h-screen fixed left-0 top-0 p-6 flex flex-col">

  <!-- Logo -->
  <h1 class="text-2xl font-bold text-gray-800 mb-10">SiVoting Admin</h1>

  <!-- Navigation -->
  <nav class="flex-1">
    <ul class="space-y-2">

      <!-- Dashboard -->
      <li>
        <a href="admin_dashboard.php"
          class="flex items-center gap-3 p-3 rounded-xl transition
           <?php echo $current == 'admin_dashboard.php' ? 'bg-indigo-600 text-white' : 'hover:bg-indigo-100 hover:text-indigo-600 text-gray-700'; ?>">
          <i class="ph ph-house text-xl"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <!-- Manage Voters -->
      <li>
        <a href="admin_voters.php"
          class="flex items-center gap-3 p-3 rounded-xl transition
           <?php echo $current == 'admin_voters.php' ? 'bg-indigo-600 text-white' : 'hover:bg-indigo-100 hover:text-indigo-600 text-gray-700'; ?>">
          <i class="ph ph-users text-xl"></i>
          <span>Manage Voters</span>
        </a>
      </li>

      <!-- Manage Options -->
      <li>
        <a href="admin_options.php"
          class="flex items-center gap-3 p-3 rounded-xl transition
           <?php echo $current == 'admin_options.php' ? 'bg-indigo-600 text-white' : 'hover:bg-indigo-100 hover:text-indigo-600 text-gray-700'; ?>">
          <i class="ph ph-list text-xl"></i>
          <span>Manage Options</span>
        </a>
      </li>

      <!-- Results -->
      <li>
        <a href="admin_results.php"
          class="flex items-center gap-3 p-3 rounded-xl transition
           <?php echo $current == 'admin_results.php' ? 'bg-indigo-600 text-white' : 'hover:bg-indigo-100 hover:text-indigo-600 text-gray-700'; ?>">
          <i class="ph ph-chart-bar text-xl"></i>
          <span>Results</span>
        </a>
      </li>

    </ul>
  </nav>

  <!-- Logout -->
  <div class="mt-auto">
    <a href="admin_logout.php"
      class="flex items-center gap-3 p-3 rounded-xl text-red-600 hover:bg-red-100 transition">
      <i class="ph ph-sign-out text-xl"></i>
      <span>Logout</span>
    </a>
  </div>

</aside>