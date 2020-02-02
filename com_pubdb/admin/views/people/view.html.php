<?php
defined('_JEXEC') or die;
JHTML::addIncludePath(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers');

class PubdbViewPeople extends JViewLegacy
{
  protected $people;
  protected $form;

  public function display($tpl = null)
  {
    PubDBHelper::addSubmenu("people");
    $this->sidebar = JHtmlSidebar::render();
    $this->people = $this->get('Item');
    $this->form = $this->get('Form');
    JFactory::getApplication()->input->set('hidemainmenu', true);
    JToolbarHelper::title(JText::_('COM_PUBDB_MANAGER_PEOPLE'), '');

    JToolbarHelper::save('people.save');

    if (($this->people->pubdb_People_id))
    {
      JToolbarHelper::cancel('people.cancel', 'JTOOLBAR_CANCEL');
    }
    else
    {
      JToolbarHelper::cancel('people.cancel', 'JTOOLBAR_CLOSE');
    }
    parent::display($tpl);
  }
}