-- -----------------------------------------------------
-- Table `system_languages`
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `system_languages` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `abbr` char(20) NOT NULL,
  `english_name` varchar(128) NOT NULL,
  `locale_name` varchar(128) NOT NULL,
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) )
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COMMENT='System languages';

-- -----------------------------------------------------
-- Table `page_content_types`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `page_types` (
  `id` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(128) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARSET=utf8
COMMENT='Page types';

-- -----------------------------------------------------
-- Table `pages`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `pages` (
  `id` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `type_id` TINYINT(2) UNSIGNED NOT NULL ,
  `parent_id` SMALLINT(5) UNSIGNED NULL DEFAULT NULL ,
  `alias` VARCHAR(64) NULL DEFAULT NULL ,
  `date_create` INT(10) UNSIGNED NOT NULL ,
  `is_active` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' ,
  `lft` MEDIUMINT NULL DEFAULT NULL ,
  `rgt` MEDIUMINT NULL DEFAULT NULL ,
  `lvl` MEDIUMINT NULL DEFAULT NULL ,
  `scope` SMALLINT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_page_parent` (`parent_id` ASC) ,
  INDEX `fk_page_type` (`type_id` ASC) ,
  CONSTRAINT `fk_page_type`
    FOREIGN KEY (`type_id` )
    REFERENCES `page_types` (`id` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARSET=utf8
COMMENT='Pages table';


-- -----------------------------------------------------
-- Table `page_content`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `page_content` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `page_id` SMALLINT(5) UNSIGNED NULL DEFAULT NULL ,
  `lang_id` TINYINT(2) UNSIGNED NOT NULL ,
  `title` VARCHAR(256) NOT NULL ,
  `long_title` VARCHAR(256) NULL DEFAULT NULL ,
  `content` TEXT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_page-content_page` (`page_id` ASC) ,
  INDEX `fk_page-content_system-language` (`lang_id` ASC) ,
  CONSTRAINT `fk_page-content_page`
    FOREIGN KEY (`page_id` )
    REFERENCES `pages` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_page-content_system-language`
    FOREIGN KEY (`lang_id` )
    REFERENCES `system_languages` (`id` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARSET=utf8
COMMENT='Pages content table';