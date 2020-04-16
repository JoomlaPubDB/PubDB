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
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_PERSON_FIRST_NAME'); ?></th>
			<td><?php echo $this->item->first_name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_PERSON_LAST_NAME'); ?></th>
			<td><?php echo $this->item->last_name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_PERSON_MIDDLE_NAME'); ?></th>
			<td><?php echo $this->item->middle_name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_PERSON_SEX'); ?></th>
			<td><?php echo $this->item->sex; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_PERSON_TITLE'); ?></th>
			<td><?php echo $this->item->title; ?></td>
		</tr>

	</table>

</div>

