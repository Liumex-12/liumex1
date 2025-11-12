<?php
// config.php
session_start();
date_default_timezone_set('UTC');

$DB_HOST = 'localhost';
$DB_NAME = 'liumexcode';
$DB_USER = 'dbuser';
$DB_PASS = 'dbpass';

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    die("DB connection failed: " . $e->getMessage());
}

/*
 Email (SMTP) settings - استخدم SMTP حقيقي (مثلاً Gmail with App Password أو Mailgun/SendGrid)
*/
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USER', 'liumexcode@gmail.com'); // بريدك
define('MAIL_PASS', 'your_smtp_password');  // استخدم App Password إن كان Gmail
define('MAIL_FROM', 'liumexcode@gmail.com');
define('MAIL_FROM_NAME', 'Liumexcode');
?>
