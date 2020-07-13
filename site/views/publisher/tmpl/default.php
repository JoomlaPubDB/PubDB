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


?>

<div class="item_fields">

    <table class="table">


        <tr>
            <th><?php echo JText::_('COM_PUBDB_FORM_LBL_PUBLISHER_NAME'); ?></th>
            <td><?php echo $this->item->name; ?></td>
        </tr>

    </table>

</div>

