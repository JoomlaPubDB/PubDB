<?php
defined('_JEXEC') or die;
?>
<form action="<?php echo JRoute::_('index.php?option=com_pubdb&layout=edit&id=' . (int) $this->literature->pubdb_literatur_id); ?>" method="post" name="adminForm"
      id="adminForm" class="form-validate">
  <div class="row-fluid">
    <div class="span10 form-horizontal">
      <fieldset>
        <?php echo JHtml::_('bootstrap.startTabSet', 'editLiterature',
          array('active' => 'general')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'editLiterature',
          'general', ($this->literature->pubdb_literatur_id) ? JText::_('COM_PUBDB_NEW_LITERATURE') : JText::sprintf('COM_LOCATION_EDIT_LITERATURE', $this->literature->pubdb_literatur_id)); ?>
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('Title'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('Title'); ?></div>

        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('Subtitle'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('Subtitle'); ?></div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
      </fieldset>
    </div>
  </div>
</form>

