-- init.sql
CREATE DATABASE IF NOT EXISTS liumexcode DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE liumexcode;

-- users table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  balance DECIMAL(10,2) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- services table
CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(50) NOT NULL UNIQUE,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  duration VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- orders table
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  service_id INT NOT NULL,
  payload TEXT, -- رقم IMEI أو SN أو الاسم+هاتف
  price DECIMAL(10,2),
  status ENUM('pending','processing','done','cancelled') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- admin user (تستطيع تغييره لاحقاً)
CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE,
  password VARCHAR(255) NOT NULL
);

-- sample services
INSERT INTO services (slug,title,description,price,duration) VALUES
('samsung-unlock','فتح أجهزة سامسونج','مطلوب: IMEI','16.00','1-5m'),
('honor-frp','إزالة FRP لهواتف هونر','مطلوب: SN','10.00','20m'),
('icloud-unlock','فتح iCloud','مطلوب: الاسم + رقم الهاتف','15.00','30m'),
('support','الدعم الفني','استشارة فنية سريعة','0.00','5m');

-- sample admin (password: changeit)
INSERT INTO admins (username, password) VALUES ('admin', '$2y$12$XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
-- ملاحظة: استبدل قيمة password بحاصل bcrypt صحيح عند الإنشاء (أدلة لاحقاً)
