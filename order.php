<?php
require 'config.php';
require 'vendor/PHPMailer/PHPMailerAutoload.php'; // عدّل المسار بحسب تركيبك

if (!isset($_SESSION['user_id'])) die('غير مسجل');

$userId = $_SESSION['user_id'];
$service_id = intval($_POST['service_id']);

$s = $pdo->prepare("SELECT * FROM services WHERE id=? LIMIT 1");
$s->execute([$service_id]);
$service = $s->fetch(PDO::FETCH_ASSOC);
if (!$service) die('خدمة غير موجودة');

// تجميع payload
if ($service['slug']=='icloud-unlock') {
  $name = trim($_POST['payload_name'] ?? '');
  $phone = trim($_POST['payload_phone'] ?? '');
  $payload = "Name: $name\nPhone: $phone";
} else {
  $payload = trim($_POST['payload'] ?? '');
}

if (!$payload) die('الرجاء إدخال البيانات المطلوبة');

// جلب رصيد المستخدم
$u = $pdo->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
$u->execute([$userId]);
$user = $u->fetch(PDO::FETCH_ASSOC);

// التأكد من الرصيد
if ($user['balance'] < $service['price']) {
    die('رصيد غير كافٍ. الرجاء شحن الرصيد أولاً.');
}

// خصم الرصيد و إنشاء الطلب داخل TRANSACTION
$pdo->beginTransaction();
try {
    $insert = $pdo->prepare("INSERT INTO orders (user_id, service_id, payload, price, status) VALUES (?,?,?,?, 'pending')");
    $insert->execute([$userId, $service['id'], $payload, $service['price']]);

    $newBalance = $user['balance'] - $service['price'];
    $upd = $pdo->prepare("UPDATE users SET balance=? WHERE id=?");
    $upd->execute([$newBalance, $userId]);

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    die("خطأ: " . $e->getMessage());
}

// إرسال إيميل للمدير (liumexcode@gmail.com)
$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host = MAIL_HOST;
$mail->SMTPAuth = true;
$mail->Username = MAIL_USER;
$mail->Password = MAIL_PASS;
$mail->SMTPSecure = 'tls';
$mail->Port = MAIL_PORT;

$mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
$mail->addAddress(MAIL_FROM); // ترسل لنفسك
$mail->Subject = "طلب خدمة جديد: {$service['title']}";
$body = "يوجد طلب جديد\n\nService: {$service['title']}\nFrom user: {$user['username']} ({$user['email']})\nPrice: {$service['price']}\nPayload:\n{$payload}\n\nCheck admin panel for more.";
$mail->Body = $body;
if (!$mail->send()) {
    // لم يتم إرسال الإيميل لكن الطلب محفوظ—أعد المحاولة لاحقًا
    error_log("Mail error: " . $mail->ErrorInfo);
}

// تحويل المستخدم لصفحة التأكيد
header('Location: dashboard.php?ordered=1');
exit;
