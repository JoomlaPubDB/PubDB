DROP TABLE IF EXISTS `#__pubdb_literature`;
DROP TABLE IF EXISTS `#__pubdb_periodical`;
DROP TABLE IF EXISTS `#__pubdb_series_title`;
DROP TABLE IF EXISTS `#__pubdb_person`;
DROP TABLE IF EXISTS `#__pubdb_publisher`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_pubdb.%');