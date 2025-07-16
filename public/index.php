<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$tasks = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🗂️ Todo App - Dark Mode</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen p-6 flex justify-center">

  <div class="w-full max-w-3xl bg-gray-800 rounded-xl shadow-lg p-6 space-y-6">
    <h1 class="text-3xl font-bold text-center text-purple-400 flex justify-center items-center gap-2">
      <i data-feather="list-check"></i> Dark Todo Manager
    </h1>

    <!-- Add Task Form -->
    <form action="add_task.php" method="POST" class="flex flex-col sm:flex-row gap-3">
      <input type="text" name="title" required placeholder="Add a new task..." class="flex-1 px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring focus:ring-purple-500">
      <button class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-all flex items-center gap-1">
        <i data-feather="plus-circle"></i> Add
      </button>
    </form>

    <!-- Task Table -->
    <div id="print-section">
      <table class="w-full text-sm sm:text-base">
        <thead class="bg-gray-700 text-purple-300">
          <tr>
            <th class="text-left py-2 px-3">#</th>
            <th class="text-left py-2 px-3">Task</th>
            <th class="text-left py-2 px-3">Status</th>
            <th class="text-left py-2 px-3">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($tasks): $i = 1; foreach ($tasks as $task): ?>
          <tr class="border-t border-gray-700 hover:bg-gray-700">
            <td class="py-2 px-3"><?= $i++ ?></td>
            <td class="py-2 px-3"><?= h($task['title']) ?></td>
            <td class="py-2 px-3"><?= $task['is_done'] ? '✅ Done' : '⏳ Pending' ?></td>
            <td class="py-2 px-3 space-x-1">
              <form action="toggle_task.php" method="POST" class="inline">
                <input type="hidden" name="id" value="<?= $task['id'] ?>">
                <button class="text-blue-400 hover:underline" title="Toggle"><i data-feather="refresh-cw"></i></button>
              </form>
              <form action="delete_task.php" method="POST" class="inline" onsubmit="return confirm('Delete this task?')">
                <input type="hidden" name="id" value="<?= $task['id'] ?>">
                <button class="text-red-400 hover:underline" title="Delete"><i data-feather="trash"></i></button>
              </form>
              <form action="update_task.php" method="POST" class="inline-flex gap-1 items-center">
                <input type="hidden" name="id" value="<?= $task['id'] ?>">
                <input type="text" name="title" value="<?= h($task['title']) ?>" class="bg-gray-600 text-white border border-gray-500 px-2 py-1 text-sm rounded">
                <button class="text-green-400 hover:underline" title="Edit"><i data-feather="edit-3"></i></button>
              </form>
            </td>
          </tr>
        <?php endforeach; else: ?>
          <tr><td colspan="4" class="py-4 text-center text-gray-400">No tasks added yet.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Email & Print -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4 border-t border-gray-600">
      <form action="send_email.php" method="POST" class="flex gap-2 w-full sm:w-auto">
        <input type="email" name="email" required placeholder="Enter email" class="px-4 py-2 bg-gray-700 text-white border border-gray-500 rounded-lg">
        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center gap-1">
          <i data-feather="send"></i> Email Tasks
        </button>
      </form>
      <button onclick="window.print()" class="bg-gray-700 text-white px-6 py-2 rounded-lg hover:bg-black transition flex items-center gap-1">
        <i data-feather="printer"></i> Print
      </button>
    </div>
  </div>

  <script>feather.replace();</script>
</body>
</html>
