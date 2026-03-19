ALTER TABLE `companies` ADD `email` VARCHAR(255) NULL AFTER `website_link`,
ADD `phone` VARCHAR(255) NULL AFTER `email`,
ADD `address` VARCHAR(255) NULL AFTER `phone`,
ADD `working_time` VARCHAR(255) NULL AFTER `address`;

ALTER TABLE `companies` DROP `contacts_content`;
