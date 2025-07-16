<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// PHPMailer manual includes
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = trim($_POST['email'] ?? '');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit('Invalid email');
}

// Fetch tasks
$tasks = $pdo->query('SELECT * FROM tasks ORDER BY created_at DESC')->fetchAll();
if (!$tasks) exit('No tasks to email');

// Create email content
$body = '<h2 style="color:#6D28D9;">📝 Your To‑Do Tasks</h2>';
$body .= '<table style="width:100%;border-collapse:collapse;font-family:sans-serif;">
<thead><tr style="background:#eee;"><th style="padding:8px;text-align:left;">#</th><th style="padding:8px;text-align:left;">Task</th><th style="padding:8px;text-align:left;">Status</th></tr></thead><tbody>';

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
$body .= '</tbody></table><p style="font-size:12px;color:#888;">Sent from your PHP To‑Do App</p>';

// Send email
try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // 🔐 Your Gmail and App Password here:
    $mail->Username = 'emailfromtodo@gmail.com';
    $mail->Password = 'nbli bjnn uziv mcsi';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('your@gmail.com', 'My Todo App');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = '📝 Your To‑Do List';
    $mail->Body = $body;

    $mail->send();

    echo "<script>alert('Tasks sent to $email successfully!'); window.location.replace('../public/index.php');</script>";
} catch (Exception $e) {
    echo "<script>alert('Email error: {$mail->ErrorInfo}'); window.location.replace('../public/index.php');</script>";
}
