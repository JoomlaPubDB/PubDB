<?php

/**
 * @version    CVS: 0.0.5
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */
defined('_JEXEC') or die;

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
				'id', 'a.`id`',
				'ordering', 'a.`ordering`',
				'state', 'a.`state`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'year', 'a.`year`',
				'month', 'a.`month`',
				'day', 'a.`day`',
				'title', 'a.`title`',
				'subtitle', 'a.`subtitle`',
				'published_on', 'a.`published_on`',
				'reference_type', 'a.`reference_type`',
				'access_date', 'a.`access_date`',
		'access_date.from', 'access_date.to',
				'language', 'a.`language`',
				'doi', 'a.`doi`',
				'isbn', 'a.`isbn`',
				'online_address', 'a.`online_address`',
				'page_count', 'a.`page_count`',
				'page_range', 'a.`page_range`',
				'periodical_id', 'a.`periodical_id`',
				'place_of_publication', 'a.`place_of_publication`',
				'pub_med_id', 'a.`pub_med_id`',
				'series_title_id', 'a.`series_title_id`',
				'eisbn', 'a.`eisbn`',
				'volume', 'a.`volume`',
				'authors', 'a.`authors`',
				'translators', 'a.`translators`',
				'others_involved', 'a.`others_involved`',
				'publishers', 'a.`publishers`',
				'keywords', 'a.`keywords`',
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
	 */
	protected function populateState($ordering = null, $direction = null)
	{
        // List state information.
        parent::populateState("a.id", "ASC");

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
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

                
                    return parent::getStoreId($id);
                
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
                
		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');
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
                

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
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
				$query->where('( a.title LIKE ' . $search . '  OR  a.subtitle LIKE ' . $search . '  OR  a.doi LIKE ' . $search . '  OR  a.isbn LIKE ' . $search . '  OR  a.eisbn LIKE ' . $search . '  OR CONCAT(`#__pubdb_person_3418647`.`first_name`, \' \', `#__pubdb_person_3418647`.`last_name`, \' \', `#__pubdb_person_3418647`.`middle_name`) LIKE ' . $search . '  OR CONCAT(`#__pubdb_person_3418648`.`first_name`, \' \', `#__pubdb_person_3418648`.`last_name`, \' \', `#__pubdb_person_3418648`.`middle_name`) LIKE ' . $search . '  OR CONCAT(`#__pubdb_person_3418649`.`first_name`, \' \', `#__pubdb_person_3418649`.`last_name`, \' \', `#__pubdb_person_3418649`.`middle_name`) LIKE ' . $search . '  OR #__pubdb_keywords_3418670.name LIKE ' . $search . ' )');
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

		if ($filter_keywords !== null && !empty($filter_keywords))
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
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
                
		foreach ($items as $oneItem)
		{

			if (isset($oneItem->reference_type))
			{
				$values    = explode(',', $oneItem->reference_type);
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

				$oneItem->reference_type = !empty($textValue) ? implode(', ', $textValue) : $oneItem->reference_type;
			}

			if (isset($oneItem->authors))
			{
				$values    = explode(',', $oneItem->authors);
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

				$oneItem->authors = !empty($textValue) ? implode(', ', $textValue) : $oneItem->authors;
			}

			if (isset($oneItem->keywords))
			{
				$values    = explode(',', $oneItem->keywords);
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

				$oneItem->keywords = !empty($textValue) ? implode(', ', $textValue) : $oneItem->keywords;
			}
		}

		return $items;
	}
}
