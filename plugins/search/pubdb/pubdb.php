<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  Search.content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_pubdb/router.php';

use \Joomla\CMS\Factory;

/**
 * Content search plugin.
 *
 * @package     Joomla.Plugin
 * @subpackage  Search.content
 * @since       1.6
 */
class PlgSearchPubdb extends \Joomla\CMS\Plugin\CMSPlugin
{
  /**
   * Determine areas searchable by this plugin.
   *
   * @return  array  An array of search areas.
   *
   * @since   1.6
   */
  public function onContentSearchAreas()
  {
    static $areas = array(
      'pubdb' => 'Pubdb'
    );

    return $areas;
  }

  /**
   * Search content (articles).
   * The SQL must return the following fields that are used in a common display
   * routine: href, title, section, created, text, browsernav.
   *
   * @param string $text Target search string.
   * @param string $phrase Matching option (possible values: exact|any|all).  Default is "any".
   * @param string $ordering Ordering option (possible values: newest|oldest|popular|alpha|category).  Default is "newest".
   * @param mixed $areas An array if the search it to be restricted to areas or null to search all areas.
   *
   * @return  array  Search results.
   *
   * @since   1.6
   */
  public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
  {
    $db = Factory::getDbo();

    if (is_array($areas)) {
      if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
        return array();
      }
    }

    $limit = $this->params->def('search_limit', 50);

    $text = trim($text);

    if ($text == '') {
      return array();
    }

    $rows = array();


//Search Literatures.
    if ($limit > 0) {
      switch ($phrase) {
        case 'exact':
//          $text = $db->quote('%' . $db->escape($text, true) . '%', false);
//          $wheres2 = array();
//          $wheres2[] = 'a.title LIKE ' . $text;
//          $wheres2[] = 'a.subtitle LIKE ' . $text;
//          $wheres2[] = 'a.doi LIKE ' . $text;
//          $wheres2[] = 'a.isbn LIKE ' . $text;
//          $wheres2[] = 'a.eisbn LIKE ' . $text;
//          $wheres2[] = 'CONCAT(`pubdb_person`.`first_name`, \' \', `pubdb_person`.`last_name`, \' \', `pubdb_person`.`middle_name`) LIKE ' . $text;
//          $wheres2[] = 'CONCAT(`pubdb_person`.`first_name`, \' \', `pubdb_person`.`last_name`, \' \', `pubdb_person`.`middle_name`) LIKE ' . $text;
//          $wheres2[] = 'CONCAT(`pubdb_person`.`first_name`, \' \', `pubdb_person`.`last_name`, \' \', `pubdb_person`.`middle_name`) LIKE ' . $text;
//          $wheres2[] = '`pubdb_keywords`.`name` LIKE ' . $text;
//          $where = '(' . implode(') OR (', $wheres2) . ')';
//          break;

        case 'all':
        case 'any':
        default:
          $words = explode(' ', $text);
          $wheres = array();

          foreach ($words as $word) {
            $word = $db->quote('%' . $db->escape($word, true) . '%', false);
            $wheres2 = array();
            $wheres2[] = 'a.title LIKE ' . $word;
            $wheres2[] = 'a.subtitle LIKE ' . $word;
            $wheres2[] = 'a.doi LIKE ' . $word;
            $wheres2[] = 'a.isbn LIKE ' . $word;
            $wheres2[] = 'a.eisbn LIKE ' . $word;
            $wheres2[] = 'a.online_address LIKE ' . $word;
            $wheres2[] = 'a.page_count LIKE ' . $word;
            $wheres2[] = 'a.page_range LIKE ' . $word;
            $wheres2[] = 'a.place_of_publication LIKE ' . $word;
            $wheres2[] = 'a.pub_med_id LIKE ' . $word;
            $wheres2[] = 'a.volume LIKE ' . $word;
            $wheres2[] = 'a.authors_last_name LIKE ' . $word;
            $wheres2[] = 'a.authors_first_name LIKE ' . $word;
            $wheres2[] = 'a.authors_first_name_initial LIKE ' . $word;
            $wheres2[] = 'a.authors_sex LIKE ' . $word;
            $wheres2[] = 'a.translators_last_name LIKE ' . $word;
            $wheres2[] = 'a.translators_first_name LIKE ' . $word;
            $wheres2[] = 'a.translators_first_name_initial LIKE ' . $word;
            $wheres2[] = 'a.translators_sex LIKE ' . $word;
            $wheres2[] = 'a.others_involved_last_name LIKE ' . $word;
            $wheres2[] = 'a.others_involved_first_name LIKE ' . $word;
            $wheres2[] = 'a.others_involved_first_name_initial LIKE ' . $word;
            $wheres2[] = 'a.others_involved_sex LIKE ' . $word;
            $wheres2[] = 'a.year LIKE ' . $word;
            $wheres2[] = 'a.month LIKE ' . $word;
            $wheres2[] = 'a.day LIKE ' . $word;
            $wheres2[] = 'a.ref_type LIKE ' . $word;
            $wheres2[] = 'a.keywords LIKE ' . $word;
            $wheres2[] = 'a.periodical_name LIKE ' . $word;
            $wheres2[] = 'a.periodical_issn LIKE ' . $word;
            $wheres2[] = 'a.periodical_eissn LIKE ' . $word;
            $wheres2[] = 'a.series_title_name LIKE ' . $word;
            $wheres2[] = 'a.series_title_editor LIKE ' . $word;
            $wheres2[] = 'a.publisher_name LIKE ' . $word;
            $wheres[] = implode(' OR ', $wheres2);
          }

          $where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
          break;
      }

      switch ($ordering) {
        default:
          $order = 'a.id DESC';
          break;
      }

      $query = $db->getQuery(true);

      $query
        ->clear()
        ->select(
          array(
            'a.id',
            'a.title AS title',
            '"" AS created',
            'a.title AS text',
            '"Literature" AS section',
            '1 AS browsernav'
          )
        )
        ->from('#__pubdb_publication_list AS a')
        ->where('(' . $where . ')')
        ->group('a.id')
        ->order($order);

      $db->setQuery($query, 0, $limit);
      $list = $db->loadObjectList();
      $limit -= count($list);

      if (isset($list)) {
        foreach ($list as $key => $item) {
          $list[$key]->href = JRoute::_('index.php?option=com_pubdb&view=literature&id=' . $item->id, false, 2);
        }
      }

      $rows = array_merge($list, $rows);
    }
    //return $rows;

//Search Buchserien.
    if ($limit > 0) {
      switch ($phrase) {
        case 'exact':
//          $text = $db->quote('%' . $db->escape($text, true) . '%', false);
//          $wheres2 = array();
//          $wheres2[] = 'CONCAT(`pubdb_person`.`first_name`, \' \', `pubdb_person`.`last_name`, \' \', `pubdb_person`.`middle_name`) LIKE ' . $text;
//          $where = '(' . implode(') OR (', $wheres2) . ')';
//          break;

        case 'all':
        case 'any':
        default:
          $words = explode(' ', $text);
          $wheres = array();

          foreach ($words as $word) {
            $word = $db->quote('%' . $db->escape($word, true) . '%', false);
            $wheres2 = array();
            $wheres2[] = 'CONCAT(`pubdb_person`.`first_name`, \' \', `pubdb_person`.`last_name`, \' \', `pubdb_person`.`middle_name`) LIKE ' . $word;
            $wheres[] = implode(' OR ', $wheres2);
          }

          $where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
          break;
      }

      switch ($ordering) {
        default:
          $order = 'a.id DESC';
          break;
      }

      $query = $db->getQuery(true);

      $query
        ->clear()
        ->select(
          array(
            'a.id',
            'a.name AS title',
            '"" AS created',
            'a.name AS text',
            '"Buchserie" AS section',
            '1 AS browsernav'
          )
        )
        ->from('#__pubdb_series_title AS a')
        ->innerJoin('`#__pubdb_person` AS pubdb_person ON pubdb_person.id = a.series_title_editor')
        ->where('(' . $where . ')')
        ->group('a.id')
        ->order($order);

      $db->setQuery($query, 0, $limit);
      $list = $db->loadObjectList();
      $limit -= count($list);

      if (isset($list)) {
        foreach ($list as $key => $item) {
          $list[$key]->href = JRoute::_('index.php?option=com_pubdb&view=series_title&id=' . $item->id, false, 2);
        }
      }

      $rows = array_merge($list, $rows);
    }


//Search Personen.
    if ($limit > 0) {
      switch ($phrase) {
        case 'exact':
          $text = $db->quote('%' . $db->escape($text, true) . '%', false);
          $wheres2 = array();
          $wheres2[] = 'a.first_name LIKE ' . $text;
          $wheres2[] = 'a.last_name LIKE ' . $text;
          $wheres2[] = 'a.middle_name LIKE ' . $text;
          $where = '(' . implode(') OR (', $wheres2) . ')';
          break;

        case 'all':
        case 'any':
        default:
          $words = explode(' ', $text);
          $wheres = array();

          foreach ($words as $word) {
            $word = $db->quote('%' . $db->escape($word, true) . '%', false);
            $wheres2 = array();
            $wheres2[] = 'a.first_name LIKE ' . $word;
            $wheres2[] = 'a.last_name LIKE ' . $word;
            $wheres2[] = 'a.middle_name LIKE ' . $word;
            $wheres[] = implode(' OR ', $wheres2);
          }

          $where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
          break;
      }

      switch ($ordering) {
        default:
          $order = 'a.id DESC';
          break;
      }

      $query = $db->getQuery(true);

      $query
        ->clear()
        ->select(
          array(
            'a.id',
            'a.first_name_initial AS title',
            '"" AS created',
            'a.first_name_initial AS text',
            '"Person" AS section',
            '1 AS browsernav'
          )
        )
        ->from('#__pubdb_person AS a')
        ->where('(' . $where . ')')
        ->group('a.id')
        ->order($order);

      $db->setQuery($query, 0, $limit);
      $list = $db->loadObjectList();
      $limit -= count($list);

      if (isset($list)) {
        foreach ($list as $key => $item) {
          $list[$key]->href = JRoute::_('index.php?option=com_pubdb&view=person&id=' . $item->id, false, 2);
        }
      }

      $rows = array_merge($list, $rows);
    }


//Search Verlage.
    if ($limit > 0) {
      switch ($phrase) {
        case 'exact':
          $text = $db->quote('%' . $db->escape($text, true) . '%', false);
          $wheres2 = array();
          $wheres2[] = 'a.name LIKE ' . $text;
          $where = '(' . implode(') OR (', $wheres2) . ')';
          break;

        case 'all':
        case 'any':
        default:
          $words = explode(' ', $text);
          $wheres = array();

          foreach ($words as $word) {
            $word = $db->quote('%' . $db->escape($word, true) . '%', false);
            $wheres2 = array();
            $wheres2[] = 'a.name LIKE ' . $word;
            $wheres[] = implode(' OR ', $wheres2);
          }

          $where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
          break;
      }

      switch ($ordering) {
        default:
          $order = 'a.id DESC';
          break;
      }

      $query = $db->getQuery(true);

      $query
        ->clear()
        ->select(
          array(
            'a.id',
            'a.name AS title',
            '"" AS created',
            'a.name AS text',
            '"Verlag" AS section',
            '1 AS browsernav'
          )
        )
        ->from('#__pubdb_publisher AS a')
        ->where('(' . $where . ')')
        ->group('a.id')
        ->order($order);

      $db->setQuery($query, 0, $limit);
      $list = $db->loadObjectList();
      $limit -= count($list);

      if (isset($list)) {
        foreach ($list as $key => $item) {
          $list[$key]->href = JRoute::_('index.php?option=com_pubdb&view=publisher&id=' . $item->id, false, 2);
        }
      }

      $rows = array_merge($list, $rows);
    }

    return $rows;
  }
}
