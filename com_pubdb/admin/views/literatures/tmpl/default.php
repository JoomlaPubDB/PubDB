<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

?>
<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<form action="<?php echo JRoute::_('index.php?option=com_pubdb&view=literatures'); ?>" method="post" name="adminForm" id="adminForm">
  <div id="j-main-container">
    <div class="clearfix"> </div>
    <table class="table table-striped" id="literaturelist">
      <thead>
      <tr>
        <th width="1%" class="nowrap center hidden-phone">
          <input type="checkbox" name="checkall-toggle" value="" title="
              <?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick= "Joomla.checkAll(this);" />
        </th>
        <th>
          <?php echo JText::_('COM_PUBDB_COLUMN_HEADER_TITLE'); ?>
        </th>
        <th class="nowrap hidden-phone">
          <?php echo JText::_('COM_LOCATION_COLUMN_HEADER_INTROTEXT'); ?>
        </th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($this->literatures as $i=>$literature) :?>
      <tr class="row<?php echo $i % 2; ?>">
        <td class="nowrap center hidden-phone">
          <?php echo JHtml::_('grid.id', $i, $literature->pubdb_literatur_id); ?>
        </td>
        <td class="has-context">
          <a href="<?php echo JRoute::_('index.php?option=com_pubdb&task=literature.edit&id='. (int) $literature->pubdb_literatur_id); ?>">
            <?php echo $this->escape($literature->Title); ?>
          </a>
        </td>
        <td class="small">
          <?php echo $this->escape($literature->Subtitle); ?>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>

    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo JHtml::_('form.token'); ?>
  </div>
</form>
</div>
