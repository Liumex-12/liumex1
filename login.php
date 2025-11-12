<?php
require 'config.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  $stmt = $pdo->prepare("SELECT * FROM users WHERE username=? OR email=? LIMIT 1");
  $stmt->execute([$username,$username]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    header('Location: dashboard.php');
    exit;
  } else {
    $error = "اسم المستخدم أو كلمة المرور خاطئة";
  }
}
?>
<!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body>
<?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
  <input name="username" placeholder="اسم المستخدم أو البريد" required><br>
  <input name="password" type="password" placeholder="كلمة المرور" required><br>
  <button>دخول</button>
</form>
<a href="register.php">إنشاء حساب جديد</a>
</body></html>
