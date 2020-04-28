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

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Pubdb records.
 *
 * @since  1.6
 */
class PubdbModelLiteratures extends \Joomla\CMS\MVC\Model\ListModel
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
				'id', 'a.id',
				'ordering', 'a.ordering',
				'state', 'a.state',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'year', 'a.year',
				'month', 'a.month',
				'day', 'a.day',
				'title', 'a.title',
				'subtitle', 'a.subtitle',
				'published_on', 'a.published_on',
				'reference_type', 'a.reference_type',
				'access_date', 'a.access_date',
				'language', 'a.language',
				'doi', 'a.doi',
				'isbn', 'a.isbn',
				'online_address', 'a.online_address',
				'page_count', 'a.page_count',
				'page_range', 'a.page_range',
				'periodical_id', 'a.periodical_id',
				'place_of_publication', 'a.place_of_publication',
				'pub_med_id', 'a.pub_med_id',
				'series_title_id', 'a.series_title_id',
				'eisbn', 'a.eisbn',
				'volume', 'a.volume',
				'authors', 'a.authors',
				'translators', 'a.translators',
				'others_involved', 'a.others_involved',
				'publishers', 'a.publishers',
				'keywords', 'a.keywords',
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
            $db    = $this->getDbo();
            $query = $db->getQuery(true);

            // Select the required fields from the table.
            $query->select(
                        $this->getState(
                                'list.select', 'DISTINCT a.*'
                        )
                );

            $query->from('`#__pubdb_literature` AS a');
            
		// Join over the users for the checked out user.
		$query->select('uc.name AS uEditor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// Join over the created by field 'modified_by'
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');
		// Join over the foreign key 'reference_type'
		$query->select('`#__pubdb_reference_types_3418098`.`name` AS referencetypes_fk_value_3418098');
		$query->join('LEFT', '#__pubdb_reference_types AS #__pubdb_reference_types_3418098 ON #__pubdb_reference_types_3418098.`id` = a.`reference_type`');
		// Join over the foreign key 'periodical_id'
		$query->select('CONCAT(`#__pubdb_periodical_3418585`.`issn`, \' \', `#__pubdb_periodical_3418585`.`name`) AS periodicals_fk_value_3418585');
		$query->join('LEFT', '#__pubdb_periodical AS #__pubdb_periodical_3418585 ON #__pubdb_periodical_3418585.`id` = a.`periodical_id`');
		// Join over the foreign key 'series_title_id'
		$query->select('`#__pubdb_series_title_3418632`.`name` AS series_titles_fk_value_3418632');
		$query->join('LEFT', '#__pubdb_series_title AS #__pubdb_series_title_3418632 ON #__pubdb_series_title_3418632.`id` = a.`series_title_id`');
		// Join over the foreign key 'authors'
		$query->select('CONCAT(`#__pubdb_person_3418647`.`first_name`, \' \', `#__pubdb_person_3418647`.`last_name`, \' \', `#__pubdb_person_3418647`.`middle_name`) AS people_fk_value_3418647');
		$query->join('LEFT', '#__pubdb_person AS #__pubdb_person_3418647 ON #__pubdb_person_3418647.`id` = a.`authors`');
		// Join over the foreign key 'translators'
		$query->select('CONCAT(`#__pubdb_person_3418648`.`first_name`, \' \', `#__pubdb_person_3418648`.`last_name`, \' \', `#__pubdb_person_3418648`.`middle_name`) AS people_fk_value_3418648');
		$query->join('LEFT', '#__pubdb_person AS #__pubdb_person_3418648 ON #__pubdb_person_3418648.`id` = a.`translators`');
		// Join over the foreign key 'others_involved'
		$query->select('CONCAT(`#__pubdb_person_3418649`.`first_name`, \' \', `#__pubdb_person_3418649`.`last_name`, \' \', `#__pubdb_person_3418649`.`middle_name`) AS people_fk_value_3418649');
		$query->join('LEFT', '#__pubdb_person AS #__pubdb_person_3418649 ON #__pubdb_person_3418649.`id` = a.`others_involved`');
		// Join over the foreign key 'publishers'
		$query->select('`#__pubdb_publisher_3418659`.`name` AS publishers_fk_value_3418659');
		$query->join('LEFT', '#__pubdb_publisher AS #__pubdb_publisher_3418659 ON #__pubdb_publisher_3418659.`id` = a.`publishers`');
		// Join over the foreign key 'keywords'
		$query->select('`#__pubdb_keywords_3418670`.`name` AS keywords_fk_value_3418670');
		$query->join('LEFT', '#__pubdb_keywords AS #__pubdb_keywords_3418670 ON #__pubdb_keywords_3418670.`id` = a.`keywords`');
            
		if (!Factory::getUser()->authorise('core.edit', 'com_pubdb'))
		{
			$query->where('a.state = 1');
		}

            // Filter by search in title
            $search = $this->getState('filter.search');

            if (!empty($search))
            {
                if (stripos($search, 'id:') === 0)
                {
                    $query->where('a.id = ' . (int) substr($search, 3));
                }
                else
                {
                    $search = $db->Quote('%' . $db->escape($search, true) . '%');
					$query->where('( a.title LIKE ' . $search . '  OR  a.subtitle LIKE ' . $search . '  OR  a.doi LIKE ' . $search . '  OR  a.isbn LIKE ' . $search . '  OR CONCAT(`#__pubdb_person_3418647`.`first_name`, \' \', `#__pubdb_person_3418647`.`last_name`, \' \', `#__pubdb_person_3418647`.`middle_name`) LIKE ' . $search . '  OR #__pubdb_keywords_3418670.name LIKE ' . $search . ' )');
                }
            }
            

		// Filtering access_date
		$filter_access_date_from = $this->state->get("filter.access_date.from");

		if ($filter_access_date_from !== null && !empty($filter_access_date_from))
		{
			$query->where("a.`access_date` >= '".$db->escape($filter_access_date_from)."'");
		}
		$filter_access_date_to = $this->state->get("filter.access_date.to");

		if ($filter_access_date_to !== null  && !empty($filter_access_date_to))
		{
			$query->where("a.`access_date` <= '".$db->escape($filter_access_date_to)."'");
		}

		// Filtering keywords
		$filter_keywords = $this->state->get("filter.keywords");

		if ($filter_keywords)
		{
			$query->where("FIND_IN_SET('" . $db->escape($filter_keywords) . "',a.keywords)");
		}

            // Add the list ordering clause.
            $orderCol  = $this->state->get('list.ordering', "a.id");
            $orderDirn = $this->state->get('list.direction', "ASC");

            if ($orderCol && $orderDirn)
            {
                $query->order($db->escape($orderCol . ' ' . $orderDirn));
            }

            return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
		
		foreach ($items as $item)
		{

			if (isset($item->reference_type))
			{

				$values    = explode(',', $item->reference_type);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__pubdb_reference_types_3418098`.`name`')
						->from($db->quoteName('#__pubdb_reference_types', '#__pubdb_reference_types_3418098'))
						->where($db->quoteName('#__pubdb_reference_types_3418098.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->name;
					}
				}

				$item->reference_type = !empty($textValue) ? implode(', ', $textValue) : $item->reference_type;
			}


			if (isset($item->periodical_id))
			{

				$values    = explode(',', $item->periodical_id);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('CONCAT(`#__pubdb_periodical_3418585`.`issn`, \',\', `#__pubdb_periodical_3418585`.`name`) AS `fk_value`')
						->from($db->quoteName('#__pubdb_periodical', '#__pubdb_periodical_3418585'))
						->where($db->quoteName('#__pubdb_periodical_3418585.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->fk_value;
					}
				}

				$item->periodical_id = !empty($textValue) ? implode(', ', $textValue) : $item->periodical_id;
			}


			if (isset($item->series_title_id))
			{

				$values    = explode(',', $item->series_title_id);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__pubdb_series_title_3418632`.`name`')
						->from($db->quoteName('#__pubdb_series_title', '#__pubdb_series_title_3418632'))
						->where($db->quoteName('#__pubdb_series_title_3418632.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->name;
					}
				}

				$item->series_title_id = !empty($textValue) ? implode(', ', $textValue) : $item->series_title_id;
			}


			if (isset($item->authors))
			{

				$values    = explode(',', $item->authors);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('CONCAT(`#__pubdb_person_3418647`.`first_name`, \' \', `#__pubdb_person_3418647`.`last_name`, \' \', `#__pubdb_person_3418647`.`middle_name`) AS `fk_value`')
						->from($db->quoteName('#__pubdb_person', '#__pubdb_person_3418647'))
						->where($db->quoteName('#__pubdb_person_3418647.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->fk_value;
					}
				}

				$item->authors = !empty($textValue) ? implode(', ', $textValue) : $item->authors;
			}


			if (isset($item->translators))
			{

				$values    = explode(',', $item->translators);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('CONCAT(`#__pubdb_person_3418648`.`first_name`, \' \', `#__pubdb_person_3418648`.`last_name`, \' \', `#__pubdb_person_3418648`.`middle_name`) AS `fk_value`')
						->from($db->quoteName('#__pubdb_person', '#__pubdb_person_3418648'))
						->where($db->quoteName('#__pubdb_person_3418648.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->fk_value;
					}
				}

				$item->translators = !empty($textValue) ? implode(', ', $textValue) : $item->translators;
			}


			if (isset($item->others_involved))
			{

				$values    = explode(',', $item->others_involved);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('CONCAT(`#__pubdb_person_3418649`.`first_name`, \' \', `#__pubdb_person_3418649`.`last_name`, \' \', `#__pubdb_person_3418649`.`middle_name`) AS `fk_value`')
						->from($db->quoteName('#__pubdb_person', '#__pubdb_person_3418649'))
						->where($db->quoteName('#__pubdb_person_3418649.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->fk_value;
					}
				}

				$item->others_involved = !empty($textValue) ? implode(', ', $textValue) : $item->others_involved;
			}


			if (isset($item->publishers))
			{

				$values    = explode(',', $item->publishers);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__pubdb_publisher_3418659`.`name`')
						->from($db->quoteName('#__pubdb_publisher', '#__pubdb_publisher_3418659'))
						->where($db->quoteName('#__pubdb_publisher_3418659.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->name;
					}
				}

				$item->publishers = !empty($textValue) ? implode(', ', $textValue) : $item->publishers;
			}


			if (isset($item->keywords))
			{

				$values    = explode(',', $item->keywords);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__pubdb_keywords_3418670`.`name`')
						->from($db->quoteName('#__pubdb_keywords', '#__pubdb_keywords_3418670'))
						->where($db->quoteName('#__pubdb_keywords_3418670.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->name;
					}
				}

				$item->keywords = !empty($textValue) ? implode(', ', $textValue) : $item->keywords;
			}

		}

		return $items;
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
