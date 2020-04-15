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
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_TITLE'); ?></th>
			<td><?php echo $this->item->title; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_SUBTITLE'); ?></th>
			<td><?php echo $this->item->subtitle; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_PUBLISHED_ON'); ?></th>
			<td><?php echo $this->item->published_on; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_REFERENCE_TYPE'); ?></th>
			<td><?php echo $this->item->reference_type; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_ACCESS_DATE'); ?></th>
			<td><?php echo $this->item->access_date; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_LANGUAGE'); ?></th>
			<td><?php echo $this->item->language; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_DOI'); ?></th>
			<td><?php if( $this->item->reference_type == 1,2,3,4,5,6,7,8,9 ) echo $this->item->doi; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_ISBN'); ?></th>
			<td><?php if( $this->item->reference_type == 1,3,4,5 ) echo $this->item->isbn; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_ONLINE_ADDESS'); ?></th>
			<td><?php echo $this->item->online_addess; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_PAGE_COUNT'); ?></th>
			<td><?php if( $this->item->reference_type == 3,4,5,8,9 ) echo $this->item->page_count; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_PAGE_RANGE'); ?></th>
			<td><?php if( $this->item->reference_type == 1,3,4,5,8,9 ) echo $this->item->page_range; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_PERIODICAL_ID'); ?></th>
			<td><?php if( $this->item->reference_type == 3,5,7,8 ) echo $this->item->periodical_id; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_PLACE_OF_PUBLICATION'); ?></th>
			<td><?php echo $this->item->place_of_publication; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_PUB_MED_ID'); ?></th>
			<td><?php echo $this->item->pub_med_id; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_SERIES_TITLE_ID'); ?></th>
			<td><?php if( $this->item->reference_type == 12 ) echo $this->item->series_title_id; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_EISBN'); ?></th>
			<td><?php if( $this->item->reference_type == 1,3,4,5 ) echo $this->item->eisbn; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_VOLUME'); ?></th>
			<td><?php if( $this->item->reference_type == 1,4,5,12 ) echo $this->item->volume; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_AUTHORS'); ?></th>
			<td><?php echo $this->item->authors; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_TRANSLATORS'); ?></th>
			<td><?php echo $this->item->translators; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_OTHERS_INVOLVED'); ?></th>
			<td><?php echo $this->item->others_involved; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_PUBLISHERS'); ?></th>
			<td><?php echo $this->item->publishers; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_KEYWORDS'); ?></th>
			<td><?php echo $this->item->keywords; ?></td>
		</tr>

	</table>

</div>

