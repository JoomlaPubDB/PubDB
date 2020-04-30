<?php
/**
 * @version    CVS: 0.0.5
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

use \Joomla\CMS\Table\Table;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Plugin\PluginHelper;

/**
 * Pubdb model.
 *
 * @since  1.6
 */
class PubdbModelImporter extends \Joomla\CMS\MVC\Model\AdminModel
{


  function __construct($config = array())
  {
    parent::__construct($config);
  }

  /**
   * Returns a reference to the a Table object, always creating it.
   *
   * @param   string  $type    The table type to instantiate
   * @param   string  $prefix  A prefix for the table class name. Optional.
   * @param   array   $config  Configuration array for model. Optional.
   *
   * @return    JTable    A database object
   *
   * @since    1.6
   */
  public function getTable($type = 'Block', $prefix = 'PubdbTable', $config = array())
  {
    return Table::getInstance($type, $prefix, $config);
  }

  /**
   * Method to get the record form.
   *
   * @param   array    $data      An optional array of data for the form to interogate.
   * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
   *
   * @return  JForm  A JForm object on success, false on failure
   *
   * @since    1.6
   *
   * @throws
   */
  public function getForm($data = array(), $loadData = true)
  {
    // Initialise variables.
    $app = Factory::getApplication();

    // Get the form.
    $form = $this->loadForm(
      'com_pubdb.importer', 'importer',
      array('control' => 'jform',
        'load_data' => $loadData
      )
    );

    if (empty($form))
    {
      return false;
    }

    return $form;
  }

  /**
   * Method to get the data that should be injected in the form.
   *
   * @return   mixed  The data for the form.
   *
   * @since    1.6
   *
   * @throws
   */
  protected function loadFormData()
  {
    // Check the session for previously entered form data.
    $data = Factory::getApplication()->getUserState('com_pubdb.edit.importer.data', array());

    if (empty($data))
    {
      if ($this->item === null)
      {
        $this->item = $this->getItem();
      }

      $data = $this->item;

    }

    return $data;
  }
}
