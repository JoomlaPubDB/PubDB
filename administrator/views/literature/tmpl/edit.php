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

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
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

        js('input:hidden.reference_type').each(function () {
            var name = js(this).attr('name');
            if (name.indexOf('reference_typehidden')) {
                js('#jform_reference_type option[value="' + js(this).val() + '"]').attr('selected', true);
            }
        });
        js("#jform_reference_type").trigger("liszt:updated");
        js('input:hidden.periodical_id').each(function () {
            var name = js(this).attr('name');
            if (name.indexOf('periodical_idhidden')) {
                js('#jform_periodical_id option[value="' + js(this).val() + '"]').attr('selected', true);
            }
        });
        js("#jform_periodical_id").trigger("liszt:updated");
        js('input:hidden.series_title_id').each(function () {
            var name = js(this).attr('name');
            if (name.indexOf('series_title_idhidden')) {
                js('#jform_series_title_id option[value="' + js(this).val() + '"]').attr('selected', true);
            }
        });
        js("#jform_series_title_id").trigger("liszt:updated");
        js('input:hidden.authors').each(function () {
            var name = js(this).attr('name');
            if (name.indexOf('authorshidden')) {
                js('#jform_authors option[value="' + js(this).val() + '"]').attr('selected', true);
            }
        });
        js("#jform_authors").trigger("liszt:updated");
        js('input:hidden.translators').each(function () {
            var name = js(this).attr('name');
            if (name.indexOf('translatorshidden')) {
                js('#jform_translators option[value="' + js(this).val() + '"]').attr('selected', true);
            }
        });
        js("#jform_translators").trigger("liszt:updated");
        js('input:hidden.others_involved').each(function () {
            var name = js(this).attr('name');
            if (name.indexOf('others_involvedhidden')) {
                js('#jform_others_involved option[value="' + js(this).val() + '"]').attr('selected', true);
            }
        });
        js("#jform_others_involved").trigger("liszt:updated");
        js('input:hidden.publishers').each(function () {
            var name = js(this).attr('name');
            if (name.indexOf('publishershidden')) {
                js('#jform_publishers option[value="' + js(this).val() + '"]').attr('selected', true);
            }
        });
        js("#jform_publishers").trigger("liszt:updated");
        js('input:hidden.keywords').each(function () {
            var name = js(this).attr('name');
            if (name.indexOf('keywordshidden')) {
                js('#jform_keywords option[value="' + js(this).val() + '"]').attr('selected', true);
            }
        });
        js("#jform_keywords").trigger("liszt:updated");
    });

    Joomla.submitbutton = function (task) {
        if (task == 'literature.cancel') {
            Joomla.submitform(task, document.getElementById('literature-form'));
        } else {

            if (task != 'literature.cancel' && document.formvalidator.isValid(document.id('literature-form'))) {

                Joomla.submitform(task, document.getElementById('literature-form'));
            } else {
                alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form
        action="<?php echo JRoute::_('index.php?option=com_pubdb&layout=edit&id=' . (int)$this->item->id); ?>"
        method="post" enctype="multipart/form-data" name="adminForm" id="literature-form"
        class="form-validate form-horizontal"
        onsubmit="return validateForm()">


    <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>"/>
    <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>"/>
    <input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>"/>
    <input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>"/>
    <input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>"/>
  <?php echo $this->form->renderField('created_by'); ?>
  <?php echo $this->form->renderField('modified_by'); ?>
    <input type="hidden" name="jform[year]" value="<?php echo $this->item->year; ?>"/>
    <input type="hidden" name="jform[month]" value="<?php echo $this->item->month; ?>"/>
    <input type="hidden" name="jform[day]" value="<?php echo $this->item->day; ?>"/>
  <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'literature')); ?>
  <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'literature', JText::_('COM_PUBDB_TAB_LITERATURE', true)); ?>
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_PUBDB_FIELDSET_LITERATURE'); ?></legend>
              <?php echo $this->form->renderField('title'); ?>
              <?php echo $this->form->renderField('subtitle'); ?>
              <?php echo $this->form->renderField('year'); ?>
              <?php echo $this->form->renderField('month'); ?>
              <?php echo $this->form->renderField('day'); ?>
              <?php echo $this->form->renderField('reference_type'); ?>
              <?php
              foreach ((array)$this->item->reference_type as $value) {
                if (!is_array($value)) {
                  echo '<input type="hidden" class="reference_type" name="jform[reference_typehidden][' . $value . ']" value="' . $value . '" />';
                }
              }
              ?>
              <?php echo $this->form->renderField('access_date'); ?>
              <?php echo $this->form->renderField('language'); ?>
              <?php echo $this->form->renderField('doi'); ?>
              <?php echo $this->form->renderField('isbn'); ?>
              <?php echo $this->form->renderField('online_address'); ?>
              <?php echo $this->form->renderField('page_count'); ?>
              <?php echo $this->form->renderField('page_range'); ?>
              <?php echo $this->form->renderField('periodical_id'); ?>
              <?php
              foreach ((array)$this->item->periodical_id as $value) {
                if (!is_array($value)) {
                  echo '<input type="hidden" class="periodical_id" name="jform[periodical_idhidden][' . $value . ']" value="' . $value . '" />';
                }
              }
              ?>
              <?php echo $this->form->renderField('place_of_publication'); ?>
              <?php echo $this->form->renderField('pub_med_id'); ?>
              <?php echo $this->form->renderField('series_title_id'); ?>
              <?php
              foreach ((array)$this->item->series_title_id as $value) {
                if (!is_array($value)) {
                  echo '<input type="hidden" class="series_title_id" name="jform[series_title_idhidden][' . $value . ']" value="' . $value . '" />';
                }
              }
              ?>
              <?php echo $this->form->renderField('eisbn'); ?>
              <?php echo $this->form->renderField('volume'); ?>
              <?php echo $this->form->renderField('authors'); ?>
              <?php
              foreach ((array)$this->item->authors as $value) {
                if (!is_array($value)) {
                  echo '<input type="hidden" class="authors" name="jform[authorshidden][' . $value . ']" value="' . $value . '" />';
                }
              }
              ?>
              <?php echo $this->form->renderField('translators'); ?>
              <?php
              foreach ((array)$this->item->translators as $value) {
                if (!is_array($value)) {
                  echo '<input type="hidden" class="translators" name="jform[translatorshidden][' . $value . ']" value="' . $value . '" />';
                }
              }
              ?>
              <?php echo $this->form->renderField('others_involved'); ?>
              <?php
              foreach ((array)$this->item->others_involved as $value) {
                if (!is_array($value)) {
                  echo '<input type="hidden" class="others_involved" name="jform[others_involvedhidden][' . $value . ']" value="' . $value . '" />';
                }
              }
              ?>
              <?php echo $this->form->renderField('publishers'); ?>
              <?php
              foreach ((array)$this->item->publishers as $value) {
                if (!is_array($value)) {
                  echo '<input type="hidden" class="publishers" name="jform[publishershidden][' . $value . ']" value="' . $value . '" />';
                }
              }
              ?>
              <?php echo $this->form->renderField('keywords'); ?>
              <?php
              foreach ((array)$this->item->keywords as $value) {
                if (!is_array($value)) {
                  echo '<input type="hidden" class="keywords" name="jform[keywordshidden][' . $value . ']" value="' . $value . '" />';
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

  <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'authors', JText::_('COM_PUBDB_TAB_LITERATURE_AUTHOR', true)); ?>
    <legend><?php echo JText::_('COM_PUBDB_TAB_LITERATURE_AUTHOR'); ?></legend>
  <?php echo $this->form->renderField('author_subform'); ?>
  <?php echo JHtml::_('bootstrap.endTab'); ?>

  <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'translators', JText::_('COM_PUBDB_TAB_LITERATURE_TRANSLATOR', true)); ?>
    <legend><?php echo JText::_('COM_PUBDB_TAB_LITERATURE_TRANSLATOR'); ?></legend>
  <?php echo $this->form->renderField('translator_subform'); ?>
  <?php echo JHtml::_('bootstrap.endTab'); ?>

  <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'others', JText::_('COM_PUBDB_TAB_LITERATURE_OTHERS', true)); ?>
    <legend><?php echo JText::_('COM_PUBDB_TAB_LITERATURE_OTHERS'); ?></legend>
  <?php echo $this->form->renderField('other_subform'); ?>
  <?php echo JHtml::_('bootstrap.endTab'); ?>

  <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publisher', JText::_('COM_PUBDB_TAB_LITERATURE_PUBLISHER', true)); ?>
    <legend><?php echo JText::_('COM_PUBDB_TAB_LITERATURE_PUBLISHER'); ?></legend>
  <?php echo $this->form->renderField('publisher_subform'); ?>
  <?php echo JHtml::_('bootstrap.endTab'); ?>

  <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'keyword', JText::_('COM_PUBDB_TAB_LITERATURE_KEYWORD', true)); ?>
    <legend><?php echo JText::_('COM_PUBDB_TAB_LITERATURE_KEYWORD'); ?></legend>
  <?php echo $this->form->renderField('keyword_subform'); ?>
  <?php echo JHtml::_('bootstrap.endTab'); ?>

  <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'periodical', JText::_('COM_PUBDB_TAB_LITERATURE_PERIODICAL', true)); ?>
    <legend><?php echo JText::_('COM_PUBDB_TAB_LITERATURE_PERIODICAL'); ?></legend>
  <?php echo $this->form->renderField('periodical_subform'); ?>
  <?php echo JHtml::_('bootstrap.endTab'); ?>

  <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'series_title', JText::_('COM_PUBDB_TAB_LITERATURE_SERIES_TITLE', true)); ?>
    <legend><?php echo JText::_('COM_PUBDB_TAB_LITERATURE_SERIES_TITLE'); ?></legend>
  <?php echo $this->form->renderField('series_title_subform'); ?>
  <?php echo JHtml::_('bootstrap.endTab'); ?>


  <?php echo JHtml::_('bootstrap.endTabSet'); ?>

    <input type="hidden" name="task" value=""/>
  <?php echo JHtml::_('form.token'); ?>

</form>
