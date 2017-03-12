USE `sapling`;

CREATE TABLE `people` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registration` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `profile` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_moderator` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_administrator` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `contact_group_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci