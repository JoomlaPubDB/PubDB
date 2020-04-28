DROP TABLE IF EXISTS `#__pubdb_literature`;
DROP TABLE IF EXISTS `#__pubdb_periodical`;
DROP TABLE IF EXISTS `#__pubdb_series_title`;
DROP TABLE IF EXISTS `#__pubdb_person`;
DROP TABLE IF EXISTS `#__pubdb_publisher`;
DROP TABLE IF EXISTS `#__pubdb_keywords`;
DROP TABLE IF EXISTS `#__pubdb_citation_style`;
DROP TABLE IF EXISTS `#__pubdb_reference_types`;
DROP TABLE IF EXISTS `#__pubdb_blocks`;
DROP VIEW IF EXISTS `#__pubdb_publication_list`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_pubdb.%');