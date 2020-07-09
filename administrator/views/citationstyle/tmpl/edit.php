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
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;


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

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_pubdb/css/form.css');
?>

<style>
    .clonedArea {
        min-height: 100px;
        margin-bottom: 20px;
        width: 100%;
        border-color: grey;
        border-width: 1px;
        border-radius: 10px;
        border-style: dotted;
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    .elementArea {
        margin-bottom: 20px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }

    .specialCharacters {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }

    .authorArea {
        margin-bottom: 20px;
        display: none;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        margin-top: 50px;
    }

    .clonedAuthorArea {
        min-height: 100px;
        margin-bottom: 20px;
        max-width: 100%;
        display: none;
        flex-direction: row;
    }

    .block {
        border-width: 1px;
        border-radius: 5px;
        border-style: solid;
        border-color: black;
        margin-right: 10px;
        padding: 5px;
        cursor: grab;
    }

    .block:active, .clonedBlock:active {
        cursor: grabbing;
    }

    .clonedBlock {
        margin-right: 3px;
        font-size: 15px;
        cursor: grab;
    }

    li[class~="-3"] {
        background-color: yellowgreen;
    }

    .authors {
        border-color: yellowgreen;
        border-width: 2px;
        border-style: solid;
        border-radius: 5px;
        padding: 10px;
    }

    .containers {
        display: flex;
        flex-direction: row;
        align-items: center;
        flex-wrap: wrap;
        margin: 0 0 0 20px !important;
    }

    .specialContainer {
        display: flex;
        flex-direction: row;
        align-items: center;
        flex-wrap: wrap;
        margin: 0 0 0 0 !important;
    }

    .partAuthor1 {
        height: 100px;
        border-color: grey;
        border-width: 1px;
        border-radius: 10px;
        border-style: dotted;
        flex: 1;
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    .partAuthor2 {
        height: 100px;
        margin-left: 10px;
        margin-right: 10px;
        border-color: grey;
        border-width: 1px;
        border-radius: 10px;
        border-style: dotted;
        flex: 1;
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    .partAuthor3 {
        height: 100px;
        border-color: grey;
        border-width: 1px;
        border-radius: 10px;
        border-style: dotted;
        flex: 1;
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    .partAuthor4 {
        height: 100px;
        border-color: grey;
        margin-left: 10px;
        border-width: 1px;
        border-radius: 10px;
        border-style: dotted;
        flex: 0.2;
        display: flex;
        flex-direction: row;
        align-items: center;
    }
</style>

<?php
$document->addScript(Uri::root() . 'media/com_pubdb/js/jquery-ui.js');
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
</script>
<script type="text/javascript">
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

        //jQuery("#orderedlist_").sortable();

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
                drop(jQuery(this)[0].id.split("_")[1], ui, "clonedAuthor4", "clonedCharacter4", "orderedAuthorList4", "originalAuthor");
            },
        });
    });

    /**
     * Sorts jsonObject by first value and return sorted array
     *
     * @param jsonObject JSON object to be sorted by value
     * @return sorted array [[value, key], [value, key], ...]
     */
    function sortByValue(jsonObject) {
        let sortedArray = [];
        for (let jsonEntry in jsonObject)
            if (jsonObject.hasOwnProperty(jsonEntry))
                // push each JSON object entry in array by [value, key]
                sortedArray.push([jsonObject[jsonEntry], jsonEntry]);

        return sortedArray.sort((a, b) => a[0].toLowerCase().localeCompare(b[0].toLowerCase()));
    }

    /**
     * Creates all blocks for each tab from blockArray with class
     *
     * @param tabIds The ids of the tabs
     * @param blockArray The array of [String, id] to create a block from
     * @param className Class for block
     */
    function createBlocks(tabIds, blockArray, className) {
        blockArray.forEach(sortedArrayEntry => {
            Array.from(tabIds).forEach(ol => {
                const li = createLiElement("block", className, sortedArrayEntry[1])
                li.appendText(String.from(sortedArrayEntry[0]));
                ol.appendChild(li);
            });
        });
    }

    /**
     * Creates a li-DOM element with classes
     *
     * @param classes The class strings for the li element
     * @return lo The DOM-element with classes
     */
    function createLiElement(...classes) {
        const li = document.createElement("li");
        classes.forEach(string => li.addClass(string));
        return li;
    }

    /**
     * If a citation style is available, it loads it's blocks into the views.
     */
    function loadItems() {
        // Checks whether the citation style has blocks to display
        if (document.getElementById("jform_string").value !== "") {
            // Grabs citation style and maps default citation style (-1) to id 1
            citationStyle = JSON.parse(document.getElementById("jform_string").value);
            citationStyle[1] = citationStyle[-1];

            // For each reference type fill its tab
            reference_type_ids.forEach(id => loadReferenceTypeTab(id));
        }
    }

    /**
     * Loads blocks from reference type into a tab
     *
     * @param id Reference type id
     */
    function loadReferenceTypeTab(id) {

        hideAuthorArea(id);
        emptyAllDropZones(id);

        // is reference type in citation style defined
        if (citationStyle.hasOwnProperty(id)) {
            let referenceTypeInCitationStyle = citationStyle[id];

            // Contains author?
            if (referenceTypeInCitationStyle.includes(1)) {
                // splits blocks for author logic
                // blocks to author
                let indexFirstAuthor = referenceTypeInCitationStyle.indexOf(1);
                let blocksToAuthor = referenceTypeInCitationStyle.slice(0, indexFirstAuthor).concat([-3]);
                let blocksRemaining = referenceTypeInCitationStyle.slice(indexFirstAuthor + 1);

                // first author blocks + delimiter
                let indexMiddleAuthor = blocksRemaining.indexOf(2);
                let blocksFirstAuthor = blocksRemaining.slice(0, indexMiddleAuthor);
                let specialChar = blocksRemaining.slice(indexMiddleAuthor + 1, indexMiddleAuthor + 2);
                blocksRemaining = blocksRemaining.slice(indexMiddleAuthor + 2);

                // middle author blocks
                let indexLastAuthor = blocksRemaining.indexOf(3);
                let blocksMiddleAuthor = blocksRemaining.slice(0, indexLastAuthor);
                blocksRemaining = blocksRemaining.slice(indexLastAuthor + 1);

                // last author
                let indexRemainingBlocks = blocksRemaining.indexOf(4);
                let blocksLastAuthor = blocksRemaining.slice(0, indexRemainingBlocks);
                let blocksFollowingAuthor = blocksRemaining.slice(indexRemainingBlocks + 1);

                // non author blocks
                let blocksNonAuthor = blocksToAuthor.concat(blocksFollowingAuthor);

                showAuthorArea(id);

                // process non author blocks
                const blockListMain = document.getElementById("orderedList_" + id);
                blocksNonAuthor.forEach(blockNonAuthor =>
                    processNonAuthorBlocks(id, blockNonAuthor, blockListMain));

                // process first author blocks
                const blockListFirstAuthor = document.getElementById("orderedAuthorList1_" + id);
                blocksFirstAuthor.forEach(blockFirstAuthor =>
                    processAuthorBlocks(id, blockFirstAuthor, blockListFirstAuthor, "clonedAuthor1", "clonedCharacter1"));

                // process middle author blocks
                const blockListMiddleAuthor = document.getElementById("orderedAuthorList2_" + id);
                blocksMiddleAuthor.forEach(blockMiddleAuthor =>
                    processAuthorBlocks(id, blockMiddleAuthor, blockListMiddleAuthor, "clonedAuthor2", "clonedCharacter2"));

                // process last author blocks
                const blockListLastAuthor = document.getElementById("orderedAuthorList3_" + id);
                blocksLastAuthor.forEach(blockLastAuthor =>
                    processAuthorBlocks(id, blockLastAuthor, blockListLastAuthor, "clonedAuthor3", "clonedCharacter3"));

                // process special character blocks
                const blockListSpecialCharacter = document.getElementById("orderedAuthorList4_" + id);
                specialChar.forEach(blockSpecialCharacter =>
                    processSpecialCharacterBlocks(id, blockSpecialCharacter, blockListSpecialCharacter));

            } else {
                // process non author blocks
                const blockListMain = document.getElementById("orderedList_" + id);
                referenceTypeInCitationStyle.forEach(blockNonAuthor =>
                    processNonAuthorBlocks(id, blockNonAuthor, blockListMain));
            }
        }
    }

    /**
     * Hides the author area for a tab
     *
     * @param id The tab id where the area should be hidden
     */
    function hideAuthorArea(id) {
        document.getElementById("authorArea_" + id).style.display = "none";
        document.getElementById("clonedAuthorArea_" + id).style.display = "none";
    }

    /**
     * Shows the author area for a tab
     *
     * @param id The tab id where the area should be shown
     */
    function showAuthorArea(id) {
        document.getElementById("authorArea_" + id).style.display = "flex";
        document.getElementById("clonedAuthorArea_" + id).style.display = "flex";
    }

    /**
     * Removes all blocks from each drop zone for a tab
     *
     * @param id The tab id where the drop zones should be emptied
     */
    function emptyAllDropZones(id) {
        jQuery("#orderedList_" + id).empty();
        emptyAuthorDropZones(id);
    }

    /**
     * Removes all blocks from the author drop zone for a tab
     *
     * @param id The tab id where the author drop zone should be emptied
     */
    function emptyAuthorDropZones(id) {
        jQuery("#orderedAuthorList1_" + id).empty();
        jQuery("#orderedAuthorList2_" + id).empty();
        jQuery("#orderedAuthorList3_" + id).empty();
    }

    /**
     * Makes the li element draggable and adds a remove function
     *
     * @param id The tab id where the block is used
     * @param li The li-DOM element which should be draggable
     */
    function makeDraggable(id, li) {
        jQuery(li).draggable({
            //connectToSortable: "#orderedlist_",

            // remove block from list and clean up author drop zone
            revert: function (valid) {
                if (!valid) {
                    if (this.hasClass("-3")) {
                        emptyAuthorDropZones(id);
                        hideAuthorArea(id);
                        jQuery(".-3").addClass("original");
                    }
                    this.remove();
                    document.getElementById("jform_string").value = "";
                }
            },
        });
    }

    /**
     * Loads non author blocks from reference type into a tab
     *
     * @param id Reference type id
     * @param nonAuthorBlock The block to be process
     * @param blockList The ol element to add the block to
     */
    function processNonAuthorBlocks(id, nonAuthorBlock, blockList) {
        const li = createLiElement("clonedBlock", nonAuthorBlock);

        // db view field or special character block
        if (nonAuthorBlock in blocks) {
            li.addClass("cloned");
            li.appendText(blocks[nonAuthorBlock]);
        } else {
            li.addClass("clonedCharacter");
            li.appendText(specialBlocks[nonAuthorBlock]);
        }

        // make block draggable
        makeDraggable(id, li);

        // add block to list
        blockList.appendChild(li);
    }

    /**
     * Loads author blocks from reference type into a tab
     *
     * @param id Reference type id
     * @param blockAuthor The block to be process
     * @param blockListAuthor The ol element to add the block to
     * @param classNameAuthor The class to be added to the author block
     * @param classNameCharacter The class to be added to the character block
     */
    function processAuthorBlocks(id, blockAuthor, blockListAuthor, classNameAuthor, classNameCharacter) {
        const li = createLiElement("clonedBlock", blockAuthor);

        // author block or special character block
        if (blockAuthor in authorBlocks) {
            li.addClass(classNameAuthor);
            li.appendText(authorBlocks[blockAuthor]);
        } else {
            li.addClass(classNameCharacter);
            li.appendText(specialBlocks[blockAuthor]);
        }

        makeDraggable(id, li);
        blockListAuthor.appendChild(li);
    }

    /**
     * Loads special blocks from reference type into a tab
     *
     * @param id Reference type id
     * @param blockSpecialCharacter The block to be process
     * @param blockListSpecialCharacter The ol element to add the block to
     */
    function processSpecialCharacterBlocks(id, blockSpecialCharacter, blockListSpecialCharacter) {
        const li = createLiElement("clonedBlock", blockSpecialCharacter, "clonedCharacter4");
        li.appendText(specialBlocks[blockSpecialCharacter]);

        makeDraggable(id, li);
        blockListSpecialCharacter.appendChild(li);
    }

    /**
     * Gets the id of a block
     *
     * @param blockListEntry block to get id from
     * @return {number} id of block
     */
    function mapBlockToId(blockListEntry) {
        // Is block -> return block id
        for (const block in blocks)
            if (blocks.hasOwnProperty(block) && blockListEntry.hasClass(block))
                return parseInt(block);

        // Is special block -> return special block id
        for (const specialBlock in specialBlocks)
            if (specialBlocks.hasOwnProperty(specialBlock) && blockListEntry.hasClass(specialBlock))
                return parseInt(specialBlock)

        // Is author block -> return author block id
        for (const authorBlock in authorBlocks)
            if (authorBlocks.hasOwnProperty(authorBlock) && blockListEntry.hasClass(authorBlock))
                return parseInt(authorBlock)
    }

    /**
     * Gets the ids of a block list
     *
     * @param blockList blocks to get ids from
     * @return {[number]} ids of blocks
     */
    function mapBlocksToIds(blockList) {
        let tmp = [];
        Array.from(blockList.getElementsByTagName("li")).forEach(blockListEntry =>
            tmp.push(mapBlockToId(blockListEntry)));
        return tmp;
    }

    /**
     * On drop of block function
     * Adds dragged block to list
     *
     * @param id Id of the tab
     * @param ui Dragged block
     * @param clonedBlockType Block type class to add
     * @param clonedCharacter Character type class to add
     * @param listToAddTo ol list to add block to
     * @param originalBlockType Block type class to be replaced
     */
    function drop(id, ui, clonedBlockType, clonedCharacter, listToAddTo, originalBlockType) {
        document.getElementById("jform_string").value = "";
        // Is block draggable
        if (jQuery(ui.draggable).hasClass(originalBlockType) || jQuery(ui.draggable).hasClass("originalCharacter")) {
            //Clone block and modify classes
            let clonedBlock = ui.draggable.clone()[0];
            jQuery(clonedBlock).removeClass("block");
            jQuery(clonedBlock).addClass("clonedBlock");
            if (jQuery(ui.draggable).hasClass(originalBlockType)) {
                jQuery(clonedBlock).addClass(clonedBlockType);
                jQuery(clonedBlock).removeClass(originalBlockType);
            } else {
                jQuery(clonedBlock).addClass(clonedCharacter);
                jQuery(clonedBlock).removeClass("originalCharacter");
            }
            // Makes cloned block draggable and adds it to list
            makeDraggable(id, clonedBlock);
            document.getElementById(listToAddTo + "_" + id).append(clonedBlock);

            // Shows author area if needed
            if (jQuery(ui.draggable).hasClass("-3")) {
                jQuery(ui.draggable).removeClass("original");
                showAuthorArea(id);
            }
        }
    }

    /**
     * Generate savable json from blocks in tabs
     * Writes json to actual Joomla! save field
     */
    function submitClicked() {
        const submitField = document.getElementById("jform_string");

        if (submitField.value === "") {
            let dict = {};
            // each reference type
            reference_type_ids.forEach(id => {
                arrayString = mapBlocksToIds(document.getElementById("orderedList_" + id));

                let dictArray;
                // replaced the author placeholder, if available
                if (arrayString.includes(-3)) {
                    arrayStringAuthor1 = mapBlocksToIds(document.getElementById("partAuthor1_" + id));
                    arrayStringAuthor2 = mapBlocksToIds(document.getElementById("partAuthor2_" + id));
                    arrayStringAuthor3 = mapBlocksToIds(document.getElementById("partAuthor3_" + id));
                    arrayStringAuthor4 = mapBlocksToIds(document.getElementById("partAuthor4_" + id));

                    let indexAuthorPlaceholder = arrayString.indexOf(-3);
                    let blocksBeforeAuthor = arrayString.slice(0, indexAuthorPlaceholder);
                    let blocksAfterAuthor = arrayString.slice(indexAuthorPlaceholder + 1);
                    let blocksAuthor = [1].concat(arrayStringAuthor1).concat([2]).concat(arrayStringAuthor4).concat(arrayStringAuthor2).concat([3]).concat(arrayStringAuthor3).concat([4]);

                    dictArray = blocksBeforeAuthor.concat(blocksAuthor).concat(blocksAfterAuthor);
                } else {
                    dictArray = arrayString;
                }

                // maps default to -1
                if (dictArray.length > 0) {
                    if (id === 1) id = -1;
                    dict[id] = dictArray;
                }
            });

            submitField.value = JSON.stringify(dict);
            submitField.innerText = JSON.stringify(dict);
        }
    }

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
                      <ol style="list-style-type: none;" class="containers"
                          id="orderedList_<?php echo $type['id']; ?>"></ol>
                  </div>
                  <div class="authors">
                      <div class="authorArea" id="authorArea_<?php echo $type['id']; ?>">
                          <ol style="list-style-type: none;" class="containers fixAuthorList"></ol>
                      </div>
                      <div class="clonedAuthorArea" id="clonedAuthorArea_<?php echo $type['id']; ?>">
                        <?php echo JText::_('COM_PUBDB_TAB_LITERATURE_AUTHOR_FIRST'); ?>
                          <div class="partAuthor1" id="partAuthor1_<?php echo $type['id']; ?>">
                              <ol style="list-style-type: none;" class="containers"
                                  id="orderedAuthorList1_<?php echo $type['id']; ?>"></ol>
                          </div>
                        <?php echo JText::_('COM_PUBDB_TAB_LITERATURE_AUTHOR_SECOND'); ?>*
                          <div class="partAuthor2" id="partAuthor2_<?php echo $type['id']; ?>">
                              <ol style="list-style-type: none;" class="containers"
                                  id="orderedAuthorList2_<?php echo $type['id']; ?>"></ol>
                          </div>
                        <?php echo JText::_('COM_PUBDB_TAB_LITERATURE_AUTHOR_THIRD'); ?>
                          <div class="partAuthor3" id="partAuthor3_<?php echo $type['id']; ?>">
                              <ol style="list-style-type: none;" class="containers"
                                  id="orderedAuthorList3_<?php echo $type['id']; ?>"></ol>
                          </div>
                        <?php echo JText::_('COM_PUBDB_TAB_LITERATURE_AUTHOR_SEPARATOR'); ?>*
                          <div class="partAuthor4" id="partAuthor4_<?php echo $type['id']; ?>">
                              <ol style="list-style-type: none;" class="containers"
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



