<?php

/**
 * @version    CVS: 0.0.5
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

use \Joomla\CMS\Factory;
use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Table\Table;

/**
 * Pubdb model.
 *
 * @since  1.6
 */
class PubdbModelLiterature extends \Joomla\CMS\MVC\Model\ItemModel
{
  public $_item;


  /**
   * Method to auto-populate the model state.
   *
   * Note. Calling getState in this method will result in recursion.
   *
   * @return void
   *
   * @throws Exception
   * @since    1.6
   *
   */
  protected function populateState()
  {
    $app = Factory::getApplication('com_pubdb');
    $user = Factory::getUser();

    // Check published state
    if ((!$user->authorise('core.edit.state', 'com_pubdb')) && (!$user->authorise('core.edit', 'com_pubdb'))) {
      $this->setState('filter.published', 1);
      $this->setState('filter.archived', 2);
    }

    // Load state from the request userState on edit or from the passed variable on default
    if (Factory::getApplication()->input->get('layout') == 'edit') {
      $id = Factory::getApplication()->getUserState('com_pubdb.edit.literature.id');
    } else {
      $id = Factory::getApplication()->input->get('id');
      Factory::getApplication()->setUserState('com_pubdb.edit.literature.id', $id);
    }

    $this->setState('literature.id', $id);

    // Load the parameters.
    $params = $app->getParams();
    $params_array = $params->toArray();

    if (isset($params_array['item_id'])) {
      $this->setState('literature.id', $params_array['item_id']);
    }

    $this->setState('params', $params);
  }

  /**
   * Method to get an object.
   *
   * @param integer $id The id of the object to get.
   *
   * @return  mixed    Object on success, false on failure.
   *
   * @throws Exception
   */
  public function getItem($id = null)
  {
    if ($this->_item === null) {
      $this->_item = false;

      if (empty($id)) {
        $id = $this->getState('literature.id');
      }

      // Get a level row instance.
      $table = $this->getTable();

      // Attempt to load the row.
      if ($table->load($id)) {


        // Check published state.
        if ($published = $this->getState('filter.published')) {
          if (isset($table->state) && $table->state != $published) {
            throw new Exception(Text::_('COM_PUBDB_ITEM_NOT_LOADED'), 403);
          }
        }

        // Convert the JTable to a clean JObject.
        $properties = $table->getProperties(1);
        $this->_item = ArrayHelper::toObject($properties, 'JObject');


      }
    }


    if (isset($this->_item->created_by)) {
      $this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
    }

    if (isset($this->_item->modified_by)) {
      $this->_item->modified_by_name = JFactory::getUser($this->_item->modified_by)->name;
    }

    if (isset($this->_item->reference_type) && $this->_item->reference_type != '') {
      if (is_object($this->_item->reference_type)) {
        $this->_item->reference_type = ArrayHelper::fromObject($this->_item->reference_type);
      }

      $values = (is_array($this->_item->reference_type)) ? $this->_item->reference_type : explode(',', $this->_item->reference_type);

      $textValue = array();

      foreach ($values as $value) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
          ->select('`#__pubdb_reference_types_3418098`.`name`')
          ->from($db->quoteName('#__pubdb_reference_types', '#__pubdb_reference_types_3418098'))
          ->where($db->quoteName('id') . ' = ' . $db->quote($value));

        $db->setQuery($query);
        $results = $db->loadObject();

        if ($results) {
          $textValue[] = $results->name;
        }
      }

      $this->_item->reference_type = !empty($textValue) ? implode(', ', $textValue) : $this->_item->reference_type;

    }

    if (isset($this->_item->periodical_id) && $this->_item->periodical_id != '') {
      if (is_object($this->_item->periodical_id)) {
        $this->_item->periodical_id = ArrayHelper::fromObject($this->_item->periodical_id);
      }

      $values = (is_array($this->_item->periodical_id)) ? $this->_item->periodical_id : explode(',', $this->_item->periodical_id);

      $textValue = array();

      foreach ($values as $value) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
          ->select('CONCAT(`#__pubdb_periodical_3418585`.`issn`, \',\', `#__pubdb_periodical_3418585`.`name`) AS `fk_value, id`')
          ->from($db->quoteName('#__pubdb_periodical', '#__pubdb_periodical_3418585'))
          ->where($db->quoteName('id') . ' = ' . $db->quote($value));

        $db->setQuery($query);
        $results = $db->loadObject();

        if ($results) {
          $textValue[] = $results;
        }
      }

      if (!empty($textValue)) {
        $tmp = "";
        foreach ($textValue as $val)
          $tmp .= " <a href=" . JRoute::_('index.php?view=person&id=' . (int)$val->id) . ">" . trim($val->fk_value) . "</a>,";
        $tmp = rtrim($tmp, ", ");

        $this->_item->periodical_id = $tmp;
      }

    }

    if (isset($this->_item->series_title_id) && $this->_item->series_title_id != '') {
      if (is_object($this->_item->series_title_id)) {
        $this->_item->series_title_id = ArrayHelper::fromObject($this->_item->series_title_id);
      }

      $values = (is_array($this->_item->series_title_id)) ? $this->_item->series_title_id : explode(',', $this->_item->series_title_id);

      $textValue = array();

      foreach ($values as $value) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
          ->select('`#__pubdb_series_title_3418632`.`name`, id')
          ->from($db->quoteName('#__pubdb_series_title', '#__pubdb_series_title_3418632'))
          ->where($db->quoteName('id') . ' = ' . $db->quote($value));

        $db->setQuery($query);
        $results = $db->loadObject();

        if ($results) {
          $textValue[] = $results;
        }
      }

      if (!empty($textValue)) {
        $tmp = "";
        foreach ($textValue as $val)
          $tmp .= " <a href=" . JRoute::_('index.php?view=person&id=' . (int)$val->id) . ">" . trim($val->name) . "</a>,";
        $tmp = rtrim($tmp, ", ");

        $this->_item->series_title_id = $tmp;
      }

    }

    if (isset($this->_item->authors) && $this->_item->authors != '') {
      if (is_object($this->_item->authors)) {
        $this->_item->authors = ArrayHelper::fromObject($this->_item->authors);
      }

      $values = (is_array($this->_item->authors)) ? $this->_item->authors : explode(',', $this->_item->authors);

      $textValue = array();

      foreach ($values as $value) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
          ->select('CONCAT(`#__pubdb_person_3418647`.`first_name`, \' \', `#__pubdb_person_3418647`.`last_name`, \' \', `#__pubdb_person_3418647`.`middle_name`) AS `fk_value`, id')
          ->from($db->quoteName('#__pubdb_person', '#__pubdb_person_3418647'))
          ->where($db->quoteName('id') . ' = ' . $db->quote($value));

        $db->setQuery($query);
        $results = $db->loadObject();

        if ($results) {
          $textValue[] = $results;
        }
      }

      if (!empty($textValue)) {
        $tmp = "";
        foreach ($textValue as $val)
          $tmp .= " <a href=" . JRoute::_('index.php?view=person&id=' . (int)$val->id) . ">" . trim($val->fk_value) . "</a>,";
        $tmp = rtrim($tmp, ", ");

        $this->_item->authors = $tmp;
      }

    }

    if (isset($this->_item->translators) && $this->_item->translators != '') {
      if (is_object($this->_item->translators)) {
        $this->_item->translators = ArrayHelper::fromObject($this->_item->translators);
      }

      $values = (is_array($this->_item->translators)) ? $this->_item->translators : explode(',', $this->_item->translators);

      $textValue = array();

      foreach ($values as $value) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
          ->select('CONCAT(`#__pubdb_person_3418648`.`first_name`, \' \', `#__pubdb_person_3418648`.`last_name`, \' \', `#__pubdb_person_3418648`.`middle_name`) AS `fk_value`, id')
          ->from($db->quoteName('#__pubdb_person', '#__pubdb_person_3418648'))
          ->where($db->quoteName('id') . ' = ' . $db->quote($value));

        $db->setQuery($query);
        $results = $db->loadObject();

        if ($results) {
          $textValue[] = $results;
        }
      }

      if (!empty($textValue)) {
        $tmp = "";
        foreach ($textValue as $val)
          $tmp .= " <a href=" . JRoute::_('index.php?view=person&id=' . (int)$val->id) . ">" . trim($val->fk_value) . "</a>,";
        $tmp = rtrim($tmp, ", ");

        $this->_item->translators = $tmp;
      }

    }

    if (isset($this->_item->others_involved) && $this->_item->others_involved != '') {
      if (is_object($this->_item->others_involved)) {
        $this->_item->others_involved = ArrayHelper::fromObject($this->_item->others_involved);
      }

      $values = (is_array($this->_item->others_involved)) ? $this->_item->others_involved : explode(',', $this->_item->others_involved);

      $textValue = array();

      foreach ($values as $value) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
          ->select('CONCAT(`#__pubdb_person_3418649`.`first_name`, \' \', `#__pubdb_person_3418649`.`last_name`, \' \', `#__pubdb_person_3418649`.`middle_name`) AS `fk_value, id`')
          ->from($db->quoteName('#__pubdb_person', '#__pubdb_person_3418649'))
          ->where($db->quoteName('id') . ' = ' . $db->quote($value));

        $db->setQuery($query);
        $results = $db->loadObject();

        if ($results) {
          $textValue[] = $results;
        }
      }

      if (!empty($textValue)) {
        $tmp = "";
        foreach ($textValue as $val)
          $tmp .= " <a href=" . JRoute::_('index.php?view=person&id=' . (int)$val->id) . ">" . trim($val->fk_value) . "</a>,";
        $tmp = rtrim($tmp, ", ");

        $this->_item->others_involved = $tmp;
      }

    }

    if (isset($this->_item->publishers) && $this->_item->publishers != '') {
      if (is_object($this->_item->publishers)) {
        $this->_item->publishers = ArrayHelper::fromObject($this->_item->publishers);
      }

      $values = (is_array($this->_item->publishers)) ? $this->_item->publishers : explode(',', $this->_item->publishers);

      $textValue = array();

      foreach ($values as $value) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
          ->select('`#__pubdb_publisher_3418659`.`name`, id')
          ->from($db->quoteName('#__pubdb_publisher', '#__pubdb_publisher_3418659'))
          ->where($db->quoteName('id') . ' = ' . $db->quote($value));

        $db->setQuery($query);
        $results = $db->loadObject();

        if ($results) {
          $textValue[] = $results;
        }
      }

      if (!empty($textValue)) {
        $tmp = "";
        foreach ($textValue as $val)
          $tmp .= " <a href=" . JRoute::_('index.php?view=publisher&id=' . (int)$val->id) . ">" . trim($val->name) . "</a>,";
        $tmp = rtrim($tmp, ", ");

        $this->_item->publishers = $tmp;
      }

    }

    if (isset($this->_item->keywords) && $this->_item->keywords != '') {
      if (is_object($this->_item->keywords)) {
        $this->_item->keywords = ArrayHelper::fromObject($this->_item->keywords);
      }

      $values = (is_array($this->_item->keywords)) ? $this->_item->keywords : explode(',', $this->_item->keywords);

      $textValue = array();

      foreach ($values as $value) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
          ->select('`#__pubdb_keywords_3418670`.`name`')
          ->from($db->quoteName('#__pubdb_keywords', '#__pubdb_keywords_3418670'))
          ->where($db->quoteName('id') . ' = ' . $db->quote($value));

        $db->setQuery($query);
        $results = $db->loadObject();

        if ($results) {
          $textValue[] = $results->name;
        }
      }

      $this->_item->keywords = !empty($textValue) ? implode(', ', $textValue) : $this->_item->keywords;

    }

    return $this->_item;
  }

  /**
   * Get an instance of JTable class
   *
   * @param string $type Name of the JTable class to get an instance of.
   * @param string $prefix Prefix for the table class name. Optional.
   * @param array $config Array of configuration values for the JTable object. Optional.
   *
   * @return  JTable|bool JTable if success, false on failure.
   */
  public function getTable($type = 'Literature', $prefix = 'PubdbTable', $config = array())
  {
    $this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_pubdb/tables');

    return Table::getInstance($type, $prefix, $config);
  }

  /**
   * Get the id of an item by alias
   *
   * @param string $alias Item alias
   *
   * @return  mixed
   */
  public function getItemIdByAlias($alias)
  {
    $table = $this->getTable();
    $properties = $table->getProperties();
    $result = null;
    $aliasKey = $this->getAliasFieldNameByView('literature');

    if (key_exists('alias', $properties)) {
      $table->load(array('alias' => $alias));
      $result = $table->id;
    } elseif (isset($aliasKey) && key_exists($aliasKey, $properties)) {
      $table->load(array($aliasKey => $alias));
      $result = $table->id;
    }

    return $result;

  }

  /**
   * Method to check in an item.
   *
   * @param integer $id The id of the row to check out.
   *
   * @return  boolean True on success, false on failure.
   *
   * @since    1.6
   */
  public function checkin($id = null)
  {
    // Get the id.
    $id = (!empty($id)) ? $id : (int)$this->getState('literature.id');

    if ($id) {
      // Initialise the table
      $table = $this->getTable();

      // Attempt to check the row in.
      if (method_exists($table, 'checkin')) {
        if (!$table->checkin($id)) {
          return false;
        }
      }
    }

    return true;

  }

  /**
   * Method to check out an item for editing.
   *
   * @param integer $id The id of the row to check out.
   *
   * @return  boolean True on success, false on failure.
   *
   * @since    1.6
   */
  public function checkout($id = null)
  {
    // Get the user id.
    $id = (!empty($id)) ? $id : (int)$this->getState('literature.id');


    if ($id) {
      // Initialise the table
      $table = $this->getTable();

      // Get the current user object.
      $user = Factory::getUser();

      // Attempt to check the row out.
      if (method_exists($table, 'checkout')) {
        if (!$table->checkout($user->get('id'), $id)) {
          return false;
        }
      }
    }

    return true;

  }

  /**
   * Publish the element
   *
   * @param int $id Item id
   * @param int $state Publish state
   *
   * @return  boolean
   */
  public function publish($id, $state)
  {
    $table = $this->getTable();

    $table->load($id);
    $table->state = $state;

    return $table->store();

  }

  /**
   * Method to delete an item
   *
   * @param int $id Element id
   *
   * @return  bool
   */
  public function delete($id)
  {
    $table = $this->getTable();


    return $table->delete($id);

  }


}
