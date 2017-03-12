USE `sapling`;

CREATE TABLE `forum_post` (
  `id` int(11) UNSIGNED NOT NULL,
  `thread_id` int(11) UNSIGNED NOT NULL,
  `person_id` int(10) UNSIGNED NOT NULL,
  `creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `removed` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `removed_by` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `thread` (`thread_id`),
  KEY `person` (`person_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `forum_thread` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;