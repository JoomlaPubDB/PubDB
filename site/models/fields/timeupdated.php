<?php
/**
 * @version    CVS: 0.0.5
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

/**
 * Supports an HTML select list of categories
 *
 * @since  1.6
 */
class JFormFieldTimeupdated extends \Joomla\CMS\Form\FormField
{
  /**
   * The form field type.
   *
   * @var        string
   * @since    1.6
   */
  protected $type = 'timeupdated';

  /**
   * Method to get the field input markup.
   *
   * @return    string    The field input markup.
   *
   * @since    1.6
   */
  protected function getInput()
  {
    // Initialize variables.
    $html = array();

    $old_time_updated = $this->value;
    $hidden = (boolean)$this->element['hidden'];

    if ($hidden == null || !$hidden) {
      if (!strtotime($old_time_updated)) {
        $html[] = '-';
      } else {
        $jdate = new JDate($old_time_updated);
        $pretty_date = $jdate->format(Text::_('DATE_FORMAT_LC2'));
        $html[] = "<div>" . $pretty_date . "</div>";
      }
    }

    $time_updated = Factory::getDate()->toSql();
    $html[] = '<input type="hidden" name="' . $this->name . '" value="' . $time_updated . '" />';

    return implode($html);
  }
}
