<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Todo App 🌟</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-gradient-to-br from-purple-100 via-indigo-100 to-blue-100 min-h-screen p-4 flex justify-center items-start">

  <div class="bg-white w-full max-w-3xl rounded-xl shadow-xl p-6 space-y-6">
    <h1 class="text-4xl font-bold text-center text-purple-800 flex items-center justify-center gap-2">
      <i data-feather="check-square"></i> Todo Manager
    </h1>

    <!-- Add Task Form -->
    <form action="add_task.php" method="POST" class="flex flex-col sm:flex-row gap-3">
      <input type="text" name="title" required placeholder="Add new task..." class="flex-1 px-4 py-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
      <button class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-all flex items-center gap-1">
        <i data-feather="plus-circle"></i> Add
      </button>
    </form>

    <!-- Task List -->
    <div id="print-section">
      <table class="w-full text-sm sm:text-base border-collapse">
        <thead class="bg-purple-100 text-purple-800">
          <tr>
            <th class="text-left py-2 px-3">#</th>
            <th class="text-left py-2 px-3">Task</th>
            <th class="text-left py-2 px-3">Status</th>
            <th class="text-left py-2 px-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($tasks): $i = 1; foreach ($tasks as $task): ?>
          <tr class="border-t hover:bg-purple-50">
            <td class="py-2 px-3"><?= $i++ ?></td>
            <td class="py-2 px-3"><?= h($task['title']) ?></td>
            <td class="py-2 px-3"><?= $task['is_done'] ? '✅ Done' : '⏳ Pending' ?></td>
            <td class="py-2 px-3 space-x-2">
              <!-- Toggle -->
              <form action="toggle_task.php" method="POST" class="inline">
                <input type="hidden" name="id" value="<?= $task['id'] ?>">
                <button class="text-blue-600 hover:underline"><i data-feather="refresh-cw"></i></button>
              </form>
              <!-- Delete -->
              <form action="delete_task.php" method="POST" class="inline" onsubmit="return confirm('Delete this task?')">
                <input type="hidden" name="id" value="<?= $task['id'] ?>">
                <button class="text-red-600 hover:underline"><i data-feather="trash-2"></i></button>
              </form>
              <!-- Edit -->
              <form action="update_task.php" method="POST" class="inline-flex gap-1 items-center">
                <input type="hidden" name="id" value="<?= $task['id'] ?>">
                <input type="text" name="title" value="<?= h($task['title']) ?>" class="border px-2 py-1 text-sm rounded">
                <button class="text-green-600 hover:underline"><i data-feather="edit-3"></i></button>
              </form>
            </td>
          </tr>
          <?php endforeach; else: ?>
            <tr><td colspan="4" class="py-4 text-center text-gray-500">No tasks added yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Email & Print -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4 border-t">
      <form action="send_email.php" method="POST" class="flex gap-2 w-full sm:w-auto">
        <input type="email" name="email" required placeholder="Email address" class="px-4 py-2 border rounded-lg flex-1 border-purple-300">
        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center gap-1">
          <i data-feather="send"></i> Send Email
        </button>
      </form>
      <button onclick="window.print()" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-black transition flex items-center gap-1">
        <i data-feather="printer"></i> Print
      </button>
    </div>
  </div>

  <script>feather.replace()</script>
</body>
</html>
