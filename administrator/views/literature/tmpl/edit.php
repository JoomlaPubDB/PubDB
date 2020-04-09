<?php
/**
 * @version    CVS: 0.0.1
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;


HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');
HTMLHelper::_('behavior.keepalive');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_pubdb/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	js('input:hidden.periodical_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('periodical_idhidden')){
			js('#jform_periodical_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_periodical_id").trigger("liszt:updated");
	js('input:hidden.series_title_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('series_title_idhidden')){
			js('#jform_series_title_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_series_title_id").trigger("liszt:updated");
	});

	Joomla.submitbutton = function (task) {
		if (task == 'literature.cancel') {
			Joomla.submitform(task, document.getElementById('literature-form'));
		}
		else {
			
			if (task != 'literature.cancel' && document.formvalidator.isValid(document.id('literature-form'))) {
				
				Joomla.submitform(task, document.getElementById('literature-form'));
			}
			else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_pubdb&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="literature-form" class="form-validate form-horizontal">

	
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
	<?php echo $this->form->renderField('created_by'); ?>
	<?php echo $this->form->renderField('modified_by'); ?>
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'literature')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'literature', JText::_('COM_PUBDB_TAB_LITERATURE', true)); ?>
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_PUBDB_FIELDSET_LITERATURE'); ?></legend>
				<?php echo $this->form->renderField('title'); ?>
				<?php echo $this->form->renderField('subtitle'); ?>
				<?php echo $this->form->renderField('published_on'); ?>
				<?php echo $this->form->renderField('reference_type'); ?>
				<?php echo $this->form->renderField('access_date'); ?>
				<?php echo $this->form->renderField('language'); ?>
				<?php echo $this->form->renderField('doi'); ?>
				<?php echo $this->form->renderField('isbn'); ?>
				<?php echo $this->form->renderField('online_addess'); ?>
				<?php echo $this->form->renderField('page_count'); ?>
				<?php echo $this->form->renderField('page_range'); ?>
				<?php echo $this->form->renderField('periodical_id'); ?>
				<?php
				foreach((array)$this->item->periodical_id as $value)
				{
					if(!is_array($value))
					{
						echo '<input type="hidden" class="periodical_id" name="jform[periodical_idhidden]['.$value.']" value="'.$value.'" />';
					}
				}
				?>
				<?php echo $this->form->renderField('place_of_publication'); ?>
				<?php echo $this->form->renderField('pub_med_id'); ?>
				<?php echo $this->form->renderField('series_title_id'); ?>
				<?php
				foreach((array)$this->item->series_title_id as $value)
				{
					if(!is_array($value))
					{
						echo '<input type="hidden" class="series_title_id" name="jform[series_title_idhidden]['.$value.']" value="'.$value.'" />';
					}
				}
				?>
				<?php echo $this->form->renderField('eisbn'); ?>
				<?php echo $this->form->renderField('volume'); ?>
				<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
				<?php endif; ?>
			</fieldset>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>

</form>
