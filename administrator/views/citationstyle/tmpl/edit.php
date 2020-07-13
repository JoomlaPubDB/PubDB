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


HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');
HTMLHelper::_('behavior.keepalive');

$db = JFactory::getDbo();
$query = $db->getQuery(true);

$query
  ->select($db->quoteName(array('id', 'name')))
  ->from($db->quoteName('#__pubdb_blocks'))
  ->where($db->quoteName('category') . '= 1');
$db->setQuery($query);
$blocks = $db->loadAssocList('id', 'name');

$query = $db->getQuery(true);
$query
  ->select($db->quoteName(array('id', 'name')))
  ->from($db->quoteName('#__pubdb_blocks'))
  ->where($db->quoteName('category') . '= 2');
$db->setQuery($query);
$specialBlocks = $db->loadAssocList('id', 'name');

$query = $db->getQuery(true);
$query
  ->select($db->quoteName(array('id', 'name')))
  ->from($db->quoteName('#__pubdb_blocks'))
  ->where($db->quoteName('category') . '= 3')
  ->where($db->quoteName('id') . '> 4');
$db->setQuery($query);
$authorBlocks = $db->loadAssocList('id', 'name');

$query = $db->getQuery(true);
$query
  ->select($db->quoteName(array('id', 'name', 'lable')))
  ->from($db->quoteName('#__pubdb_reference_types'))
  ->where('state = 1');
$db->setQuery($query);
$reference_types = $db->loadAssocList('id');
$reference_type_ids = array_keys($reference_types);

// Add labels
$blocks = array_map(function ($block) {
  return JText::sprintf('COM_PUBDB_CITATIONSTLYE_' . $block);
}, $blocks);
$specialBlocks = array_map(function ($block) {
  return JText::sprintf('COM_PUBDB_CITATIONSTLYE_' . $block);
}, $specialBlocks);
$authorBlocks = array_map(function ($block) {
  return JText::sprintf('COM_PUBDB_CITATIONSTLYE_' . $block);
}, $authorBlocks);


// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_pubdb/css/form.css');
$document->addStyleSheet(Uri::root() . 'administrator/components/com_pubdb/assets/css/citation_style.css');
$document->addScript(Uri::root() . 'media/com_pubdb/js/jquery-ui.js');
$document->addScript(Uri::root() . 'administrator/components/com_pubdb/assets/js/citation_style.js');
?>

<script type="text/javascript">
    js = jQuery.noConflict();

    Joomla.submitbutton = function (task) {
        submitClicked();
        if (task === 'citationstyle.cancel') {
            Joomla.submitform(task, document.getElementById('citationstyle-form'));
        } else {
            if (task !== 'citationstyle.cancel' && document.formvalidator.isValid(document.id('citationstyle-form'))) {
                Joomla.submitform(task, document.getElementById('citationstyle-form'));
            }
        }
    }

    let blocks;
    let authorBlocks;
    let specialBlocks;
    let arrayString = [];
    let arrayStringAuthor1 = [];
    let arrayStringAuthor2 = [];
    let arrayStringAuthor3 = [];
    let arrayStringAuthor4 = [];
    let citationStyle = {};
    let reference_type_ids = {};

    /**
     * On document loaded create blocks and load citation style
     */
    jQuery(document).ready(function () {
        // Get blocks from db
        blocks = JSON.parse('<?php echo json_encode($blocks) ?>');
        blocks["-3"] = "Author";
        authorBlocks = JSON.parse('<?php echo json_encode($authorBlocks) ?>');
        specialBlocks = JSON.parse('<?php echo json_encode($specialBlocks) ?>');
        reference_type_ids = JSON.parse('<?php echo json_encode($reference_type_ids); ?>');

        // Create all blocks
        createBlocks(document.getElementsByClassName("fixlist"), sortByValue(blocks), "original");
        createBlocks(document.getElementsByClassName("fixAuthorList"), sortByValue(authorBlocks), "originalAuthor");
        createBlocks(document.getElementsByClassName("fixSpecialList"), sortByValue(specialBlocks), "originalCharacter");

        // Load block into drop area for citation style
        loadItems();

        //Makes all blocks draggable
        jQuery(".block").draggable({helper: "clone", revert: "invalid"});

        jQuery(".clonedArea").droppable({
            // Defines which blocks can be dragged here
            accept: ".original, .cloned, .originalCharacter, .clonedCharacter",
            // On drop definition
            drop: function (ev, ui) {
                drop(jQuery(this)[0].id, ui, "cloned", "clonedCharacter", "orderedList", "original");
            },
        });

        jQuery(".partAuthor1").droppable({
            accept: ".originalAuthor, .clonedAuthor1, .clonedCharacter1, .originalCharacter",
            drop: function (ev, ui) {
                drop(jQuery(this)[0].id.split("_")[1], ui, "clonedAuthor1", "clonedCharacter1", "orderedAuthorList1", "originalAuthor");
            },
        });

        jQuery(".partAuthor2").droppable({
            accept: ".originalAuthor, .clonedAuthor2, .clonedCharacter2, .originalCharacter",
            drop: function (ev, ui) {
                drop(jQuery(this)[0].id.split("_")[1], ui, "clonedAuthor2", "clonedCharacter2", "orderedAuthorList2", "originalAuthor");
            },
        });

        jQuery(".partAuthor3").droppable({
            accept: ".originalAuthor, .clonedAuthor3, .clonedCharacter3, .originalCharacter",
            drop: function (ev, ui) {
                drop(jQuery(this)[0].id.split("_")[1], ui, "clonedAuthor3", "clonedCharacter3", "orderedAuthorList3", "originalAuthor");
            },
        });

        jQuery(".partAuthor4").droppable({
            accept: ".clonedCharacter4, .originalCharacter",
            drop: function (ev, ui) {
                if ($(this).children[0].children.length < 1)
                    drop(jQuery(this)[0].id.split("_")[1], ui, "clonedAuthor4", "clonedCharacter4", "orderedAuthorList4", "originalAuthor");
            },
        });

        jQuery(".orderedList").sortable();
    });


</script>


<form
        action="<?php echo JRoute::_('index.php?option=com_pubdb&layout=edit&id=' . (int)$this->item->id); ?>"
        method="post" enctype="multipart/form-data" name="adminForm" id="citationstyle-form"
        class="form-validate form-horizontal">


    <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>"/>
    <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>"/>
    <input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>"/>
    <input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>"/>
    <input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>"/>
  <?php echo $this->form->renderField('created_by'); ?>
  <?php echo $this->form->renderField('modified_by'); ?>
  <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'citationstyle')); ?>
  <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'citationstyle', JText::_('COM_PUBDB_TAB_CITATIONSTYLE', true)); ?>
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_PUBDB_FIELDSET_CITATIONSTYLE'); ?></legend>
              <?php echo $this->form->renderField('name'); ?>
              <?php echo $this->form->renderField('string'); ?>
              <?php if ($this->state->params->get('save_history', 1)) : ?>
                  <div class="control-group">
                      <div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
                      <div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
                  </div>
              <?php endif; ?>
        </div>
    </div>
  <?php echo JHtml::_('bootstrap.endTab'); ?>
  <?php foreach ($reference_types as $type): ?>
    <?php echo JHtml::_('bootstrap.addTab', 'myTab', $type['name'], JText::_("COM_PUBDB_REF_TYPE_" . $type['name'])); ?>
      <legend><?php echo JText::_("COM_PUBDB_REF_TYPE_" . $type['name']); ?></legend>
      <p><?php echo JText::_($type['lable']) ?></p>
      <input type="hidden" name="type_<?php echo $type['id']; ?>" value=""/>
      <div style="display: flex; flex-direction: column;">
          <div style="display: flex; flex-direction: row;">
              <div style="display: flex; flex-direction: column; flex:0.6">
                  <div class="elementArea">
                      <ol style="list-style-type: none;" class="containers fixlist"></ol>
                  </div>
                  <div class="clonedArea" id="<?php echo $type['id']; ?>">
                      <ol style="list-style-type: none;" class="containers orderedList"
                          id="orderedList_<?php echo $type['id']; ?>"></ol>
                  </div>
                  <div class="authors" id="authors_<?php echo $type['id']; ?>">
                      <div class="authorArea" id="authorArea_<?php echo $type['id']; ?>">
                          <ol style="list-style-type: none;" class="containers fixAuthorList"></ol>
                      </div>
                      <div class="clonedAuthorArea" id="clonedAuthorArea_<?php echo $type['id']; ?>">
                        <?php echo JText::_('COM_PUBDB_TAB_LITERATURE_AUTHOR_FIRST'); ?>
                          <div class="partAuthor1" id="partAuthor1_<?php echo $type['id']; ?>">
                              <ol style="list-style-type: none;" class="containers orderedList"
                                  id="orderedAuthorList1_<?php echo $type['id']; ?>"></ol>
                          </div>
                        <?php echo JText::_('COM_PUBDB_TAB_LITERATURE_AUTHOR_SECOND'); ?>*
                          <div class="partAuthor2" id="partAuthor2_<?php echo $type['id']; ?>">
                              <ol style="list-style-type: none;" class="containers orderedList"
                                  id="orderedAuthorList2_<?php echo $type['id']; ?>"></ol>
                          </div>
                        <?php echo JText::_('COM_PUBDB_TAB_LITERATURE_AUTHOR_THIRD'); ?>
                          <div class="partAuthor3" id="partAuthor3_<?php echo $type['id']; ?>">
                              <ol style="list-style-type: none;" class="containers orderedList"
                                  id="orderedAuthorList3_<?php echo $type['id']; ?>"></ol>
                          </div>
                        <?php echo JText::_('COM_PUBDB_TAB_LITERATURE_AUTHOR_SEPARATOR'); ?>*
                          <div class="partAuthor4" id="partAuthor4_<?php echo $type['id']; ?>">
                              <ol style="list-style-type: none;" class="containers orderedList"
                                  id="orderedAuthorList4_<?php echo $type['id']; ?>"></ol>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="specialCharacters" style="flex:0.4">
                  <ol style="list-style-type: none;" class="specialContainer fixSpecialList"></ol>
              </div>
          </div>
      </div>
    <?php echo JHtml::_('bootstrap.endTab'); ?>
  <?php endforeach; ?>


  <?php echo JHtml::_('bootstrap.endTabSet'); ?>

    <input type="hidden" name="task" value=""/>
  <?php echo JHtml::_('form.token'); ?>

</form>



