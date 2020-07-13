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

/**
 * Class JFormRuleDate
 *
 * @since  v0.0.7
 */
class JFormRuleName extends JFormRule
{
  /**
   * Server-side validation function for citation style
   * Is name set, custom error message
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
    if (empty($value) || !isset($value) || $value == '') {
      $element->addAttribute('message', JText::sprintf('COM_PUBDB_CITATIONSTLYE_NO_NAME', $element['label'], JText::sprintf('COM_PUBDB_TITLE_CITATIONSTYLES')));
      return false;
    }
    return true;
  }
}
