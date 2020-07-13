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

jimport('joomla.application.component.view');

use \Joomla\CMS\Language\Text;

/**
 * View class for a list of Pubdb.
 *
 * @since  1.6
 */
class PubdbViewPeople extends \Joomla\CMS\MVC\View\HtmlView
{
  protected $items;

  protected $pagination;

  protected $state;

  /**
   * Display the view
   *
   * @param string $tpl Template name
   *
   * @return void
   *
   * @throws Exception
   */
  public function display($tpl = null)
  {
    $this->state = $this->get('State');
    $this->items = $this->get('Items');
    $this->pagination = $this->get('Pagination');
    $this->filterForm = $this->get('FilterForm');
    $this->activeFilters = $this->get('ActiveFilters');

    // Check for errors.
    if (count($errors = $this->get('Errors'))) {
      throw new Exception(implode("\n", $errors));
    }

    PubdbHelper::addSubmenu('people');

    $this->addToolbar();

    $this->sidebar = JHtmlSidebar::render();
    parent::display($tpl);
  }

  /**
   * Add the page title and toolbar.
   *
   * @return void
   *
   * @since    1.6
   */
  protected function addToolbar()
  {
    $state = $this->get('State');
    $canDo = PubdbHelper::getActions();

    JToolBarHelper::title(Text::_('COM_PUBDB_TITLE_PEOPLE'), 'people.png');

    // Check if the form exists before showing the add/edit buttons
    $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/person';

    if (file_exists($formPath)) {
      if ($canDo->get('core.create')) {
        JToolBarHelper::addNew('person.add', 'JTOOLBAR_NEW');

        if (isset($this->items[0])) {
          JToolbarHelper::custom('people.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
        }
      }

      if ($canDo->get('core.edit') && isset($this->items[0])) {
        JToolBarHelper::editList('person.edit', 'JTOOLBAR_EDIT');
      }
    }

    if ($canDo->get('core.edit.state')) {
      if (isset($this->items[0]->state)) {
        JToolBarHelper::divider();
        JToolBarHelper::custom('people.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
        JToolBarHelper::custom('people.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
      } elseif (isset($this->items[0])) {
        // If this component does not use state then show a direct delete button as we can not trash
        JToolBarHelper::deleteList('', 'people.delete', 'JTOOLBAR_DELETE');
      }

      if (isset($this->items[0]->state)) {
        JToolBarHelper::divider();
        JToolBarHelper::archiveList('people.archive', 'JTOOLBAR_ARCHIVE');
      }

      if (isset($this->items[0]->checked_out)) {
        JToolBarHelper::custom('people.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
      }
    }

    // Show trash and delete for components that uses the state field
    if (isset($this->items[0]->state)) {
      if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
        JToolBarHelper::deleteList('', 'people.delete', 'JTOOLBAR_EMPTY_TRASH');
        JToolBarHelper::divider();
      } elseif ($canDo->get('core.edit.state')) {
        JToolBarHelper::trash('people.trash', 'JTOOLBAR_TRASH');
        JToolBarHelper::divider();
      }
    }

    if ($canDo->get('core.admin')) {
      JToolBarHelper::preferences('com_pubdb');
    }

    // Set sidebar action - New in 3.0
    JHtmlSidebar::setAction('index.php?option=com_pubdb&view=people');
  }

  /**
   * Method to order fields
   *
   * @return void
   */
  protected function getSortFields()
  {
    return array(
      'a.`id`' => JText::_('JGRID_HEADING_ID'),
      'a.`ordering`' => JText::_('JGRID_HEADING_ORDERING'),
      'a.`state`' => JText::_('JSTATUS'),
      'a.`first_name`' => JText::_('COM_PUBDB_PEOPLE_FIRST_NAME'),
      'a.`last_name`' => JText::_('COM_PUBDB_PEOPLE_LAST_NAME'),
      'a.`middle_name`' => JText::_('COM_PUBDB_PEOPLE_MIDDLE_NAME'),
      'a.`sex`' => JText::_('COM_PUBDB_PEOPLE_SEX'),
      'a.`title`' => JText::_('COM_PUBDB_PEOPLE_TITLE'),
    );
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
}
