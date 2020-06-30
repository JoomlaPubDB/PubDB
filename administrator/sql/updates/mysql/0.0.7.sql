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
