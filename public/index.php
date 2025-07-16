<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$tasks = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Todo App 🌟</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    @media print {
      body * { visibility: hidden; }
      #print-section, #print-section * { visibility: visible; }
      #print-section { position: absolute; top: 0; left: 0; width: 100%; }
    }
  </style>
</head>
<body class="bg-gradient-to-br from-indigo-200 to-purple-300 min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-3xl bg-white bg-opacity-80 shadow-2xl backdrop-blur-lg rounded-2xl p-6 space-y-6">

    <h1 class="text-4xl font-bold text-center text-purple-800">📝 My Todo List</h1>

    <!-- Add Task -->
    <form action="add_task.php" method="POST" class="flex flex-col sm:flex-row gap-3">
      <input type="text" name="title" required placeholder="Enter new task..." class="flex-1 px-4 py-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
      <button class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-all">Add Task</button>
    </form>

    <!-- Task Table -->
    <div id="print-section">
      <table class="w-full table-auto border-collapse text-sm sm:text-base">
        <thead>
          <tr class="bg-purple-100 text-purple-800">
            <th class="p-3 text-left">#</th>
            <th class="p-3 text-left">Task</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($tasks): $i = 1; foreach ($tasks as $task): ?>
            <tr class="border-t hover:bg-purple-50">
              <td class="p-3 font-semibold text-gray-700"><?= $i++ ?></td>
              <td class="p-3 text-gray-800"><?= h($task['title']) ?></td>
              <td class="p-3"><?= $task['is_done'] ? '✅ Done' : '⏳ Pending' ?></td>
              <td class="p-3 space-x-2 flex flex-wrap items-center">
                <!-- Toggle -->
                <form action="toggle_task.php" method="POST">
                  <input type="hidden" name="id" value="<?= $task['id'] ?>">
                  <button class="text-blue-600 hover:underline">Toggle</button>
                </form>
                <!-- Delete -->
                <form action="delete_task.php" method="POST" onsubmit="return confirm('Delete this task?')">
                  <input type="hidden" name="id" value="<?= $task['id'] ?>">
                  <button class="text-red-600 hover:underline">Delete</button>
                </form>
                <!-- Edit -->
                <form action="update_task.php" method="POST" class="flex gap-1 items-center">
                  <input type="hidden" name="id" value="<?= $task['id'] ?>">
                  <input type="text" name="title" value="<?= h($task['title']) ?>" class="px-2 py-1 border border-purple-300 rounded text-sm">
                  <button class="text-green-600 hover:underline">Update</button>
                </form>
              </td>
            </tr>
          <?php endforeach; else: ?>
            <tr><td colspan="4" class="p-4 text-center text-gray-500">No tasks yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Buttons -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4 border-t">
      <!-- Email Form -->
      <form action="send_email.php" method="POST" class="flex gap-2 w-full sm:w-auto">
        <input type="email" name="email" required placeholder="Email address" class="px-4 py-2 border border-purple-300 rounded-lg flex-1">
        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">Send Email</button>
      </form>
      <!-- Print Button -->
      <button onclick="window.print()" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-black transition">🖨️ Print</button>
    </div>

  </div>
</body>
</html>
