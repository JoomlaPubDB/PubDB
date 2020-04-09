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
		
	js('input:hidden.series_title_editor').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('series_title_editorhidden')){
			js('#jform_series_title_editor option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_series_title_editor").trigger("liszt:updated");
	});

	Joomla.submitbutton = function (task) {
		if (task == 'series_title.cancel') {
			Joomla.submitform(task, document.getElementById('series_title-form'));
		}
		else {
			
			if (task != 'series_title.cancel' && document.formvalidator.isValid(document.id('series_title-form'))) {
				
				Joomla.submitform(task, document.getElementById('series_title-form'));
			}
			else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_pubdb&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="series_title-form" class="form-validate form-horizontal">

	
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
	<?php echo $this->form->renderField('created_by'); ?>
	<?php echo $this->form->renderField('modified_by'); ?>
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'periodical')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'periodical', JText::_('COM_PUBDB_TAB_PERIODICAL', true)); ?>
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_PUBDB_FIELDSET_PERIODICAL'); ?></legend>
				<?php echo $this->form->renderField('name'); ?>
				<?php echo $this->form->renderField('series_title_editor'); ?>
				<?php
				foreach((array)$this->item->series_title_editor as $value)
				{
					if(!is_array($value))
					{
						echo '<input type="hidden" class="series_title_editor" name="jform[series_title_editorhidden]['.$value.']" value="'.$value.'" />';
					}
				}
				?>
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
