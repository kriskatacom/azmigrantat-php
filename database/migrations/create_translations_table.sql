CREATE TABLE IF NOT EXISTS `translations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `lang_code` VARCHAR(5) NOT NULL,
    `translation_key` VARCHAR(100) NOT NULL,
    `translation_value` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `lang_key_unique` (`lang_code`, `translation_key`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
