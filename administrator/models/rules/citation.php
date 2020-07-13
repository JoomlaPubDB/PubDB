<?php
/**
 * @version    CVS: 0.0.7
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\Date\Date;

/**
 * Class JFormRuleCitation
 *
 * @since  v0.0.7
 */
class JFormRuleCitation extends JFormRule
{
  /**
   * Server-side validation function for citation style
   * Is default citation style filled
   *
   * @param SimpleXMLElement $element
   * @param mixed $value
   * @param null $group
   * @param JRegistry|null $input
   * @param JForm|null $form
   * @return bool
   *
   * @since  v0.0.7
   */
  public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
  {
    if (!strpos($value, '-1')) {
      $element->addAttribute('message',  JText::sprintf('COM_PUBDB_CITATIONSTLYE_NO_MISC', JText::sprintf('COM_PUBDB_REF_TYPE_MISC')));
      return false;
    }
    return true;
  }
}
