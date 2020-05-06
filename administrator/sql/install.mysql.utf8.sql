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
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Repetition start', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Split First/Main', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Split Main/Last', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Repetition end', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES (', ', '', '2');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES (': ', '', '2');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('. ', '', '2');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('pp.', '', '2');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('and ', '', '2');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Year', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Month', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Day', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Title', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Subtitle', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Published_on', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Place_of_publication', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Access_date', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Language', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('DOI', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('ISBN', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Online_address', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Page_count', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Page_range', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Pub_med_id', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('eISBN', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Volume', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Authors_last_name', '', '3');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Authors_first_name', '', '3');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Authors_first_name_initial', '', '3');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Authors_sex', '', '3');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Translator_last_name', '', '3');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Translator_first_name', '', '3');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Translator_first_name_initial', '', '3');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Translator_sex', '', '3');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Others_involved_last_name', '', '3');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Others_involved_first_name', '', '3');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Others_involved_first_name_initial', '', '3');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Others_involved_sex', '', '3');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Periodical_name', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Periodical_ISSN', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Periodical_eISSN', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Publisher_name', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Series_title_name', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Series_title_editor', '', '1');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('online', '', '2');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Available at:', '', '2');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('[', '', '2');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('Accessed', '', '2');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES (']', '', '2');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES ('(', '', '2');
INSERT INTO `#__pubdb_blocks` (`name`, `lable`, `category`) VALUES (')', '', '2');

#Reference Types
INSERT INTO `#__pubdb_reference_types` (`id`, `ordering`, `state`, `checked_out`, `checked_out_time`, `created_by`, `modified_by`, `name`, `lable`) VALUES
(1, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'Misc', ''),
(2, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'Article', 'An article from a journal, magazine, newspaper, or periodical.'),
(3, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'Book', 'A book where the publisher is clearly identifiable.'),
(4, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'Booklet', 'A printed work that is bound, but does not have a clearly identifiable publisher or supporting institution.'),
(5, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'conference', 'An article that has been included in conference proceedings.'),
(6, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'inbook', 'A section, such as a chapter, or a page range within a book.'),
(7, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'incollection', 'A titled section of a book. Such as a short story within the larger collection of short stories that make up the book.'),
(8, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'inproceedings', 'A paper that has been published in conference proceedings. The usage of conference and inproceedings is the same.'),
(9, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'manual', 'A technical manual for a machine software such as would come with a purchase to explain operation to the new owner.'),
(10, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'masterthesis', 'A thesis written for the Master’s level degree.'),
(11, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'manual', 'A technical manual for a machine software such as would come with a purchase to explain operation to the new owner.'),
(12, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'masterthesis', 'A thesis written for the Master’s level degree.'),
(13, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'phdthesis', 'A thesis written for the PhD level degree.'),
(14, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'proceedings', 'A conference proceeding.'),
(15, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'techreport', 'An institutionally published report such as a report from a school, a government organization, an organization, or a company. This entry type is also frequently used for white papers and working papers.'),
(16, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'unpublished', 'A document that has not been officially published such as a paper draft or manuscript in preparation.');

#Citation styles
INSERT INTO `#__pubdb_citation_style`(`name`, `string`) VALUES ('Harvard', '{"-1": [], "1": [1,2,5,28,5,30,3,9,28,5,4,30,11,7,14,7,17,6,43,5,8,24,7], "2": [1,2,5,28,5,30,3,9,28,5,4,30,11,7,14,7,27,7,17,6,43,5,8,24,7], "3": [1,2,5,28,5,30,3,4,11,7,14,40,5,24,7], "4": [1,2,5,28,5,30,3,4,11,7,14,7,40,5,46,24,7,47,22,48,49,18,50,7]}');

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
                   as "authors_author_sex",

    l.year,
    l.month,
    l.day,
    type.name as ref_type,

    keywords.name as keywords,

    publisher.name as publishers

FROM
    #__pubdb_literature as l

    left join #__pubdb_reference_types as type
        on l.reference_type = type.id

left join #__pubdb_keywords as keywords
          on l.keywords = keywords.id

left join #__pubdb_publisher as publisher
          on l.publishers = publisher.id;
