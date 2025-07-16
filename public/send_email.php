<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// PHPMailer manual includes
require_once __DIR__ . '/../src/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../src/PHPMailer/SMTP.php';
require_once __DIR__ . '/../src/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = trim($_POST['email'] ?? '');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit('Invalid email address');
}

// Fetch tasks
$tasks = $pdo->query('SELECT * FROM tasks ORDER BY created_at DESC')->fetchAll();
if (!$tasks) exit('No tasks found');

$body = '<h2 style="color:#6D28D9;">📝 Your To‑Do List</h2>';
$body .= '<table style="width:100%;border-collapse:collapse;font-family:sans-serif;">
<thead>
  <tr style="background:#eee;">
    <th style="padding:8px;text-align:left;">#</th>
    <th style="padding:8px;text-align:left;">Task</th>
    <th style="padding:8px;text-align:left;">Status</th>
  </tr>
</thead><tbody>';

$i = 1;
foreach ($tasks as $task) {
    $status = $task['is_done'] ? '✅ Completed' : '⏳ Pending';
    $body .= "<tr>
        <td style='padding:8px;border-bottom:1px solid #ddd;'>$i</td>
        <td style='padding:8px;border-bottom:1px solid #ddd;'>" . h($task['title']) . "</td>
        <td style='padding:8px;border-bottom:1px solid #ddd;'>$status</td>
    </tr>";
    $i++;
}
$body .= '</tbody></table><p style="font-size:12px;color:#888;">Sent from your PHP Todo App</p>';

// Send email
try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'emailfromtodo@gmail.com';      // ✅ Gmail
    $mail->Password = 'nbli bjnn uziv mcsi';           // ✅ App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('emailfromtodo@gmail.com', 'My Todo App');  // ✅ Should match username
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = '📝 Your Todo List';
    $mail->Body = $body;

    $mail->send();

    echo "<script>alert('Tasks sent to $email successfully!'); window.location.replace('../public/index.php');</script>";
} catch (Exception $e) {
    echo "<script>alert('Email error: {$mail->ErrorInfo}'); window.location.replace('../public/index.php');</script>";
}
