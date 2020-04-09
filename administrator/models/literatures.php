<?php

/**
 * @version    CVS: 0.0.1
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
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
				'title', 'a.`title`',
				'subtitle', 'a.`subtitle`',
				'published_on', 'a.`published_on`',
				'reference_type', 'a.`reference_type`',
				'access_date', 'a.`access_date`',
		'access_date.from', 'access_date.to',
				'language', 'a.`language`',
				'doi', 'a.`doi`',
				'isbn', 'a.`isbn`',
				'online_addess', 'a.`online_addess`',
				'page_count', 'a.`page_count`',
				'page_range', 'a.`page_range`',
				'periodical_id', 'a.`periodical_id`',
				'place_of_publication', 'a.`place_of_publication`',
				'pub_med_id', 'a.`pub_med_id`',
				'series_title_id', 'a.`series_title_id`',
				'eisbn', 'a.`eisbn`',
				'volume', 'a.`volume`',
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
		// Join over the foreign key 'periodical_id'
		$query->select('CONCAT(`#__pubdb_periodical_3418585`.`issn`, \' \', `#__pubdb_periodical_3418585`.`name`) AS periodicals_fk_value_3418585');
		$query->join('LEFT', '#__pubdb_periodical AS #__pubdb_periodical_3418585 ON #__pubdb_periodical_3418585.`id` = a.`periodical_id`');
		// Join over the foreign key 'series_title_id'
		$query->select('`#__pubdb_series_title_3418632`.`name` AS periodicals_fk_value_3418632');
		$query->join('LEFT', '#__pubdb_series_title AS #__pubdb_series_title_3418632 ON #__pubdb_series_title_3418632.`id` = a.`series_title_id`');
                

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
				$query->where('( a.title LIKE ' . $search . '  OR  a.subtitle LIKE ' . $search . '  OR  a.doi LIKE ' . $search . '  OR  a.isbn LIKE ' . $search . '  OR  a.eisbn LIKE ' . $search . ' )');
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
					$oneItem->reference_type = ($oneItem->reference_type == '') ? '' : JText::_('COM_PUBDB_LITERATURES_REFERENCE_TYPE_OPTION_' . strtoupper($oneItem->reference_type));
		}

		return $items;
	}
}
