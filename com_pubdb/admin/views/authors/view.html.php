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

class PubdbViewAuthors extends JViewLegacy
{
  protected $literatures;
  public function display($tpl = null)
  {
    $this->literatures = $this->get('Items');
    $bar = JToolBar::getInstance('toolbar');
    JToolbarHelper::title(JText::_('COM_PUBDB_MANAGER_AUTHORS'), '');
    JToolbarHelper::addNew('people.add');
    JToolbarHelper::editList('people.edit');
    JToolbarHelper::deleteList(JText::_('COM_PUBDB_DELETE_CONFIRMATION'), 'people.delete', 'JTOOLBAR_DELETE');
    parent::display($tpl);
  }
}