<?php
/**
 * @version    CVS: 0.0.3
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;


?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_SERIES_TITLE_NAME'); ?></th>
			<td><?php echo $this->item->name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_SERIES_TITLE_SERIES_TITLE_EDITOR'); ?></th>
			<td><?php echo $this->item->series_title_editor; ?></td>
		</tr>

	</table>

</div>

