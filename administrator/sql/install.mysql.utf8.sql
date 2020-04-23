CREATE TABLE IF NOT EXISTS `#__pubdb_literature` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT 1,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`year` DOUBLE,
`month` DOUBLE,
`day` DOUBLE,
`title` VARCHAR(255)  NOT NULL ,
`subtitle` VARCHAR(255)  NOT NULL ,
`published_on` DATETIME NOT NULL ,
`reference_type` INT NOT NULL ,
`access_date` DATETIME NOT NULL DEFAULT NOW(),
`language` VARCHAR(5)  NOT NULL ,
`doi` VARCHAR(255)  NOT NULL ,
`isbn` VARCHAR(13)  NOT NULL ,
`online_address` VARCHAR(255)  NOT NULL ,
`page_count` INT(11)  NOT NULL ,
`page_range` VARCHAR(255)  NOT NULL ,
`periodical_id` INT NOT NULL ,
`place_of_publication` VARCHAR(255)  NOT NULL ,
`pub_med_id` VARCHAR(255)  NOT NULL ,
`series_title_id` INT NOT NULL ,
`eisbn` VARCHAR(13)  NOT NULL ,
`volume` VARCHAR(255)  NOT NULL ,
`authors` TEXT NOT NULL ,
`translators` TEXT NOT NULL ,
`others_involved` TEXT NOT NULL ,
`publishers` TEXT NOT NULL ,
`keywords` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__pubdb_periodical` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT 1,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`issn` VARCHAR(9)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`eissn` VARCHAR(9)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__pubdb_series_title` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT 1,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`series_title_editor` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__pubdb_person` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT 1,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`first_name_initial` VARCHAR(255)  NOT NULL ,
`first_name` VARCHAR(255)  NOT NULL ,
`last_name` VARCHAR(255)  NOT NULL ,
`middle_name` VARCHAR(255)  NOT NULL ,
`sex` VARCHAR(255)  NOT NULL ,
`title` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__pubdb_publisher` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT 1,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__pubdb_keywords` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT 1,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__pubdb_citation_style` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT 1,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`string` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__pubdb_reference_types` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT 1,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__pubdb_blocks` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT 1,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`prefix` VARCHAR(255)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`suffix` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;


INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Literature','com_pubdb.literature','{"special":{"dbtable":"#__pubdb_literature","key":"id","type":"Literature","prefix":"PubdbTable"}}', '{"formFile":"administrator\/components\/com_pubdb\/models\/forms\/literature.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"reference_type","targetTable":"#__pubdb_reference_types","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"periodical_id","targetTable":"#__pubdb_periodical","targetColumn":"id","displayColumn":"issn"},{"sourceColumn":"series_title_id","targetTable":"#__pubdb_series_title","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_pubdb.literature')
) LIMIT 1;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Magazin','com_pubdb.periodical','{"special":{"dbtable":"#__pubdb_periodical","key":"id","type":"Periodical","prefix":"PubdbTable"}}', '{"formFile":"administrator\/components\/com_pubdb\/models\/forms\/periodical.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_pubdb.periodical')
) LIMIT 1;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Buchserie','com_pubdb.series_title','{"special":{"dbtable":"#__pubdb_series_title","key":"id","type":"Series_title","prefix":"PubdbTable"}}', '{"formFile":"administrator\/components\/com_pubdb\/models\/forms\/series_title.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_pubdb.series_title')
) LIMIT 1;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Person','com_pubdb.person','{"special":{"dbtable":"#__pubdb_person","key":"id","type":"Person","prefix":"PubdbTable"}}', '{"formFile":"administrator\/components\/com_pubdb\/models\/forms\/person.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_pubdb.person')
) LIMIT 1;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Verlag','com_pubdb.publisher','{"special":{"dbtable":"#__pubdb_publisher","key":"id","type":"Publisher","prefix":"PubdbTable"}}', '{"formFile":"administrator\/components\/com_pubdb\/models\/forms\/publisher.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_pubdb.publisher')
) LIMIT 1;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Schlüsselwort','com_pubdb.keyword','{"special":{"dbtable":"#__pubdb_keywords","key":"id","type":"Keyword","prefix":"PubdbTable"}}', '{"formFile":"administrator\/components\/com_pubdb\/models\/forms\/keyword.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_pubdb.keyword')
) LIMIT 1;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Zitierstil','com_pubdb.citationstyle','{"special":{"dbtable":"#__pubdb_citation_style","key":"id","type":"Citationstyle","prefix":"PubdbTable"}}', '{"formFile":"administrator\/components\/com_pubdb\/models\/forms\/citationstyle.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"string"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_pubdb.citationstyle')
) LIMIT 1;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Referenz Typ','com_pubdb.referencetype','{"special":{"dbtable":"#__pubdb_reference_types","key":"id","type":"Referencetype","prefix":"PubdbTable"}}', '{"formFile":"administrator\/components\/com_pubdb\/models\/forms\/referencetype.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_pubdb.referencetype')
) LIMIT 1;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Block','com_pubdb.block','{"special":{"dbtable":"#__pubdb_blocks","key":"id","type":"Block","prefix":"PubdbTable"}}', '{"formFile":"administrator\/components\/com_pubdb\/models\/forms\/block.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_pubdb.block')
) LIMIT 1;

# Default entries
# Blocks
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Repetition start', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Split First/Main', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Split Main/Last', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Repetition end', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', ',', ' ');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', ':', ' ');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', '.', ' ');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'pp.', ' ');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES (' ', 'and', ' ');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Year', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('(', 'Year', ')');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Month', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Day', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Title', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Subtitle', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Published_on', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Place_of_publication', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Access_date', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Language', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'DOI', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'ISBN', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Online_address', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Page_count', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Page_range', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Pub_med_id', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'eISBN', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Volume', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Author_last_name', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Author_first_name', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Author_first_name_initial', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Author_sex', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Translator_last_name', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Translator_last_name', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Translator_first_name', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Translator_first_name_initial', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Translator_sex', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Others_involved_last_name', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Others_involved_first_name', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Others_involved_first_name_initial', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Others_involved_sex', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Periodical_name', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Periodical_ISSN', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Periodical_eISSN', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Publisher_name', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Series_title_name', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Series_title_editor', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES (' [', 'online', '] ');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES (' ', 'Available at:', ' ');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', '[', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', 'Accessed', '');
INSERT INTO `#__pubdb_blocks` (`prefix`, `name`, `suffix`) VALUES ('', ']', '');

#Reference Types
INSERT INTO `#_pubdb_reference_types`(`name`) VALUES ('Book');
INSERT INTO `#_pubdb_reference_types`(`name`) VALUES ('Book edited');
INSERT INTO `#_pubdb_reference_types`(`name`) VALUES ('Journal print');
INSERT INTO `#_pubdb_reference_types`(`name`) VALUES ('Journal online');
INSERT INTO `#_pubdb_reference_types`(`name`) VALUES ('Website');
INSERT INTO `#_pubdb_reference_types`(`name`) VALUES ('Website article/blog');
INSERT INTO `#_pubdb_reference_types`(`name`) VALUES ('Website social media');

#Citation styles
INSERT INTO `#_pubdb_citation_style`(`name`, `string`) VALUES ('Harvard', '{"-1": [], "1": [1,2,5,28,5,30,3,9,28,5,4,30,11,7,14,7,17,6,43,5,8,24,7], "2": [1,2,5,28,5,30,3,9,28,5,4,30,11,7,14,7,27,7,17,6,43,5,8,24,7], "3": [1,2,5,28,5,30,3,4,11,7,14,40,5,24,7], "4": [1,2,5,28,5,30,3,4,11,7,14,7,40,5,46,24,7,47,22,48,49,18,50,7]}')
