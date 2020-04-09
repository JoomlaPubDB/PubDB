DROP TABLE IF EXISTS `#__pubdb_literature`;
DROP TABLE IF EXISTS `#__pubdb_periodical`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'PubDB.%');