<?php
defined('_JEXEC') or die;
?>
<form action="<?php echo JRoute::_('index.php?option=com_pubdb&layout=edit&id=' . (int) $this->people->pubdb_people_id); ?>" method="post" name="adminForm"
      id="adminForm" class="form-validate">
  <div class="row-fluid">
    <div class="span10 form-horizontal">
      <fieldset>
        <?php echo JHtml::_('bootstrap.startTabSet', 'editPeople',
          array('active' => 'general')); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'editPeople',
          'general', ($this->people->pubdb_people_id) ? JText::_('COM_PUBDB_NEW_PEOPLE') : JText::sprintf('COM_LOCATION_EDIT_PEOPLE', $this->people->pubdb_people_id)); ?>
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('firstname'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('firstname'); ?></div>

        </div>

        <div class="control-group">

          <div class="control-label"><?php echo $this->form->getLabel('lastname'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('lastname'); ?></div>

        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
      </fieldset>
    </div>
  </div>
</form>
