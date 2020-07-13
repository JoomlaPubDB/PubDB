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
 * Class JFormRuleDate
 *
 * @since  v0.0.7
 */
class JFormRuleDate extends JFormRule
{
  /**
   * Server-side validation function for access date
   * Is date in the past
   *
   * @param SimpleXMLElement $element
   * @param mixed $value
   * @param null $group
   * @param JRegistry|null $input
   * @param JForm|null $form
   * @return bool
   *
   * @since v0.0.7
   */
  public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
  {
    $today = new Date();
    $expected = new Date($value);
    if ($expected->format('Y-m-d') > $today->format('Y-m-d')) {
      $element->addAttribute('message', JText::sprintf('COM_PUBDB_LITERATURES_DATE_IN_FUTURE', JText::sprintf($element['label'])));
      return false;
    }
    return true;
  }
}
