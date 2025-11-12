<?php
require 'config.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $username = $_POST['username']; $password = $_POST['password'];
  $stmt = $pdo->prepare("SELECT * FROM admins WHERE username=? LIMIT 1");
  $stmt->execute([$username]);
  $a = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($a && password_verify($password, $a['password'])) {
      $_SESSION['admin_id'] = $a['id'];
      header('Location: admin_panel.php');
      exit;
  } else $err = "غير مسموح";
}
?>
<form method="post">
  <input name="username" placeholder="Admin user" required>
  <input name="password" type="password" placeholder="password" required>
  <button>دخول إدارة</button>
  <?php if(!empty($err)) echo "<p>$err</p>"; ?>
</form>
