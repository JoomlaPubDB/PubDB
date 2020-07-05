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
 * literature Table class
 *
 * @since  1.6
 */
class PubdbTableliterature extends \Joomla\CMS\Table\Table
{

	/**
	 * Constructor
	 *
	 * @param   JDatabase  &$db  A database connector object
	 */
	public function __construct(&$db)
	{
		JObserverMapper::addObserverClassToClass('JTableObserverContenthistory', 'PubdbTableliterature', array('typeAlias' => 'com_pubdb.literature'));
		parent::__construct('#__pubdb_literature', 'id', $db);
        $this->setColumnAlias('published', 'state');
    }

	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param   array  $array   Named array
	 * @param   mixed  $ignore  Optional array or list of parameters to ignore
	 *
	 * @return  null|string  null is operation was satisfactory, otherwise returns an error
	 *
	 * @see     JTable:bind
	 * @since   1.5
     * @throws Exception
	 */
	public function bind($array, $ignore = '')
	{
	  $date = Factory::getDate();
		$task = Factory::getApplication()->input->get('task');

		$input = JFactory::getApplication()->input;
		$task = $input->getString('task', '');

    $datetime = DateTime::createFromFormat("Y-n-j",$array['year'] . "-" . $array['month'] . "-" . $array['day']);
    $array['published_on'] = $datetime->format('Y-m-d');

		if ($array['id'] == 0 && empty($array['created_by']))
		{
			$array['created_by'] = JFactory::getUser()->id;
		}

		if ($array['id'] == 0 && empty($array['modified_by']))
		{
			$array['modified_by'] = JFactory::getUser()->id;
		}

		if ($task == 'apply' || $task == 'save')
		{
			$array['modified_by'] = JFactory::getUser()->id;
		}

		// Support for empty date field: published_on
		if($array['published_on'] == '0000-00-00' )
		{
			$array['published_on'] = '';
		}

		// Support for multiple or not foreign key field: reference_type
			if(!empty($array['reference_type']))
			{
				if(is_array($array['reference_type'])){
					$array['reference_type'] = implode(',',$array['reference_type']);
				}
				else if(strrpos($array['reference_type'], ',') != false){
					$array['reference_type'] = explode(',',$array['reference_type']);
				}
			}
			else {
				$array['reference_type'] = '';
			}

		// Support for empty date field: access_date
		if($array['access_date'] == '0000-00-00' )
		{
			$array['access_date'] = '';
		}

		// Support for multiple or not foreign key field: periodical_id
			if(!empty($array['periodical_id']))
			{
				if(is_array($array['periodical_id'])){
					$array['periodical_id'] = implode(',',$array['periodical_id']);
				}
				else if(strrpos($array['periodical_id'], ',') != false){
					$array['periodical_id'] = explode(',',$array['periodical_id']);
				}
			}
			else {
				$array['periodical_id'] = '';
			}

		// Support for multiple or not foreign key field: series_title_id
			if(!empty($array['series_title_id']))
			{
				if(is_array($array['series_title_id'])){
					$array['series_title_id'] = implode(',',$array['series_title_id']);
				}
				else if(strrpos($array['series_title_id'], ',') != false){
					$array['series_title_id'] = explode(',',$array['series_title_id']);
				}
			}
			else {
				$array['series_title_id'] = '';
			}

		// Support for multiple or not foreign key field: authors
			if(!empty($array['authors']))
			{
				if(is_array($array['authors'])){
					$array['authors'] = implode(',',$array['authors']);
				}
				else if(strrpos($array['authors'], ',') != false){
					$array['authors'] = explode(',',$array['authors']);
				}
			}
			else {
				$array['authors'] = '';
			}

		// Support for multiple or not foreign key field: translators
			if(!empty($array['translators']))
			{
				if(is_array($array['translators'])){
					$array['translators'] = implode(',',$array['translators']);
				}
				else if(strrpos($array['translators'], ',') != false){
					$array['translators'] = explode(',',$array['translators']);
				}
			}
			else {
				$array['translators'] = '';
			}

		// Support for multiple or not foreign key field: others_involved
			if(!empty($array['others_involved']))
			{
				if(is_array($array['others_involved'])){
					$array['others_involved'] = implode(',',$array['others_involved']);
				}
				else if(strrpos($array['others_involved'], ',') != false){
					$array['others_involved'] = explode(',',$array['others_involved']);
				}
			}
			else {
				$array['others_involved'] = '';
			}

		// Support for multiple or not foreign key field: publishers
			if(!empty($array['publishers']))
			{
				if(is_array($array['publishers'])){
					$array['publishers'] = implode(',',$array['publishers']);
				}
				else if(strrpos($array['publishers'], ',') != false){
					$array['publishers'] = explode(',',$array['publishers']);
				}
			}
			else {
				$array['publishers'] = '';
			}

		// Support for multiple or not foreign key field: keywords
			if(!empty($array['keywords']))
			{
				if(is_array($array['keywords'])){
					$array['keywords'] = implode(',',$array['keywords']);
				}
				else if(strrpos($array['keywords'], ',') != false){
					$array['keywords'] = explode(',',$array['keywords']);
				}
			}
			else {
				$array['keywords'] = '';
			}

		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}

		if (!Factory::getUser()->authorise('core.admin', 'com_pubdb.literature.' . $array['id']))
		{
			$actions         = Access::getActionsFromFile(
				JPATH_ADMINISTRATOR . '/components/com_pubdb/access.xml',
				"/access/section[@name='literature']/"
			);
			$default_actions = Access::getAssetRules('com_pubdb.literature.' . $array['id'])->getData();
			$array_jaccess   = array();

			foreach ($actions as $action)
			{
                if (key_exists($action->name, $default_actions))
                {
                    $array_jaccess[$action->name] = $default_actions[$action->name];
                }
			}

			$array['rules'] = $this->JAccessRulestoArray($array_jaccess);
		}

		// Bind the rules for ACL where supported.
		if (isset($array['rules']) && is_array($array['rules']))
		{
			$this->setRules($array['rules']);
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * This function convert an array of JAccessRule objects into an rules array.
	 *
	 * @param   array  $jaccessrules  An array of JAccessRule objects.
	 *
	 * @return  array
	 */
	private function JAccessRulestoArray($jaccessrules)
	{
		$rules = array();

		foreach ($jaccessrules as $action => $jaccess)
		{
			$actions = array();

			if ($jaccess)
			{
				foreach ($jaccess->getData() as $group => $allow)
				{
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
		if (property_exists($this, 'ordering') && $this->id == 0)
		{
			$this->ordering = self::getNextOrder();
		}



		return parent::check();
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed    $pks     An optional array of primary key values to update.  If not
	 *                            set the instance property value is used.
	 * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param   integer  $userId  The user id of the user performing the operation.
	 *
	 * @return   boolean  True on success.
	 *
	 * @since    1.0.4
	 *
	 * @throws Exception
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		ArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else
			{
				throw new Exception(500, Text::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k . '=' . implode(' OR ' . $k . '=', $pks);

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time'))
		{
			$checkin = '';
		}
		else
		{
			$checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
		}

		// Update the publishing state for rows with the given primary keys.
		$this->_db->setQuery(
			'UPDATE `' . $this->_tbl . '`' .
			' SET `state` = ' . (int) $state .
			' WHERE (' . $where . ')' .
			$checkin
		);
		$this->_db->execute();

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			// Checkin each row.
			foreach ($pks as $pk)
			{
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks))
		{
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

		return 'com_pubdb.literature.' . (int) $this->$k;
	}

	/**
	 * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
	 *
	 * @param   JTable   $table  Table name
	 * @param   integer  $id     Id
	 *
	 * @see Table::_getAssetParentId
	 *
	 * @return mixed The id on success, false on failure.
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
		if ($assetParent->id)
		{
			$assetParentId = $assetParent->id;
		}

		return $assetParentId;
	}

	/**
	 * Delete a record by id
	 *
	 * @param   mixed  $pk  Primary key value to delete. Optional
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
