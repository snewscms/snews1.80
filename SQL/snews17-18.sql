--
--  SAME STRUCTURE - CHANGE CHARSET TO UTF-8
--
--  Before proceeding, ensure that you: Have completed a full database backup!
--
--  Last Updated: July 14, 2016
-- -----------------------------------------------------------------------------

-- DON'T FORGET TO CHANGE DATABASE NAME IN THIS CASE I WILL USE snews18
ALTER DATABASE `snews17` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

SET NAMES 'utf8';

SET storage_engine = INNODB;

ALTER TABLE `settings` CONVERT TO CHARACTER SET `utf8` COLLATE `utf8_general_ci`;
ALTER TABLE `categories` CONVERT TO CHARACTER SET `utf8` COLLATE `utf8_general_ci`;
ALTER TABLE `articles` CONVERT TO CHARACTER SET `utf8` COLLATE `utf8_general_ci`;
ALTER TABLE `extras` CONVERT TO CHARACTER SET `utf8` COLLATE `utf8_general_ci`;
ALTER TABLE `comments` CONVERT TO CHARACTER SET `utf8` COLLATE `utf8_general_ci`;