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

class JFormRuleName extends JFormRule
{
  public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
  {
    if (empty($value) || !isset($value)  || $value == '') {
      $element->addAttribute('message',  JText::sprintf('COM_PUBDB_CITATIONSTLYE_NO_NAME', $element['label'], JText::sprintf('COM_PUBDB_TITLE_CITATIONSTYLES')));
      return false;
    }
    return true;
  }
}
