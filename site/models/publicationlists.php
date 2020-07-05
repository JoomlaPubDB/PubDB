<?php

/**
 * @version    CVS: 0.0.5
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

require(JPATH_ADMINISTRATOR . '/components/com_pubdb/helpers/PubdbLiteraturesCitation.php');

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Pubdb records.
 *
 * @since  1.6
 */
class PubdbModelPublicationlists extends \Joomla\CMS\MVC\Model\ListModel
{
  /**
   * Constructor.
   *
   * @param   array  $config  An optional associative array of configuration settings.
   *
   * @see        JController
   * @since      1.6
   */
  public function __construct($config = array())
  {
    if (empty($config['filter_fields']))
    {
      $config['filter_fields'] = array(

      );
    }

    parent::__construct($config);
  }



  /**
   * Method to auto-populate the model state.
   *
   * Note. Calling getState in this method will result in recursion.
   *
   * @param   string  $ordering   Elements order
   * @param   string  $direction  Order direction
   *
   * @return void
   *
   * @throws Exception
   *
   * @since    1.6
   */
  protected function populateState($ordering = null, $direction = null)
  {
    $app  = JFactory::getApplication();
    $list = $app->getUserState($this->context . '.list');

    $ordering  = isset($list['filter_order'])     ? $list['filter_order']     : null;
    $direction = isset($list['filter_order_Dir']) ? $list['filter_order_Dir'] : null;
    if(empty($ordering)){
      $ordering = $app->getUserStateFromRequest($this->context . '.filter_order', 'filter_order', $app->get('filter_order'));
      if (!in_array($ordering, $this->filter_fields))
      {
        $ordering = "a.id";
      }
      $this->setState('list.ordering', $ordering);
    }
    if(empty($direction))
    {
      $direction = $app->getUserStateFromRequest($this->context . '.filter_order_Dir', 'filter_order_Dir', $app->get('filter_order_Dir'));
      if (!in_array(strtoupper($direction), array('ASC', 'DESC', '')))
      {
        $direction = "ASC";
      }
      $this->setState('list.direction', $direction);
    }

    $list['limit']     = $app->getUserStateFromRequest($this->context . '.list.limit', 'limit', $app->get('list_limit'), 'uint');
    $list['start']     = $app->input->getInt('start', 0);
    $list['ordering']  = $ordering;
    $list['direction'] = $direction;

    $app->setUserState($this->context . '.list', $list);
    $app->input->set('list', null);


    // List state information.

    parent::populateState($ordering, $direction);

    $context = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
    $this->setState('filter.search', $context);

    // Split context into component and optional section
    $parts = FieldsHelper::extract($context);

    if ($parts)
    {
      $this->setState('filter.component', $parts[0]);
      $this->setState('filter.section', $parts[1]);
    }
  }

  /**
   * Build an SQL query to load the list data.
   *
   * @return   JDatabaseQuery
   *
   * @since    1.6
   */
  protected function getListQuery()
  {
    // Create a new query object.
    $db = $this->getDbo();
    $query = $db->getQuery(true);
    // Create select statement
    $query->select('*');
    $query->from($db->quoteName('#__pubdb_publication_list'));
    $db->setQuery($query);
    $params = $this->state->get('parameters.menu');
    $preFilter = $this->getResultFromParams($params);
    //get id's as comma separated list
    if (count($preFilter) > 0) {
      $filteredIds = implode(',', $preFilter);
      $query->where($db->quoteName('id') . ' IN (' . $filteredIds . ')');
    } else {
      $query->where($db->quoteName('id') . ' = 0');
    }
    return $query;
  }

  /**
   * Query database with Back End menu settings to get a pre filtering
   * @param $menu_item
   * @return mixed
   * @since v0.0.7
   */

  private function getResultFromParams($menu_item){
    $db = $this->getDbo();
    $query = $db->getQuery(true);
    $query->select($db->quoteName('id'));
    $query->from($db->quoteName('#__pubdb_publication_list'));
    //extract menu params
    foreach( (array) $menu_item as $param_list) {
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
   * Method to get an array of data items
   *
   * @return  mixed An array of data on success, false on failure.
   */
  public function getItems()
  {
    $items = parent::getItems();
    $params = $this->state->get('parameters.menu');
    $pattern = $params['citation_style_id'][0];
    $formattedStrings = PubdbLiteraturesCitation::mapList($pattern, $items);
    $arrReturn = array();
    // build json object
    $export_ids = array();
    for ($i = 0 ; $i < count($items); $i ++){
      $tmpItem = (array) $items[$i];
      $tmpItem['formatted_string'] = " <a href=" . JRoute::_('index.php?option=com_pubdb&view=literature&id=' . (int)$items[$i]->id) . ">" . $formattedStrings[$i] . "</a>";
      $tmpItem['authors'] = $this->getAuthorsFromItem($tmpItem);
      $tmpItem['keywords'] = explode(',', $tmpItem['keywords']);
      $arrReturn[] = $tmpItem;
      $export_ids[] = (int)$items[$i]->id;
    }
    $this->state->set('export_ids', $export_ids);
    return $arrReturn;
  }


  private function getAuthorsFromItem($item){
    $last_names = explode(',', $item['authors_last_name']);
    $first_names = explode(',' , $item['authors_first_name']);
    $arrAuthors = array();
    for($i = 0; $i  < count($last_names); $i ++){
      $arrAuthors[] = $last_names[$i]. " " . $first_names[$i];
    }
    return $arrAuthors;
  }

  /**
   * Overrides the default function to check Date fields format, identified by
   * "_dateformat" suffix, and erases the field if it's not correct.
   *
   * @return void
   */
  protected function loadFormData()
  {
    $app              = Factory::getApplication();
    $filters          = $app->getUserState($this->context . '.filter', array());
    $error_dateformat = false;

    foreach ($filters as $key => $value)
    {
      if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
      {
        $filters[$key]    = '';
        $error_dateformat = true;
      }
    }

    if ($error_dateformat)
    {
      $app->enqueueMessage(Text::_("COM_PUBDB_SEARCH_FILTER_DATE_FORMAT"), "warning");
      $app->setUserState($this->context . '.filter', $filters);
    }

    return parent::loadFormData();
  }

  /**
   * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
   *
   * @param   string  $date  Date to be checked
   *
   * @return bool
   */
  private function isValidDate($date)
  {
    $date = str_replace('/', '-', $date);
    return (date_create($date)) ? Factory::getDate($date)->format("Y-m-d") : null;
  }
}
