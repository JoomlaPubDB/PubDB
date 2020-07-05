-- if you get problems with the sort_buffer_size you should increas like nest statement
-- SET GLOBAL sort_buffer_size=524288;

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
                                                          `lable` VARCHAR(255)  NOT NULL ,
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
                                                 `name` VARCHAR(255)  NOT NULL ,
                                                 `lable` VARCHAR(255)  NOT NULL ,
                                                 `category` VARCHAR(255)  NOT NULL ,
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
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (1, 'Repetition start', 'COM_PUBDB_REPETITION_START', '3');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (2, 'Split First/Main', 'COM_PUBDB_SPLIT_FIRST_MAIN', '3');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (3, 'Split Main/Last', 'COM_PUBDB_SPLIT_MAIN_LAST', '3');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (4, 'Repetition end', 'COM_PUBDB_REPETITION_END', '3');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (5, ', ', 'COM_PUBDB_COMMA', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (6, ': ', 'COM_PUBDB_COLON', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (7, '. ', 'COM_PUBDB_POINT', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (8, 'pp.', 'COM_PUBDB_PP', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (9, 'and ', 'COM_PUBDB_AND', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (10, 'Year', 'COM_PUBDB_YEAR', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (11, 'Month', 'COM_PUBDB_MONTH', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (12, 'Day', 'COM_PUBDB_DAY', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (13, 'Title', 'COM_PUBDB_TITLE', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (14, 'Subtitle', 'COM_PUBDB_SUBTITLE', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (15, 'Published_on', 'COM_PUBDB_PUBLISHED_ON', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (16, 'Place_of_publication', 'COM_PUBDB_PLACE_OF_PUBLICATION', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (17, 'Access_date', 'COM_PUBDB_ACCESS_DATE', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (18, 'Language', 'COM_PUBDB_LANGUAGE', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (19, 'DOI', 'COM_PUBDB_DOI', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (20, 'ISBN', 'COM_PUBDB_ISBN', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (21, 'Online_address', 'COM_PUBDB_ONLINE_ADDRESS', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (22, 'Page_count', 'COM_PUBDB_PAGE_COUNT', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (23, 'Page_range', 'COM_PUBDB_PAGE_RANGE', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (24, 'Pub_med_id', 'COM_PUBDB_PUB_MED_ID', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (25, 'eISBN', 'COM_PUBDB_EISBN', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (26, 'Volume', 'COM_PUBDB_VOLUME', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (27, 'Authors_last_name', 'COM_PUBDB_AUTHORS_LAST_NAME', '3');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (28, 'Authors_first_name', 'COM_PUBDB_AUTHORS_FIRST_NAME', '3');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (29, 'Authors_first_name_initial', 'COM_PUBDB_AUTHORS_FIRST_NAME_INITIAL', '3');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (30, 'Authors_sex', 'COM_PUBDB_AUTHORS_SEX', '3');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (31, 'Translator_last_name', 'COM_PUBDB_TRANSLATOR_LAST_NAME', '3');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (32, 'Translator_first_name', 'COM_PUBDB_TRANSLATOR_FIRST_NAME', '3');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (33, 'Translator_first_name_initial', 'COM_PUBDB_TRANSLATOR_FIRST_NAME_INITIAL', '3');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (34, 'Translator_sex', 'COM_PUBDB_TRANSLATOR_SEX', '3');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (35, 'Others_involved_last_name', 'COM_PUBDB_OTHERS_INVOLVED_LAST_NAME', '3');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (36, 'Others_involved_first_name', 'COM_PUBDB_OTHERS_INVOLVED_FIRST_NAME', '3');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (37, 'Others_involved_first_name_initial', 'COM_PUBDB_OTHERS_INVOLVED_FIRST_NAME_INITIAL', '3');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (38, 'Others_involved_sex', 'COM_PUBDB_OTHERS_INVOLVED_SEX', '3');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (39, 'Periodical_name', 'COM_PUBDB_PERIODICAL_NAME', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (40, 'Periodical_ISSN', 'COM_PUBDB_PERIODICAL_ISSN', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (41, 'Periodical_eISSN', 'COM_PUBDB_PERIODICAL_EISSN', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (42, 'Publisher_name', 'COM_PUBDB_PUBLISHER_NAME', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (43, 'Series_title_name', 'COM_PUBDB_SERIES_TITLE_NAME', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (44, 'Series_title_editor', 'COM_PUBDB_SERIES_TITLE_EDITOR', '1');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (45, 'online', 'COM_PUBDB_ONLINE', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (46, 'Available at:', 'COM_PUBDB_AVAILABLE_AT', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (47, ' [', 'COM_PUBDB_OPEN_SQUARE_BRACKET', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (48, 'Accessed', 'COM_PUBDB_ACCESSED', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (49, '] ', 'COM_PUBDB_CLOSE_SQUARE_BRACKET', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (50, ' (', 'COM_PUBDB_OPEN_BRACKET', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (51, ') ', 'COM_PUBDB_CLOSE_BRACKET', '2');

#Reference Types
INSERT INTO `#__pubdb_reference_types` (`id`, `ordering`, `state`, `checked_out`, `checked_out_time`, `created_by`, `modified_by`, `name`, `lable`) VALUES
(1, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'COM_PUBDB_REF_TYPE_MISC', 'COM_PUBDB_REF_TYPE_MISC_DESC'),
(2, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'COM_PUBDB_REF_TYPE_ARTICLE', 'COM_PUBDB_REF_TYPE_ARTICLE_DESC'),
(3, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'COM_PUBDB_REF_TYPE_BOOK', 'COM_PUBDB_REF_TYPE_BOOK_DESC'),
(4, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'COM_PUBDB_REF_TYPE_BOOKLET', 'COM_PUBDB_REF_TYPE_BOOKLET_DESC'),
(5, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'COM_PUBDB_REF_TYPE_CONFERENCE', 'COM_PUBDB_REF_TYPE_CONFERENCE_DESC'),
(6, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'COM_PUBDB_REF_TYPE_INBOOK', 'COM_PUBDB_REF_TYPE_INBOOK_DESC'),
(7, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'COM_PUBDB_REF_TYPE_INCOLLECTION', 'COM_PUBDB_REF_TYPE_INCOLLECTION_DESC'),
(8, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'COM_PUBDB_REF_TYPE_INPROCEEDINGS', 'COM_PUBDB_REF_TYPE_INPROCEEDINGS_DESC'),
(9, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'COM_PUBDB_REF_TYPE_MANUAL', 'COM_PUBDB_REF_TYPE_MANUAL_DESC'),
(10, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'COM_PUBDB_REF_TYPE_MASTERTHESIS', 'COM_PUBDB_REF_TYPE_MASTERTHESIS_DESC'),
(11, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'COM_PUBDB_REF_TYPE_PHDTHESIS', 'COM_PUBDB_REF_TYPE_PHDTHESIS_DESC'),
(12, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'COM_PUBDB_REF_TYPE_PROCEEDINGS', 'COM_PUBDB_REF_TYPE_PROCEEDINGS_DESC'),
(13, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'COM_PUBDB_REF_TYPE_TECHREPORT', 'COM_PUBDB_REF_TYPE_TECHREPORT_DESC'),
(14, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'COM_PUBDB_REF_TYPE_UNPUBLISHED', 'COM_PUBDB_REF_TYPE_UNPUBLISHED_DESC');

#Citation styles
INSERT INTO `#__pubdb_citation_style`(`name`, `string`) VALUES ('Harvard', '{"-1":[1,2,5,27,5,29,3,9,27,5,29,4,50,10,51,7,13,7,16,6,42,7],"2":[1,2,5,27,5,29,3,9,27,5,29,4,50,10,51,7,13,7,42,5,26,6,8,23],"3":[1,2,5,27,5,29,3,9,27,5,29,4,50,10,51,7,13,7,16,6,42,7]}');
INSERT INTO `#__pubdb_citation_style`(`name`, `string`) VALUES ('Business & Information Systems Engineering', '{"2":[1,2,5,27,29,3,4,50,10,51,13,7,39,26,6,23],"3":[1,2,5,27,29,3,4,50,10,51,13,7,42,5,16],"10":[1,2,5,27,29,3,4,50,10,51,13,16],"13":[1,2,5,27,29,3,4,50,10,51,13,16],"-1":[1,2,5,27,29,3,4,50,10,51,13,7,42,5,16]}');

#Front End VIEW
CREATE OR REPLACE VIEW `#__pubdb_publication_list` AS
SELECT
    l.id,
    l.title,
    l.subtitle,
    l.published_on,
    l.access_date,
    l.language,
    l.doi,
    l.isbn,
    l.online_address,
    l.page_count,
    l.page_range,
    l.place_of_publication,
    l.pub_med_id,
    l.eisbn,
    l.volume,
    l.authors,
    (
        SELECT GROUP_CONCAT(last_name SEPARATOR ", ") FROM #__pubdb_person as p WHERE FIND_IN_SET(p.id, l.authors) > 0 ORDER BY last_name DESC
    )
                   as "authors_last_name",
    (
        SELECT GROUP_CONCAT(first_name SEPARATOR ", ") FROM #__pubdb_person as p WHERE FIND_IN_SET(p.id, l.authors) > 0 ORDER BY first_name DESC
    )
                   as "authors_first_name",
    (
        SELECT GROUP_CONCAT(first_name_initial SEPARATOR ", ") FROM #__pubdb_person as p WHERE FIND_IN_SET(p.id, l.authors) > 0 ORDER BY first_name_initial DESC
    )
                   as "authors_first_name_initial",
    (
        SELECT GROUP_CONCAT(sex SEPARATOR ", ") FROM #__pubdb_person as p WHERE FIND_IN_SET(p.id, l.authors) > 0 ORDER BY sex DESC
    )
                   as "authors_sex",

    l.translators,
    (
        SELECT GROUP_CONCAT(last_name SEPARATOR ", ") FROM #__pubdb_person as p WHERE FIND_IN_SET(p.id, l.translators) > 0 ORDER BY last_name DESC
    )
                   as "translators_last_name",
    (
        SELECT GROUP_CONCAT(first_name SEPARATOR ", ") FROM #__pubdb_person as p WHERE FIND_IN_SET(p.id, l.translators) > 0 ORDER BY first_name DESC
    )
                   as "translators_first_name",
    (
        SELECT GROUP_CONCAT(first_name_initial SEPARATOR ", ") FROM #__pubdb_person as p WHERE FIND_IN_SET(p.id, l.translators) > 0 ORDER BY first_name_initial DESC
    )
                   as "translators_first_name_initial",
    (
        SELECT GROUP_CONCAT(sex SEPARATOR ", ") FROM #__pubdb_person as p WHERE FIND_IN_SET(p.id, l.translators) > 0 ORDER BY sex DESC
    )
                   as "translators_sex",

    l.others_involved,
    (
        SELECT GROUP_CONCAT(last_name SEPARATOR ", ") FROM #__pubdb_person as p WHERE FIND_IN_SET(p.id, l.others_involved) > 0 ORDER BY last_name DESC
    )
                   as "others_involved_last_name",
    (
        SELECT GROUP_CONCAT(first_name SEPARATOR ", ") FROM #__pubdb_person as p WHERE FIND_IN_SET(p.id, l.others_involved) > 0 ORDER BY first_name DESC
    )
                   as "others_involved_first_name",
    (
        SELECT GROUP_CONCAT(first_name_initial SEPARATOR ", ") FROM #__pubdb_person as p WHERE FIND_IN_SET(p.id, l.others_involved) > 0 ORDER BY first_name_initial DESC
    )
                   as "others_involved_first_name_initial",
    (
        SELECT GROUP_CONCAT(sex SEPARATOR ", ") FROM #__pubdb_person as p WHERE FIND_IN_SET(p.id, l.others_involved) > 0 ORDER BY sex DESC
    )
                   as "others_involved_sex",

    l.year,
    l.month,
    l.day,
    type.name as ref_type,
    type.id as ref_type_id,

    (
        SELECT GROUP_CONCAT(name SEPARATOR ", ") FROM #__pubdb_keywords as k WHERE FIND_IN_SET(k.id, l.keywords) > 0 ORDER BY keywords.id
    )
    as "keywords",

    periodical.id as periodical_id,
    periodical.name as periodical_name,
    periodical.issn as periodical_issn,
    periodical.eissn as periodical_eissn,

    series_title.id as series_title_id,
    series_title.name as series_title_name,
    series_title.series_title_editor as series_title_editor,

    publisher.id as publisher_id,
    publisher.name as publisher_name

FROM
    #__pubdb_literature as l

    left join #__pubdb_reference_types as type
        on l.reference_type = type.id

left join #__pubdb_keywords as keywords
          on l.keywords = keywords.id

left join #__pubdb_periodical as periodical
          on l.periodical_id = periodical.id

left join #__pubdb_series_title as series_title
          on l.series_title_id = series_title.id

left join #__pubdb_publisher as publisher
          on l.publishers = publisher.id

WHERE l.state = 1;
