<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;
JHTML::addIncludePath(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers');

class PubdbViewLiteratures extends JViewLegacy
{
  protected $literatures;
  public function display($tpl = null)
  {
      PubDBHelper::addSubmenu("literatures");
    $this->literatures = $this->get('Items');
    $bar = JToolBar::getInstance('toolbar');
    JToolbarHelper::title(JText::_('COM_PUBDB_MANAGER_LITERATURES'), '');
    JToolbarHelper::addNew('literature.add');
    JToolbarHelper::editList('literature.edit');
    JToolbarHelper::deleteList(JText::_('COM_PUBDB_DELETE_CONFIRMATION'), 'Literature.delete', 'JTOOLBAR_DELETE');
    $this->sidebar = JHtmlSidebar::render();
    parent::display($tpl);
  }
}
