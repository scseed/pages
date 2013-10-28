--
-- Table structure for table `system_languages`
--

CREATE TABLE IF NOT EXISTS `system_languages` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `abbr` char(20) NOT NULL,
  `english_name` varchar(128) NOT NULL,
  `locale_name` varchar(128) NOT NULL,
  `country_name` varchar(128) NOT NULL,
  `name_rodit` varchar(64) DEFAULT NULL,
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `country_name` (`country_name`),
  UNIQUE KEY `abbr` (`abbr`),
  KEY `is_active` (`is_active`),
  KEY `locale_name` (`locale_name`),
  KEY `english_name` (`english_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Языки системы';

--
-- Table structure for table `page_types`
--

CREATE TABLE IF NOT EXISTS `page_types` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `route_name` varchar(45) NOT NULL,
  `directory` varchar(64) DEFAULT NULL,
  `controller` varchar(64) DEFAULT NULL,
  `action` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `route_name` (`route_name`),
  KEY `directory` (`directory`),
  KEY `controller` (`controller`),
  KEY `action` (`action`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -----------------------------------------------------
-- Table `pages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pages` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` tinyint(2) unsigned NOT NULL,
  `alias` varchar(64) DEFAULT NULL,
  `date_update` int(10) unsigned DEFAULT NULL,
  `date_create` int(10) unsigned NOT NULL,
  `creator_id` int(11) unsigned DEFAULT NULL,
  `updater_id` int(11) unsigned DEFAULT NULL,
  `params` varchar(256) DEFAULT NULL,
  `query` varchar(256) DEFAULT NULL,
  `class` varchar(64) DEFAULT NULL,
  `parent_id` smallint(5) unsigned DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `lvl` int(11) DEFAULT NULL,
  `scope` int(11) DEFAULT NULL,
  `is_visible` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_page_page-type` (`type_id`),
  KEY `fk_page_creator` (`creator_id`),
  KEY `fk_page_updater` (`updater_id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `lvl` (`lvl`),
  KEY `scope` (`scope`),
  KEY `is_active` (`is_active`),
  KEY `is_visible` (`is_visible`),
  KEY `parent_id` (`parent_id`),
  KEY `alias` (`alias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Table structure for table `page_content`
--

CREATE TABLE IF NOT EXISTS `page_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` smallint(5) unsigned NOT NULL,
  `lang_id` tinyint(2) unsigned NOT NULL,
  `title` varchar(256) NOT NULL,
  `long_title` varchar(256) DEFAULT NULL,
  `content` text,
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_content_page` (`page_id`),
  KEY `fk_page-conent_language` (`lang_id`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Содержание статических страниц и новостей';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `page_content`
--
ALTER TABLE `page_content`
  ADD CONSTRAINT `fk_page-conent_language` FOREIGN KEY (`lang_id`) REFERENCES `system_languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_page-content_page` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `page_content_ibfk_1` FOREIGN KEY (`lang_id`) REFERENCES `system_languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `page_content_ibfk_2` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `fk_page_creator` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_page_page-type` FOREIGN KEY (`type_id`) REFERENCES `page_types` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_page_updater` FOREIGN KEY (`updater_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pages_ibfk_2` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `pages_ibfk_3` FOREIGN KEY (`type_id`) REFERENCES `page_types` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pages_ibfk_4` FOREIGN KEY (`updater_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `pages_ibfk_5` FOREIGN KEY (`parent_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;