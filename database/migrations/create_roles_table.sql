CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `label` VARCHAR(50) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`name`, `label`) VALUES 
('admin', 'Администратор'),
('driver', 'Шофьор'),
('user', 'Потребител'),
('entrepreneur', 'Предприемач'),
('moderator', 'Модератор');

ALTER TABLE `users` ADD COLUMN `role_id` INT DEFAULT 3 AFTER `username`;
ALTER TABLE `users` ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`);

UPDATE `users` SET `role_id` = 1 WHERE `role` = 'admin';
UPDATE `users` SET `role_id` = 3 WHERE `role` = 'user';

ALTER TABLE `users` DROP COLUMN `role`;
