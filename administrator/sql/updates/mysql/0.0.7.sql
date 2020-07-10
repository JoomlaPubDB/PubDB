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

INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (5, 'Comma', 'COM_PUBDB_COMMA', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (6, 'Colon', 'COM_PUBDB_COLON', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (7, 'Point', 'COM_PUBDB_POINT', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (8, 'pp', 'COM_PUBDB_PP', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (9, 'and', 'COM_PUBDB_AND', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (46, 'Available_at', 'COM_PUBDB_AVAILABLE_AT', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (47, 'Open_square_bracket', 'COM_PUBDB_OPEN_SQUARE_BRACKET', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (49, 'Close_square_bracket', 'COM_PUBDB_CLOSE_SQUARE_BRACKET', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (50, 'Open_bracket', 'COM_PUBDB_OPEN_BRACKET', '2');
INSERT INTO `#__pubdb_blocks` (`id`, `name`, `lable`, `category`) VALUES (51, 'Close_bracket', 'COM_PUBDB_CLOSE_BRACKET', '2');

INSERT INTO `#__pubdb_citation_style`(`name`, `string`) VALUES ('Business & Information Systems Engineering', '{"2":[1,2,5,27,29,3,4,50,10,51,13,7,39,26,6,23],"3":[1,2,5,27,29,3,4,50,10,51,13,7,42,5,16],"10":[1,2,5,27,29,3,4,50,10,51,13,16],"13":[1,2,5,27,29,3,4,50,10,51,13,16],"-1":[1,2,5,27,29,3,4,50,10,51,13,7,42,5,16]}');
INSERT INTO `#__pubdb_citation_style`(`name`, `string`) VALUES ('Springer - Lecture Notes in Computer Science', '{"2":[1,2,5,27,5,29,3,4,6,13,7,39,7,26,5,23,50,10,51,7,10],"3":[1,2,5,27,5,29,3,4,6,13,7,42,5,16,50,10,51,7],"-1":[1,2,5,27,5,29,3,4,6,13,7,39,7,26,5,23,50,10,51,7,10]}');

#Reference Types
INSERT INTO `#__pubdb_reference_types` (`id`, `ordering`, `state`, `checked_out`, `checked_out_time`, `created_by`, `modified_by`, `name`, `lable`) VALUES
(1, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'MISC', 'COM_PUBDB_REF_TYPE_MISC_DESC'),
(2, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'ARTICLE', 'COM_PUBDB_REF_TYPE_ARTICLE_DESC'),
(3, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'BOOK', 'COM_PUBDB_REF_TYPE_BOOK_DESC'),
(4, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'BOOKLET', 'COM_PUBDB_REF_TYPE_BOOKLET_DESC'),
(5, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'CONFERENCE', 'COM_PUBDB_REF_TYPE_CONFERENCE_DESC'),
(6, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'INBOOK', 'COM_PUBDB_REF_TYPE_INBOOK_DESC'),
(7, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'INCOLLECTION', 'COM_PUBDB_REF_TYPE_INCOLLECTION_DESC'),
(8, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'INPROCEEDINGS', 'COM_PUBDB_REF_TYPE_INPROCEEDINGS_DESC'),
(9, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'MANUAL', 'COM_PUBDB_REF_TYPE_MANUAL_DESC'),
(10, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'MASTERTHESIS', 'COM_PUBDB_REF_TYPE_MASTERTHESIS_DESC'),
(11, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'PHDTHESIS', 'COM_PUBDB_REF_TYPE_PHDTHESIS_DESC'),
(12, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'PROCEEDINGS', 'COM_PUBDB_REF_TYPE_PROCEEDINGS_DESC'),
(13, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'TECHREPORT', 'COM_PUBDB_REF_TYPE_TECHREPORT_DESC'),
(14, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 'UNPUBLISHED', 'COM_PUBDB_REF_TYPE_UNPUBLISHED_DESC');