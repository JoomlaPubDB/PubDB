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

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

/**
 * View to edit
 *
 * @since  1.6
 */
class PubdbViewImporter extends JViewLegacy
{
  protected $state;

  protected $item;

  protected $form;

  /**
   * Display the view
   *
   * @param   string  $tpl  Template name
   *
   * @return void
   *
   * @throws Exception
   */
  public function display($tpl = null)
  {
    $this->state            = $this->get('State');
    $this->form     = $this->get('Form');

    // Check for errors.
    if (count($errors = $this->get('Errors')))
    {
      throw new Exception(implode("\n", $errors));
    }

    PubdbHelper::addSubmenu('importer');

    $this->addToolbar();

    $this->sidebar = JHtmlSidebar::render();
    parent::display($tpl);
  }

  /**
   * Add the page title and toolbar.
   *
   * @return void
   *
   * @throws Exception
   */
  protected function addToolbar()
  {
    Factory::getApplication()->input->set('hidemainmenu', false);

    $user  = Factory::getUser();
    $isNew = ($this->item->id == 0);

    if (isset($this->item->checked_out))
    {
      $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
    }
    else
    {
      $checkedOut = false;
    }

    $canDo = PubdbHelper::getActions();

    JToolBarHelper::title(Text::_('COM_PUBDB_TITLE_IMPORTER'), 'importer.png');

    // If not checked out, can save the item.
    if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create'))))
    {
      JToolBarHelper::apply('importer.apply', 'JTOOLBAR_APPLY');
      JToolBarHelper::save('importer.save', 'JTOOLBAR_SAVE');
    }

    if (!$checkedOut && ($canDo->get('core.create')))
    {
      JToolBarHelper::custom('importer.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
    }

    // If an existing item, can save to a copy.
    if (!$isNew && $canDo->get('core.create'))
    {
      JToolBarHelper::custom('importer.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
    }

    // Button for version control

    if (empty($this->item->id))
    {
      JToolBarHelper::cancel('importer.cancel', 'JTOOLBAR_CANCEL');
    }
    else
    {
      JToolBarHelper::cancel('importer.cancel', 'JTOOLBAR_CLOSE');
    }
  }
}
