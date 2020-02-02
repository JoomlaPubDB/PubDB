<?php
defined('_JEXEC') or die;
class PubdbViewLiterature extends JViewLegacy
{
  protected $literature;
  protected $form;

  public function display($tpl = null)
  {
    $this->literature = $this->get('Item');
    $this->form = $this->get('Form');
    JFactory::getApplication()->input->set('hidemainmenu', true);
    JToolbarHelper::title(JText::_('COM_PUBDB_MANAGER_LITERATURE'), '');

    JToolbarHelper::save('literature.save');

    if (($this->literature->pubdb_literatur_id))
    {
      JToolbarHelper::cancel('literature.cancel', 'JTOOLBAR_CANCEL');
    }
    else
    {
      JToolbarHelper::cancel('literature.cancel', 'JTOOLBAR_CLOSE');
    }
    parent::display($tpl);
  }
}
