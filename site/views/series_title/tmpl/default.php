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


      <?php if (isset($this->item->name) && $this->item->name !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_SERIES_TITLE_NAME'); ?></th>
              <td><?php echo $this->item->name; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->series_title_editor) && $this->item->series_title_editor !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_SERIES_TITLE_SERIES_TITLE_EDITOR'); ?></th>
              <td><?php echo $this->item->series_title_editor; ?></td>
          </tr>
      <?php endif ?>

    </table>

</div>

