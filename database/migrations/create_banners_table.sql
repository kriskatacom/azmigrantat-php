CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `height` int(11) NOT NULL DEFAULT 520,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL,
  `show_name` tinyint(1) NOT NULL DEFAULT 1,
  `show_description` tinyint(1) NOT NULL DEFAULT 1,
  `show_overlay` tinyint(1) NOT NULL DEFAULT 1,
  `content_place` enum('top_left','top_right','top_center','center_right','bottom_right','bottom_center','bottom_left','center_left','center_center') NOT NULL DEFAULT 'center_center',
  `show_button` tinyint(1) NOT NULL DEFAULT 1,
  `href` varchar(512) DEFAULT NULL,
  `button_text` varchar(20) NOT NULL,
  `group_key` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=170 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
