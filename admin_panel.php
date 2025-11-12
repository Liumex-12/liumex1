<?php
require 'config.php';
if (!isset($_SESSION['admin_id'])) header('Location: admin_login.php');

// show orders
$stmt = $pdo->query("SELECT o.*, u.username,u.email, s.title FROM orders o
  JOIN users u ON u.id=o.user_id
  JOIN services s ON s.id=o.service_id
  ORDER BY o.created_at DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>لوحة الإدارة</h2>
<a href="admin_logout.php">خروج</a>
<table border="1" cellpadding="6" cellspacing="0">
  <tr><th>ID</th><th>User</th><th>Service</th><th>Payload</th><th>Price</th><th>Status</th><th>Action</th></tr>
  <?php foreach($orders as $o): ?>
  <tr>
    <td><?=$o['id']?></td>
    <td><?=$o['username']?> (<?=$o['email']?>)</td>
    <td><?=$o['title']?></td>
    <td><?=nl2br(htmlspecialchars($o['payload']))?></td>
    <td><?=$o['price']?></td>
    <td><?=$o['status']?></td>
    <td>
      <form method="post" action="admin_action.php" style="display:inline">
        <input type="hidden" name="order_id" value="<?=$o['id']?>">
        <select name="status">
          <option value="pending" <?= $o['status']=='pending'?'selected':''?>>pending</option>
          <option value="processing" <?= $o['status']=='processing'?'selected':''?>>processing</option>
          <option value="done" <?= $o['status']=='done'?'selected':''?>>done</option>
          <option value="cancelled" <?= $o['status']=='cancelled'?'selected':''?>>cancelled</option>
        </select>
        <button>تحديث</button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
