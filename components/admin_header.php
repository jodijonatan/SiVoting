<header
  x-data="{ open: true }"
  x-on:sidebar-toggle.window="open = !open"
  :style="open ? 'left: 16rem;' : 'left: 5rem;'"
  class="fixed top-0 right-0 h-20 bg-gray-50 shadow-sm
         flex items-center justify-between px-6
         z-20 transition-all duration-300">

  <!-- Left -->
  <div class="flex items-center gap-4">

    <h1 class="text-xl font-semibold text-gray-800">
      <?= e($page_title) ?>
    </h1>
  </div>

  <!-- Right -->
  <div class="flex items-center gap-4">

    <a href="../public/logout.php"
      class="flex items-center gap-2 bg-red-500 hover:bg-red-600
             text-white px-4 py-2 rounded-lg transition">
      <i class="ph ph-sign-out"></i>
      <span class="hidden sm:inline">Logout</span>
    </a>

    <div class="w-10 h-10 rounded-full bg-gray-300 overflow-hidden">
      <img src="https://ui-avatars.com/api/?name=Cekuang"
        class="w-full h-full object-cover" />
    </div>

  </div>
</header>