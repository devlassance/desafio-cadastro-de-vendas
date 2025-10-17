-- Ensure the application database exists on first run
CREATE DATABASE IF NOT EXISTS `sales` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Optionally create a dedicated user (commented because root is used in .env)
-- CREATE USER IF NOT EXISTS 'sales'@'%' IDENTIFIED BY 'sales';
-- GRANT ALL PRIVILEGES ON `sales`.* TO 'sales'@'%';
-- FLUSH PRIVILEGES;
