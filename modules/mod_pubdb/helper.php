<?php

/**
 * @version     CVS: 0.0.1
 * @package     com_pubdb
 * @subpackage  mod_pubdb
 * @author      Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright   2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */
defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

/**
 * Helper for mod_pubdb
 *
 * @package     com_pubdb
 * @subpackage  mod_pubdb
 * @since       1.6
 */
class ModPubdbHelper
{
	/**
	 * Retrieve component items
	 *
	 * @param   Joomla\Registry\Registry &$params module parameters
	 *
	 * @return array Array with all the elements
     *
     * @throws Exception
	 */
	public static function getList(&$params)
	{
		$app   = Factory::getApplication();
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$tableField = explode(':', $params->get('field'));
		$table_name = !empty($tableField[0]) ? $tableField[0] : '';

		/* @var $params Joomla\Registry\Registry */
		$query
			->select('*')
			->from($table_name)
			->where('state = 1');

		$db->setQuery($query, $app->input->getInt('offset', (int) $params->get('offset')), $app->input->getInt('limit', (int) $params->get('limit')));
		$rows = $db->loadObjectList();

		return $rows;
	}

	/**
	 * Retrieve component items
	 *
	 * @param   Joomla\Registry\Registry &$params module parameters
	 *
	 * @return mixed stdClass object if the item was found, null otherwise
	 */
	public static function getItem(&$params)
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		/* @var $params Joomla\Registry\Registry */
		$query
			->select('*')
			->from($params->get('item_table'))
			->where('id = ' . intval($params->get('item_id')));

		$db->setQuery($query);
		$element = $db->loadObject();

		return $element;
	}

	/**
	 * Render element
	 *
	 * @param   Joomla\Registry\Registry $table_name  Table name
	 * @param   string                   $field_name  Field name
	 * @param   string                   $field_value Field value
	 *
	 * @return string
	 */
	public static function renderElement($table_name, $field_name, $field_value)
	{
		$result = '';
		
		if(strpos($field_name, ':'))
		{
			$tableField = explode(':', $field_name);
			$table_name = !empty($tableField[0]) ? $tableField[0] : '';
			$field_name = !empty($tableField[1]) ? $tableField[1] : '';
		}
		
		switch ($table_name)
		{
			
		case '#__pubdb_literature':
		switch($field_name){
		case 'id':
		$result = $field_value;
		break;
		case 'created_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'modified_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'title':
		$result = $field_value;
		break;
		case 'subtitle':
		$result = $field_value;
		break;
		case 'published_on':
		$result = $field_value;
		break;
		case 'reference_type':
		$result = $field_value;
		break;
		case 'access_date':
		$result = $field_value;
		break;
		case 'language':
		$result = JLanguage::getInstance($field_value)->getName();
		break;
		case 'doi':
		$result = $field_value;
		break;
		case 'isbn':
		$result = $field_value;
		break;
		case 'online_addess':
		$result = $field_value;
		break;
		case 'page_count':
		$result = $field_value;
		break;
		case 'page_range':
		$result = $field_value;
		break;
		case 'periodical_id':
		$result = self::loadValueFromExternalTable('#__pubdb_periodical', 'id', '', $field_value);
		break;
		case 'place_of_publication':
		$result = $field_value;
		break;
		case 'pub_med_id':
		$result = $field_value;
		break;
		case 'series_title_id':
		$result = self::loadValueFromExternalTable('#__pubdb_series_title', 'id', 'name', $field_value);
		break;
		case 'eisbn':
		$result = $field_value;
		break;
		case 'volume':
		$result = $field_value;
		break;
		case 'authors':
		$result = self::loadValueFromExternalTable('#__pubdb_person', 'id', '', $field_value);
		break;
		case 'translators':
		$result = self::loadValueFromExternalTable('#__pubdb_person', 'id', '', $field_value);
		break;
		case 'others_involved':
		$result = self::loadValueFromExternalTable('#__pubdb_person', 'id', '', $field_value);
		break;
		}
		break;
		case '#__pubdb_periodical':
		switch($field_name){
		case 'id':
		$result = $field_value;
		break;
		case 'created_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'modified_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'issn':
		$result = $field_value;
		break;
		case 'name':
		$result = $field_value;
		break;
		case 'eissn':
		$result = $field_value;
		break;
		}
		break;
		case '#__pubdb_series_title':
		switch($field_name){
		case 'id':
		$result = $field_value;
		break;
		case 'created_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'modified_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'name':
		$result = $field_value;
		break;
		case 'series_title_editor':
		$result = self::loadValueFromExternalTable('#__pubdb_person', 'id', '', $field_value);
		break;
		}
		break;
		case '#__pubdb_person':
		switch($field_name){
		case 'id':
		$result = $field_value;
		break;
		case 'created_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'modified_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'first_name':
		$result = $field_value;
		break;
		case 'last_name':
		$result = $field_value;
		break;
		case 'middle_name':
		$result = $field_value;
		break;
		case 'sex':
		$result = $field_value;
		break;
		case 'title':
		$result = $field_value;
		break;
		}
		break;
		}

		return $result;
	}

	/**
	 * Returns the translatable name of the element
	 *
	 * @param   string .................. $table_name table name
	 * @param   string                   $field   Field name
	 *
	 * @return string Translatable name.
	 */
	public static function renderTranslatableHeader($table_name, $field)
	{
		return Text::_(
			'MOD_PUBDB_HEADER_FIELD_' . str_replace('#__', '', strtoupper($table_name)) . '_' . strtoupper($field)
		);
	}

	/**
	 * Checks if an element should appear in the table/item view
	 *
	 * @param   string $field name of the field
	 *
	 * @return boolean True if it should appear, false otherwise
	 */
	public static function shouldAppear($field)
	{
		$noHeaderFields = array('checked_out_time', 'checked_out', 'ordering', 'state');

		return !in_array($field, $noHeaderFields);
	}

	

    /**
     * Method to get a value from a external table
     * @param string $source_table Source table name
     * @param string $key_field Source key field 
     * @param string $value_field Source value field
     * @param mixed  $key_value Value for the key field
     * @return mixed The value in the external table or null if it wasn't found
     */
    private static function loadValueFromExternalTable($source_table, $key_field, $value_field, $key_value) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
                ->select($db->quoteName($value_field))
                ->from($source_table)
                ->where($db->quoteName($key_field) . ' = ' . $db->quote($key_value));


        $db->setQuery($query);
        return $db->loadResult();
    }
}
