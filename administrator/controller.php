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

use \Joomla\CMS\Factory;

/**‚
 * Class PubdbController
 *
 * @since  1.6
 */
class PubdbController extends \Joomla\CMS\MVC\Controller\BaseController
{
  /**
   * Method to display a view.
   *
   * @param boolean $cachable If true, the view output will be cached
   * @param mixed $urlparams An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
   *
   * @return   JController This object to support chaining.
   *
   * @throws Exception
   * @since    1.5
   */
  public function display($cachable = false, $urlparams = false)
  {
    $view = Factory::getApplication()->input->getCmd('view', 'literatures');
    Factory::getApplication()->input->set('view', $view);

    parent::display($cachable, $urlparams);

    return $this;
  }
}
