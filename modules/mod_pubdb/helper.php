<?php

/**
 * @version     CVS: 0.0.7
 * @package     com_pubdb
 * @subpackage  mod_pubdb
 * @author      Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright   2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license     GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */
defined('_JEXEC') or die;

require(JPATH_ADMINISTRATOR . '/components/com_pubdb/helpers/PubdbLiteraturesCitation.php');

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;

/**
 * Helper for mod_pubdb
 *
 * @package     com_pubdb
 * @subpackage  mod_pubdb
 * @since       1.6
 */
class ModPubdbHelper
{
  private $params = null;
  private $export_ids = null;

  public function __construct()
  {
    $this->params = $this->getParams();
  }

  public function getParams()
  {
    $module = JModuleHelper::getModule('mod_pubdb');
    $moduleParams = new JRegistry;

    if ($module->params !== '') {
      $moduleParams->loadString($module->params);
    }

    return $moduleParams;
  }

  /**
   * Build an SQL query to load the list data.
   *
   * @return   JDatabaseQuery
   *
   * @since    1.6
   */
  public function getItemList($params)
  {
    // Create a new query object.
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    // Create select statement
    $query->select('*');
    $query->from($db->quoteName('#__pubdb_publication_list'));
    $db->setQuery($query);
    $preFilter = ModPubdbHelper::getResultFromParams($params);
    //get id's as comma separated list
    if (count($preFilter) > 0) {
      $filteredIds = implode(',', $preFilter);
      $query->where($db->quoteName('id') . ' IN (' . $filteredIds . ')');
    } else {
      $query->where($db->quoteName('id') . ' = 0');
    }
    $db->setQuery($query);
    return $db->loadAssocList();
  }

  /**
   * Query database with Back End menu settings to get a pre filtering
   * @param $menu_item
   * @return mixed
   * @since v0.0.7
   */

  public function getResultFromParams($menu_item)
  {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select($db->quoteName('id'));
    $query->from($db->quoteName('#__pubdb_publication_list'));
    //extract menu params
    foreach ((array)$menu_item as $param_list) {
      //loop to get params from menu item
      foreach ((array)$param_list as $key => $filter) {
        //filter custom settings to not use joomla items
        if (strpos($key, 'pubdb-') !== 0) continue;
        // arrays need different function for sql statements
        $key_field = explode('-', $key);
        if (is_array($filter)) {
          $query->where($db->quoteName($key_field[1]) . ' IN (' . implode(',', $filter) . ')', 'AND');
        } else {
          $query->where($db->quoteName($key_field[1]) . ' = ' . $filter, 'AND');
        }

      }
    }

    $db->setQuery($query);

    return $db->loadAssocList('id', 'id');
  }


  /**
   * Retrieve component items
   *
   * @param Joomla\Registry\Registry &$params module parameters
   *
   * @return array Array with all the elements
   *
   * @throws Exception
   */
  public static function getList(&$params)
  {
    $items = ModPubdbHelper::getItemList($params);
    $pattern = (int)$params['citation_style_id'];
    if (isset($_GET['citation_style'])) $pattern = (int)$_GET['citation_style'];
    $item_objects = array();
    foreach ($items as $item) {
      $item_objects[] = (object)$item;
    }
    $formattedStrings = PubdbLiteraturesCitation::mapList($pattern, $item_objects);
    $arrReturn = array();
    // build json object
    $export_ids = array();
    for ($i = 0; $i < count($item_objects); $i++) {
      $tmpItem = (array)$item_objects[$i];
      $tmpItem['formatted_string'] = " <a href=" . JRoute::_('index.php?option=com_pubdb&view=literature&id=' . (int)$item_objects[$i]->id) . ">" . $formattedStrings[$i] . "</a>";
      $tmpItem['authors'] = ModPubdbHelper::getAuthorsFromItem($tmpItem);
      $tmpItem['keywords'] = explode(',', $tmpItem['keywords']);
      $arrReturn[] = $tmpItem;
      $export_ids[] = (int)$item_objects[$i]->id;
    }
    $app = JFactory::getApplication();
    $app->set('mod_pubdb_export_id', $export_ids);
    return $arrReturn;
  }


  public function getAuthorsFromItem($item)
  {
    $last_names = explode(',', $item['authors_last_name']);
    $first_names = explode(',', $item['authors_first_name']);
    $arrAuthors = array();
    for ($i = 0; $i < count($last_names); $i++) {
      $arrAuthors[] = $last_names[$i] . " " . $first_names[$i];
    }
    return $arrAuthors;
  }

  /**
   * Check if state is set
   *
   * @param mixed $state State
   *
   * @return bool
   */
  public function getState($state)
  {
    return isset($this->state->{$state}) ? $this->state->{$state} : false;
  }

  /**
   * Get list of all citation styles available
   * @return mixed array with citation style id and name
   */
  public function getCitationStyles()
  {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query
      ->select($db->quoteName(array('id', 'name')))
      ->from('#__pubdb_citation_style')
      ->where($db->quoteName('state') . ' = 1');
    $db->setQuery($query);
    return $db->loadAssocList();
  }
}
