USE `sapling`;

CREATE TABLE `blog` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `author` int(11) unsigned NOT NULL,
 `visible_to` int(11) unsigned DEFAULT NULL,
 `creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `title` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
 `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
 PRIMARY KEY (`id`),
 KEY `author` (`author`),
 KEY `visible_to` (`visible_to`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci