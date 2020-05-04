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

    $this->sidebar = JHtmlSidebar::render();
    parent::display($tpl);
  }
}
