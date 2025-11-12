<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if (!$username || !$email || !$password) { die('مفقود'); }

  // تحقق من وجود المستخدم
  $stmt = $pdo->prepare("SELECT id FROM users WHERE username=? OR email=? LIMIT 1");
  $stmt->execute([$username,$email]);
  if ($stmt->fetch()) {
    die('اسم المستخدم أو الإيميل موجود بالفعل');
  }

  $hash = password_hash($password, PASSWORD_BCRYPT);
  $stmt = $pdo->prepare("INSERT INTO users (username,email,password,balance) VALUES (?,?,?,0)");
  $stmt->execute([$username,$email,$hash]);

  header('Location: login.php?registered=1');
  exit;
}
?>
<!-- نموذج بسيط -->
<!doctype html>
<html lang="ar" dir="rtl">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body>
<form method="post">
  <input name="username" placeholder="اسم المستخدم" required><br>
  <input name="email" type="email" placeholder="البريد الإلكتروني" required><br>
  <input name="password" type="password" placeholder="كلمة المرور" required><br>
  <button>تسجيل</button>
</form>
</body>
</html>
