<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$tasks = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>📋 Todo List</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-purple-200 to-indigo-200 min-h-screen py-10">
  <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-lg">
    <h1 class="text-3xl font-bold mb-4 text-purple-700">My Todo List</h1>

    <!-- Add Task -->
    <form action="../src/add_task.php" method="POST" class="flex gap-2 mb-4">
      <input type="text" name="title" required placeholder="Enter task..." class="flex-1 px-4 py-2 border rounded">
      <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">Add</button>
    </form>

    <!-- Tasks Table -->
    <div id="print-section">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-purple-100">
            <th class="p-2">#</th>
            <th class="p-2">Task</th>
            <th class="p-2">Status</th>
            <th class="p-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($tasks): $i = 1; foreach ($tasks as $task): ?>
            <tr class="border-t hover:bg-purple-50">
              <td class="p-2"><?= $i++ ?></td>
              <td class="p-2"><?= h($task['title']) ?></td>
              <td class="p-2"><?= $task['is_done'] ? '✅ Done' : '⏳ Pending' ?></td>
              <td class="p-2 flex gap-1">
                <form action="../src/toggle_task.php" method="POST">
                  <input type="hidden" name="id" value="<?= $task['id'] ?>">
                  <button class="text-blue-600 underline">Toggle</button>
                </form>
                <form action="../src/delete_task.php" method="POST" onsubmit="return confirm('Delete this task?')">
                  <input type="hidden" name="id" value="<?= $task['id'] ?>">
                  <button class="text-red-600 underline">Delete</button>
                </form>
                <form action="../src/update_task.php" method="POST" class="flex gap-1">
                  <input type="hidden" name="id" value="<?= $task['id'] ?>">
                  <input type="text" name="title" value="<?= h($task['title']) ?>" class="border px-2 py-1 rounded text-sm w-32">
                  <button class="text-green-600 underline">Update</button>
                </form>
              </td>
            </tr>
          <?php endforeach; else: ?>
            <tr><td colspan="4" class="p-4 text-center text-gray-500">No tasks found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Buttons -->
    <div class="mt-6 flex justify-between items-center gap-2">
      <form action="../src/send_email.php" method="POST" class="flex gap-2 items-center">
        <input type="email" name="email" placeholder="Email to send tasks" required class="border px-4 py-2 rounded">
        <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Send via Email</button>
      </form>
      <button onclick="window.print()" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-black">🖨️ Print</button>
    </div>
  </div>
</body>
</html>
