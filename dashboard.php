<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) header('Location: login.php');

$userId = $_SESSION['user_id'];
$userStmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$userStmt->execute([$userId]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

// load services
$srvStmt = $pdo->query("SELECT * FROM services ORDER BY id");
$services = $srvStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body>
<h2>مرحباً، <?=htmlspecialchars($user['username'])?> — رصيد: <?=number_format($user['balance'],2)?> $</h2>
<a href="logout.php">خروج</a>
<hr>
<?php foreach($services as $s): ?>
  <div style="border:1px solid #eee;padding:12px;margin:10px;border-radius:8px;">
    <h3><?=htmlspecialchars($s['title'])?> — <?=htmlspecialchars($s['duration'])?></h3>
    <p><?=nl2br(htmlspecialchars($s['description']))?></p>
    <p><strong>السعر: <?=number_format($s['price'],2)?> $</strong></p>

    <form method="post" action="order.php">
      <input type="hidden" name="service_id" value="<?=$s['id']?>">
      <!-- payload input varies -->
      <?php if($s['slug']=='samsung-unlock'): ?>
        <input name="payload" placeholder="أدخل رقم IMEI" required>
      <?php elseif($s['slug']=='honor-frp'): ?>
        <input name="payload" placeholder="أدخل الرقم التسلسلي SN" required>
      <?php elseif($s['slug']=='icloud-unlock'): ?>
        <input name="payload_name" placeholder="الاسم الكامل" required><br>
        <input name="payload_phone" placeholder="رقم الهاتف" required>
      <?php else: ?>
        <input name="payload" placeholder="معلومات الطلب" required>
      <?php endif; ?>

      <button type="submit">أرسل الطلب</button>
    </form>
  </div>
<?php endforeach; ?>
</body></html>
