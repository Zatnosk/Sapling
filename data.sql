--
-- Database: `sapling`
--
CREATE DATABASE IF NOT EXISTS `sapling` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sapling`;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `forum_post`
--

CREATE TABLE `forum_post` (
  `id` int(11) UNSIGNED NOT NULL,
  `thread_id` int(11) UNSIGNED NOT NULL,
  `person_id` int(10) UNSIGNED NOT NULL,
  `creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `removed` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `removed_by` int(11) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `forum_thread`
--

CREATE TABLE `forum_thread` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` tinytext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `people`
--

CREATE TABLE `people` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` char(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registration` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `profile` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_moderator` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `is_administrator` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indeks for tabel `forum_post`
--
ALTER TABLE `forum_post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thread` (`thread_id`),
  ADD KEY `person` (`person_id`);

--
-- Indeks for tabel `forum_thread`
--
ALTER TABLE `forum_thread`
  ADD PRIMARY KEY (`id`);

--
-- Indeks for tabel `people`
--
ALTER TABLE `people`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);
