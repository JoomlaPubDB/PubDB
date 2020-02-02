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

<form action="<?php echo JRoute::_('index.php?option=com_pubdb&view=authors'); ?>" method="post" name="adminForm" id="adminForm">
  <div id="j-main-container">
    <div class="clearfix"> </div>
    <table class="table table-striped" id="authorlist">
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
      <?php foreach ($this->people as $i=>$people) :?>
      <tr class="row<?php echo $i % 2; ?>">
        <td class="nowrap center hidden-phone">
          <?php echo JHtml::_('grid.id', $i, $literature->pubdp_People_id); ?>
        </td>
        <td class="has-context">
          <a href="<?php echo JRoute::_('index.php?option=com_pubdb&task=people.edit&id='. (int) $people->pubdp_People_id); ?>">
            <?php echo $this->escape($people->Name); ?>
          </a>
        </td>
        <td class="small">
          <?php echo $this->escape($people->Surname); ?>
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