<?php
/**
 * @version    CVS: 0.0.6
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
JHtml::_('formbehavior.chosen', 'select');
?>
<form action="<?php echo JRoute::_('index.php?option=com_pubdb&view=importer'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
  <?php if (!empty( $this->sidebar)) : ?>
  <div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
  </div>
  <div id="j-main-container" class="span10">
    <?php else : ?>
    <div id="j-main-container">
      <?php endif;?>
      <div class="row-fluid">
        <fieldset class="form-horizontal">
          <legend><?php echo JText::_( 'COM_PUBDB_TITLE_IMPORTER' ); ?></legend>
          <div class="row-fluid">
            <div class="span6">
              <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('type'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('type'); ?></div>
              </div>
              <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('import_file'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('import_file'); ?></div>
              </div>
              <button class="btn btn-primary" type="submit"
                      onclick="document.getElementById('task').value = 'importer.import';this.form.submit()"/>
              <i class="icon-upload icon-white"></i> <?php echo JText::_('COM_PUBDB_IMPORT'); ?></button>
            </div>
            <div class="span6">
              <div class="alert alert-info"><?php echo JText::_('COM_PUBDB_IMPORT_TYPE_TIPS'); ?></div>
            </div>
          </div>
        </fieldset>
      </div>
      <div class="row-fluid">
        <fieldset class="form-horizontal">
          <legend><?php echo JText::_('COM_PUBDB_TITLE_EXPORTER'); ?></legend>
          <div class="row-fluid">
            <div class="span6">
              <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('type_export'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('type_export'); ?></div>
              </div>
              <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('export_file'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('export_file'); ?></div>
              </div>
              <button class="btn btn-primary" type="submit"
                      onclick="document.getElementById('task').value = 'importer.export';this.form.submit()"/>
              <i class="icon-download icon-white"></i> <?php echo JText::_('COM_PUBDB_EXPORT'); ?></button>
            </div>
          </div>
        </fieldset>
      </div>
    </div>
    <?php echo JHTML::_('form.token'); ?>
    <input type="hidden" name="option" value="com_pubdb"/>
    <input type="hidden" name="task" id="task" value="importer.import"/>
    <input type="hidden" name="controller" value="importer"/>
</form>
<div class="clearfix"></div>
