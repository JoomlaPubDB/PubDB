<?php

/**
 * @version    CVS: 0.0.5
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Access\Access;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Table\Table;

/**
 * publisher Table class
 *
 * @since  1.6
 */
class PubdbTablepublisher extends \Joomla\CMS\Table\Table
{
  /**
   * Check if a field is unique
   *
   * @param string $field Name of the field
   *
   * @return bool True if unique
   */
  private function isUnique($field)
  {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query
      ->select($db->quoteName($field))
      ->from($db->quoteName($this->_tbl))
      ->where($db->quoteName($field) . ' = ' . $db->quote($this->$field))
      ->where($db->quoteName('id') . ' <> ' . (int)$this->{$this->_tbl_key});

    $db->setQuery($query);
    $db->execute();

    return ($db->getNumRows() == 0) ? true : false;
  }

  /**
   * Constructor
   *
   * @param JDatabase  &$db A database connector object
   */
  public function __construct(&$db)
  {
    JObserverMapper::addObserverClassToClass('JTableObserverContenthistory', 'PubdbTablepublisher', array('typeAlias' => 'com_pubdb.publisher'));
    parent::__construct('#__pubdb_publisher', 'id', $db);
    $this->setColumnAlias('published', 'state');
  }

  /**
   * Overloaded bind function to pre-process the params.
   *
   * @param array $array Named array
   * @param mixed $ignore Optional array or list of parameters to ignore
   *
   * @return  null|string  null is operation was satisfactory, otherwise returns an error
   *
   * @throws Exception
   * @since   1.5
   * @see     JTable:bind
   */
  public function bind($array, $ignore = '')
  {
    $date = Factory::getDate();
    $task = Factory::getApplication()->input->get('task');

    $input = JFactory::getApplication()->input;
    $task = $input->getString('task', '');

    if ($array['id'] == 0 && empty($array['created_by'])) {
      $array['created_by'] = JFactory::getUser()->id;
    }

    if ($array['id'] == 0 && empty($array['modified_by'])) {
      $array['modified_by'] = JFactory::getUser()->id;
    }

    if ($task == 'apply' || $task == 'save') {
      $array['modified_by'] = JFactory::getUser()->id;
    }

    if (isset($array['params']) && is_array($array['params'])) {
      $registry = new JRegistry;
      $registry->loadArray($array['params']);
      $array['params'] = (string)$registry;
    }

    if (isset($array['metadata']) && is_array($array['metadata'])) {
      $registry = new JRegistry;
      $registry->loadArray($array['metadata']);
      $array['metadata'] = (string)$registry;
    }

    if (!Factory::getUser()->authorise('core.admin', 'com_pubdb.publisher.' . $array['id'])) {
      $actions = Access::getActionsFromFile(
        JPATH_ADMINISTRATOR . '/components/com_pubdb/access.xml',
        "/access/section[@name='publisher']/"
      );
      $default_actions = Access::getAssetRules('com_pubdb.publisher.' . $array['id'])->getData();
      $array_jaccess = array();

      foreach ($actions as $action) {
        if (key_exists($action->name, $default_actions)) {
          $array_jaccess[$action->name] = $default_actions[$action->name];
        }
      }

      $array['rules'] = $this->JAccessRulestoArray($array_jaccess);
    }

    // Bind the rules for ACL where supported.
    if (isset($array['rules']) && is_array($array['rules'])) {
      $this->setRules($array['rules']);
    }

    return parent::bind($array, $ignore);
  }

  /**
   * This function convert an array of JAccessRule objects into an rules array.
   *
   * @param array $jaccessrules An array of JAccessRule objects.
   *
   * @return  array
   */
  private function JAccessRulestoArray($jaccessrules)
  {
    $rules = array();

    foreach ($jaccessrules as $action => $jaccess) {
      $actions = array();

      if ($jaccess) {
        foreach ($jaccess->getData() as $group => $allow) {
          $actions[$group] = ((bool)$allow);
        }
      }

      $rules[$action] = $actions;
    }

    return $rules;
  }

  /**
   * Overloaded check function
   *
   * @return bool
   */
  public function check()
  {
    // If there is an ordering column and this is a new row then get the next ordering value
    if (property_exists($this, 'ordering') && $this->id == 0) {
      $this->ordering = self::getNextOrder();
    }

    // Check if name is unique
    if (!$this->isUnique('name')) {
      throw new Exception('Your <b>name</b> item "<b>' . $this->name . '</b>" already exists');
    }


    return parent::check();
  }

  /**
   * Method to set the publishing state for a row or list of rows in the database
   * table.  The method respects checked out rows by other users and will attempt
   * to checkin rows that it can after adjustments are made.
   *
   * @param mixed $pks An optional array of primary key values to update.  If not
   *                            set the instance property value is used.
   * @param integer $state The publishing state. eg. [0 = unpublished, 1 = published]
   * @param integer $userId The user id of the user performing the operation.
   *
   * @return   boolean  True on success.
   *
   * @throws Exception
   * @since    1.0.4
   *
   */
  public function publish($pks = null, $state = 1, $userId = 0)
  {
    // Initialise variables.
    $k = $this->_tbl_key;

    // Sanitize input.
    ArrayHelper::toInteger($pks);
    $userId = (int)$userId;
    $state = (int)$state;

    // If there are no primary keys set check to see if the instance key is set.
    if (empty($pks)) {
      if ($this->$k) {
        $pks = array($this->$k);
      } // Nothing to set publishing state on, return false.
      else {
        throw new Exception(500, Text::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
      }
    }

    // Build the WHERE clause for the primary keys.
    $where = $k . '=' . implode(' OR ' . $k . '=', $pks);

    // Determine if there is checkin support for the table.
    if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
      $checkin = '';
    } else {
      $checkin = ' AND (checked_out = 0 OR checked_out = ' . (int)$userId . ')';
    }

    // Update the publishing state for rows with the given primary keys.
    $this->_db->setQuery(
      'UPDATE `' . $this->_tbl . '`' .
      ' SET `state` = ' . (int)$state .
      ' WHERE (' . $where . ')' .
      $checkin
    );
    $this->_db->execute();

    // If checkin is supported and all rows were adjusted, check them in.
    if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
      // Checkin each row.
      foreach ($pks as $pk) {
        $this->checkin($pk);
      }
    }

    // If the JTable instance value is in the list of primary keys that were set, set the instance.
    if (in_array($this->$k, $pks)) {
      $this->state = $state;
    }

    return true;
  }

  /**
   * Define a namespaced asset name for inclusion in the #__assets table
   *
   * @return string The asset name
   *
   * @see Table::_getAssetName
   */
  protected function _getAssetName()
  {
    $k = $this->_tbl_key;

    return 'com_pubdb.publisher.' . (int)$this->$k;
  }

  /**
   * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
   *
   * @param JTable $table Table name
   * @param integer $id Id
   *
   * @return mixed The id on success, false on failure.
   * @see Table::_getAssetParentId
   *
   */
  protected function _getAssetParentId(JTable $table = null, $id = null)
  {
    // We will retrieve the parent-asset from the Asset-table
    $assetParent = Table::getInstance('Asset');

    // Default: if no asset-parent can be found we take the global asset
    $assetParentId = $assetParent->getRootId();

    // The item has the component as asset-parent
    $assetParent->loadByName('com_pubdb');

    // Return the found asset-parent-id
    if ($assetParent->id) {
      $assetParentId = $assetParent->id;
    }

    return $assetParentId;
  }

  /**
   * Delete a record by id
   *
   * @param mixed $pk Primary key value to delete. Optional
   *
   * @return bool
   */
  public function delete($pk = null)
  {
    $this->load($pk);
    $result = parent::delete($pk);

    return $result;
  }
}
