<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/functions.php';

$tasks      = $pdo->query('SELECT * FROM tasks ORDER BY created_at DESC')->fetchAll();
$total      = count($tasks);
$completed  = array_sum(array_column($tasks, 'is_done'));
$remaining  = $total - $completed;
$percent    = $total ? round($completed / $total * 100) : 0;
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Colorful To-Do</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    @media print {
      body { background: none !important; }
      .no-print { display: none !important; }
      .print\:block { display: block !important; }
    }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-violet-500 via-fuchsia-500 to-rose-500 flex items-center justify-center py-10 px-3">

  <div class="w-full max-w-xl bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl px-8 py-10 ring-1 ring-white/40">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-3xl font-extrabold text-gray-800 flex items-center gap-2">
        <svg class="w-8 h-8 stroke-violet-600" fill="none" stroke-width="2"><use href="#check"/></svg>
        My To‑Do List
      </h1>
      <button onclick="window.print()" class="no-print px-3 py-2 rounded-lg text-sm bg-emerald-600 text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
        Print
      </button>
    </div>

    <!-- Stats -->
    <div class="mb-4 space-y-2">
      <p class="text-sm text-gray-600"><?= $remaining ?> / <?= $total ?> tasks left</p>
      <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
        <div style="width: <?= $percent ?>%" class="h-full <?= $percent==100 ? 'bg-emerald-500' : 'bg-violet-500' ?> transition-all"></div>
      </div>
    </div>

    <!-- Add task form -->
    <form action="../src/add_task.php" method="POST" class="flex gap-2 no-print">
      <input type="text" name="title" placeholder="What do you need to do?" required class="flex-1 rounded-xl border border-gray-300 focus:border-violet-500 focus:ring-violet-500">
      <button class="px-4 py-2 rounded-xl bg-violet-600 text-white font-semibold hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
        Add
      </button>
    </form>

    <!-- 📩 Email form -->
    <form action="../src/send_email.php" method="POST" class="mt-4 no-print flex gap-2">
      <input type="email" name="email" required placeholder="Enter email to send tasks"
             class="flex-1 rounded-xl border border-gray-300 focus:border-violet-500 focus:ring-violet-500">
      <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        Send Tasks
      </button>
    </form>

    <!-- Filter buttons -->
    <div class="no-print mt-6 flex space-x-2 text-sm font-medium">
      <button data-filter="all" class="tab active">All</button>
      <button data-filter="active" class="tab">Active</button>
      <button data-filter="complete" class="tab">Completed</button>
    </div>

    <!-- Task list -->
    <ul id="taskList" class="mt-6 space-y-3">
      <?php if (!$tasks): ?>
        <li class="text-gray-600">No tasks yet – start by adding one above ✨</li>
      <?php endif; ?>
      <?php $i = 1; foreach ($tasks as $task): ?>
        <li data-done="<?= $task['is_done'] ?>" class="task flex items-center justify-between bg-white shadow-sm rounded-xl px-4 py-3 ring-1 ring-gray-200 hover:ring-violet-400 transition">
          <div class="flex items-center gap-3">
            <span class="w-6 text-gray-500 font-semibold"><?= $i++ ?>.</span>

            <!-- Edit form (hidden by default) -->
            <form action="../src/update_task.php" method="POST" class="editForm hidden flex items-center gap-2">
              <input type="hidden" name="id" value="<?= $task['id'] ?>">
              <input name="title" value="<?= h($task['title']) ?>" required class="rounded-lg border px-2 py-1 w-48 text-sm focus:outline-none focus:ring focus:border-violet-500">
              <button class="text-sm px-2 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700">Save</button>
            </form>

            <!-- View mode -->
            <div class="viewMode flex items-center gap-2">
              <form action="../src/toggle_task.php" method="POST" class="no-print">
                <input type="hidden" name="id" value="<?= $task['id'] ?>">
                <button class="p-2 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500">
                  <?php if ($task['is_done']): ?>
                    <svg class="w-5 h-5 text-emerald-600"><use href="#checked"/></svg>
                  <?php else: ?>
                    <svg class="w-5 h-5 text-gray-400"><use href="#unchecked"/></svg>
                  <?php endif; ?>
                </button>
              </form>
              <span class="<?= $task['is_done'] ? 'line-through text-gray-400' : 'text-gray-800' ?>">
                <?= h($task['title']) ?>
              </span>
              <button class="text-sm text-blue-600 hover:underline editBtn no-print">✏️</button>
            </div>
          </div>
          <form action="../src/delete_task.php" method="POST" class="no-print">
            <input type="hidden" name="id" value="<?= $task['id'] ?>">
            <button class="p-2 rounded-full hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500">
              <svg class="w-5 h-5 text-red-600"><use href="#trash"/></svg>
            </button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <!-- SVG Icons -->
  <svg class="hidden">
    <symbol id="check" viewBox="0 0 24 24" stroke="currentColor"><path d="M5 13l4 4L19 7"/></symbol>
    <symbol id="unchecked" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="4" width="16" height="16" rx="3"/></symbol>
    <symbol id="checked" viewBox="0 0 24 24" fill="currentColor">
      <path d="M9 11l3 3L22 4"/><rect x="2" y="2" width="20" height="20" rx="3" class="opacity-25"/>
    </symbol>
    <symbol id="trash" viewBox="0 0 24 24" stroke="currentColor"><path d="M3 6h18M9 6v12a2 2 0 002 2h2a2 2 0 002-2V6"/></symbol>
  </svg>

  <!-- Scripts -->
  <script>
    // Tab filter
    const tabs = document.querySelectorAll('.tab');
    const tasks = document.querySelectorAll('.task');
    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active', 'bg-violet-600', 'text-white'));
        tab.classList.add('active', 'bg-violet-600', 'text-white');

        const mode = tab.dataset.filter;
        tasks.forEach(li => {
          const done = li.dataset.done === '1';
          li.style.display =
            mode === 'all' ? '' :
            mode === 'active' && !done ? '' :
            mode === 'complete' && done ? '' : 'none';
        });
      });
    });

    // Inline edit toggle
    document.querySelectorAll('.editBtn').forEach(btn => {
      btn.addEventListener('click', () => {
        const view = btn.closest('.viewMode');
        const edit = view.previousElementSibling;
        view.classList.add('hidden');
        edit.classList.remove('hidden');
        edit.querySelector('input[name="title"]').focus();
      });
    });
  </script>

  <style>
    .tab {
      @apply px-3 py-1.5 rounded-lg text-gray-600 hover:bg-violet-50 cursor-pointer;
    }
  </style>
</body>
</html>
