ALTER TABLE `countries` ADD `image_mobile_url` VARCHAR(255) NULL AFTER `image_url`,
ADD `layout` ENUM ('primary', 'secondary', '', '') NOT NULL DEFAULT 'secondary' AFTER `image_mobile_url`;