SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';


ALTER TABLE `dokumenty`
CHANGE `lat` `lat` float NULL COMMENT 'sirka' AFTER `pocitadlo`,
CHANGE `lng` `lng` float NULL COMMENT 'vyska' AFTER `lat`;

ALTER TABLE `dokumenty`
ADD `druh` int NULL COMMENT 'Druh dokumentu';
