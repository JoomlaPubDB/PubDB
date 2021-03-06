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

      <?php if (isset($this->item->title) && $this->item->title !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_TITLE'); ?></th>
              <td><?php echo $this->item->title; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->subtitle) && $this->item->subtitle !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_SUBTITLE'); ?></th>
              <td><?php echo $this->item->subtitle; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->published_on) && $this->item->published_on !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_PUBLISHED_ON'); ?></th>
              <td><?php echo $this->item->published_on; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->reference_type) && $this->item->reference_type !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_REFERENCE_TYPE'); ?></th>
              <td><?php echo JText::_($this->item->reference_type); ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->access_date) && $this->item->access_date !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_ACCESS_DATE'); ?></th>
              <td><?php echo $this->item->access_date; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->language) && $this->item->language !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_LANGUAGE'); ?></th>
              <td><?php echo $this->item->language; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->doi) && $this->item->doi !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_DOI'); ?></th>
              <td><?php echo $this->item->doi; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->isbn) && $this->item->isbn !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_ISBN'); ?></th>
              <td><?php echo $this->item->isbn; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->online_address) && $this->item->online_address !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_ONLINE_ADDRESS'); ?></th>
              <td><?php echo $this->item->online_address; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->page_count) && $this->item->page_count !== '' && $this->item->page_count !== '0'): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_PAGE_COUNT'); ?></th>
              <td><?php echo $this->item->page_count; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->page_range) && $this->item->page_range !== '' && $this->item->page_range !== '0'): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_PAGE_RANGE'); ?></th>
              <td><?php echo $this->item->page_range; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->periodical_id) && $this->item->periodical_id !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_PERIODICAL_ID'); ?></th>
              <td><?php echo $this->item->periodical_id; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->place_of_publication) && $this->item->place_of_publication !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_PLACE_OF_PUBLICATION'); ?></th>
              <td><?php echo $this->item->place_of_publication; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->pub_med_id) && $this->item->pub_med_id !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_PUB_MED_ID'); ?></th>
              <td><?php echo $this->item->pub_med_id; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->series_title_id) && $this->item->series_title_id !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_SERIES_TITLE_ID'); ?></th>
              <td><?php echo $this->item->series_title_id; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->eisbn) && $this->item->eisbn !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_EISBN'); ?></th>
              <td><?php echo $this->item->eisbn; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->volume) && $this->item->volume !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_VOLUME'); ?></th>
              <td><?php echo $this->item->volume; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->authors) && $this->item->authors !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_AUTHORS'); ?></th>
              <td><?php echo $this->item->authors; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->translators) && $this->item->translators !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_TRANSLATORS'); ?></th>
              <td><?php echo $this->item->translators; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->others_involved) && $this->item->others_involved !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_OTHERS_INVOLVED'); ?></th>
              <td><?php echo $this->item->others_involved; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->publishers) && $this->item->publishers !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_PUBLISHERS'); ?></th>
              <td><?php echo $this->item->publishers; ?></td>
          </tr>
      <?php endif ?>

      <?php if (isset($this->item->keywords) && $this->item->keywords !== ''): ?>
          <tr>
              <th><?php echo JText::_('COM_PUBDB_FORM_LBL_LITERATURE_KEYWORDS'); ?></th>
              <td><?php echo $this->item->keywords; ?></td>
          </tr>
      <?php endif ?>

    </table>
    <form action="<?php echo JRoute::_('index.php?option=com_pubdb&view=publicationlists'); ?>" method="post"
          name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
        <button class="btn btn-primary pull-right" type="submit"
                onclick="document.getElementById('task').value = 'publicationlists.export';this.form.submit()"/>
        <i class="icon-download icon-white"></i>
      <?php echo JText::_('COM_PUBDB_EXPORT'); ?></button>
      <?php echo JHTML::_('form.token'); ?>
        <input type="hidden" name="option" value="com_pubdb"/>
        <input type="hidden" name="task" id="task" value="importer.import"/>
        <input type="hidden" name="export_id" value="<?php echo $this->item->id; ?>">
        <input type="hidden" name="controller" value="publicationlists"/>
    </form>
</div>

