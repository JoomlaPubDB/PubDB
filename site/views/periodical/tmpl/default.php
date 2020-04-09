<?php
/**
 * @version    CVS: 0.0.1
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
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_PERIODICAL_ISSN'); ?></th>
			<td><?php echo $this->item->issn; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_PERIODICAL_NAME'); ?></th>
			<td><?php echo $this->item->name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_PERIODICAL_EISSN'); ?></th>
			<td><?php echo $this->item->eissn; ?></td>
		</tr>

	</table>

</div>

