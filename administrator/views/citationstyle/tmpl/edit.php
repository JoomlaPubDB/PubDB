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
    const reference_type_ids = <?php echo json_encode($reference_type_ids); ?>;


    //Excecuted when the page is loaded. If a citationstlye is available, it loads it's blocks into the views.
    function loadItems() {

        if (document.getElementById("jform_string").value !== "") {
            let fieldText = JSON.parse(document.getElementById("jform_string").value);
            fieldText[1] = fieldText[-1];

            reference_type_ids.forEach(id => {
                document.getElementById("authorArea_" + id).style.display = "none";
                document.getElementById("clonedAuthorArea_" + id).style.display = "none";
                jQuery("#orderedList_" + id).empty();
                jQuery("#orderedAuthorList1_" + id).empty();
                jQuery("#orderedAuthorList2_" + id).empty();
                jQuery("#orderedAuthorList3_" + id).empty();

                if (fieldText.hasOwnProperty(id)) {
                    let newArray = fieldText[id];

                    // Author logic
                    if (newArray.includes(1)) {
                        //Split Array until author starts (1) and add "-3" for author field
                        let firstIndexToSplit = newArray.indexOf(1);
                        let first = newArray.slice(0, firstIndexToSplit).concat([-3]);
                        let rest = newArray.slice(firstIndexToSplit + 1);

                        let secondIndexToSplit = rest.indexOf(2);
                        let second = rest.slice(0, secondIndexToSplit);
                        let specialChar = rest.slice(secondIndexToSplit + 1, secondIndexToSplit + 2);
                        rest = rest.slice(secondIndexToSplit + 2);

                        let thirdIndexToSplit = rest.indexOf(3);
                        let third = rest.slice(0, thirdIndexToSplit);
                        rest = rest.slice(thirdIndexToSplit + 1);

                        let fourthIndexToSplit = rest.indexOf(4);
                        let fourth = rest.slice(0, fourthIndexToSplit);
                        let last = rest.slice(fourthIndexToSplit + 1);

                        let newResultArray = first.concat(last);

                        document.getElementById("authorArea_" + id).style.display = "flex";
                        document.getElementById("clonedAuthorArea_" + id).style.display = "flex";

                        newResultArray.forEach(function (item) {
                            let li = document.createElement("li");
                            jQuery(li).addClass("" + item);
                            jQuery(li).addClass("clonedBlock");
                            if (item in blocks) {
                                let content = document.createTextNode(blocks[item]);
                                jQuery(li).addClass("cloned");
                                li.appendChild(content);
                            } else {
                                let content = document.createTextNode(specialBlocks[item]);
                                jQuery(li).addClass("clonedCharacter");
                                li.appendChild(content);
                            }
                            jQuery(li).draggable({
                                //connectToSortable: "#orderedlist_",
                                revert: function (valid) {
                                    if (!valid) {
                                        if (jQuery(this).hasClass("-3")) {
                                            jQuery("#orderedAuthorList1_" + id).empty();
                                            jQuery("#orderedAuthorList2_" + id).empty();
                                            jQuery("#orderedAuthorList3_" + id).empty();
                                            document.getElementById("authorArea_" + id).style.display =
                                                "none";
                                            document.getElementById("clonedAuthorArea_" + id).style.display =
                                                "none";
                                            jQuery(".-3").addClass("original");
                                        }
                                        jQuery(this).remove();
                                        document.getElementById("jform_string").value = "";
                                    }
                                },
                            });
                            let ol = document.getElementById("orderedList_" + id);
                            ol.appendChild(li);
                            if (item === -3) {
                                jQuery(".-3").removeClass("original");
                            }
                        });

                        second.forEach(function (item) {
                            let li = document.createElement("li");
                            jQuery(li).addClass("" + item);
                            jQuery(li).addClass("clonedBlock");
                            if (item in authorBlocks) {
                                let content = document.createTextNode(authorBlocks[item]);
                                jQuery(li).addClass("clonedAuthor1");
                                li.appendChild(content);
                            } else {
                                let content = document.createTextNode(specialBlocks[item]);
                                jQuery(li).addClass("clonedCharacter1");
                                li.appendChild(content);
                            }
                            jQuery(li).draggable({
                                //connectToSortable: "#orderedAuthorList",
                                revert: function (valid) {
                                    if (!valid) {
                                        jQuery(this).remove();
                                        document.getElementById("jform_string").value = "";
                                    }
                                },
                            });
                            let ol = document.getElementById("orderedAuthorList1_" + id);
                            ol.appendChild(li);
                        });

                        third.forEach(function (item) {
                            let li = document.createElement("li");
                            jQuery(li).addClass("" + item);
                            jQuery(li).addClass("clonedBlock");
                            if (item in authorBlocks) {
                                let content = document.createTextNode(authorBlocks[item]);
                                jQuery(li).addClass("clonedAuthor2");
                                li.appendChild(content);
                            } else {
                                let content = document.createTextNode(specialBlocks[item]);
                                jQuery(li).addClass("clonedCharacter2");
                                li.appendChild(content);
                            }
                            jQuery(li).draggable({
                                //connectToSortable: "#orderedAuthorList",
                                revert: function (valid) {
                                    if (!valid) {
                                        jQuery(this).remove();
                                        document.getElementById("jform_string").value = "";
                                    }
                                },
                            });
                            let ol = document.getElementById("orderedAuthorList2_" + id);
                            ol.appendChild(li);
                        });

                        fourth.forEach(function (item) {
                            let li = document.createElement("li");
                            jQuery(li).addClass("" + item);
                            jQuery(li).addClass("clonedBlock");
                            if (item in authorBlocks) {
                                let content = document.createTextNode(authorBlocks[item]);
                                jQuery(li).addClass("clonedAuthor3");
                                li.appendChild(content);
                            } else {
                                let content = document.createTextNode(specialBlocks[item]);
                                jQuery(li).addClass("clonedCharacter3");
                                li.appendChild(content);
                            }
                            jQuery(li).draggable({
                                //connectToSortable: "#orderedAuthorList",
                                revert: function (valid) {
                                    if (!valid) {
                                        jQuery(this).remove();
                                        document.getElementById("jform_string").value = "";
                                    }
                                },
                            });
                            let ol = document.getElementById("orderedAuthorList3_" + id);
                            ol.appendChild(li);
                        });

                        specialChar.forEach(function (item) {
                            let li = document.createElement("li");
                            jQuery(li).addClass("" + item);
                            jQuery(li).addClass("clonedBlock");
                            let content = document.createTextNode(specialBlocks[item]);
                            jQuery(li).addClass("clonedCharacter4");
                            li.appendChild(content);
                            jQuery(li).draggable({
                                //connectToSortable: "#orderedAuthorList",
                                revert: function (valid) {
                                    if (!valid) {
                                        jQuery(this).remove();
                                        document.getElementById("jform_string").value = "";
                                    }
                                },
                            });
                            let ol = document.getElementById("orderedAuthorList4_" + id);
                            ol.appendChild(li);
                        });

                    } else {
                        newArray.forEach(function (item) {
                            let li = document.createElement("li");
                            jQuery(li).addClass("" + item);
                            jQuery(li).addClass("clonedBlock");
                            if (item in blocks) {
                                let content = document.createTextNode(blocks[item]);
                                jQuery(li).addClass("cloned");
                                li.appendChild(content);
                            } else {
                                let content = document.createTextNode(specialBlocks[item]);
                                jQuery(li).addClass("clonedCharacter");
                                li.appendChild(content);
                            }
                            jQuery(li).draggable({
                                //connectToSortable: "#orderedlist_",
                                revert: function (valid) {
                                    if (!valid) {
                                        jQuery(this).remove();
                                        document.getElementById("jform_string").value = "";
                                    }
                                },
                            });
                            let ol = document.getElementById("orderedList_" + id);
                            ol.appendChild(li);
                        });
                    }
                }
            });
        }
    }


    function sortByValue(jsObj) {
        let sortedArray = [];
        for (let i in jsObj) sortedArray.push([jsObj[i], i]); // Push each JSON Object entry in array by [value, key]
        return sortedArray.sort(function (a, b) {return a[0].toLowerCase().localeCompare(b[0].toLowerCase());});
    }


    function submitClicked() {
        if (document.getElementById("jform_string").value === "") {
            let dict = {};
            reference_type_ids.forEach(id => {
                arrayString = [];
                let ol = document.getElementById("orderedList_" + id);
                let items = ol.getElementsByTagName("li");
                for (let i = 0; i < items.length; ++i) {
                    for (let y in blocks) {
                        if (jQuery(items[i]).hasClass(y)) {
                            arrayString.push(parseInt(y));
                        }
                    }
                    for (let h in specialBlocks) {
                        if (jQuery(items[i]).hasClass(h)) {
                            arrayString.push(parseInt(h));
                        }
                    }
                }
                arrayStringAuthor1 = [];
                let olAuthor = document.getElementById("partAuthor1_" + id);
                let authorItems = olAuthor.getElementsByTagName("li");
                for (let t = 0; t < authorItems.length; ++t) {
                    for (let z in authorBlocks) {
                        if (jQuery(authorItems[t]).hasClass(z)) {
                            arrayStringAuthor1.push(parseInt(z));
                        }
                    }
                    for (let f in specialBlocks) {
                        if (jQuery(authorItems[t]).hasClass(f)) {
                            arrayStringAuthor1.push(parseInt(f));
                        }
                    }
                }
                arrayStringAuthor2 = [];
                olAuthor = document.getElementById("partAuthor2_" + id);
                authorItems = olAuthor.getElementsByTagName("li");
                for (let t = 0; t < authorItems.length; ++t) {
                    for (let z in authorBlocks) {
                        if (jQuery(authorItems[t]).hasClass(z)) {
                            arrayStringAuthor2.push(parseInt(z));
                        }
                    }
                    for (let f in specialBlocks) {
                        if (jQuery(authorItems[t]).hasClass(f)) {
                            arrayStringAuthor2.push(parseInt(f));
                        }
                    }
                }
                arrayStringAuthor3 = [];
                olAuthor = document.getElementById("partAuthor3_" + id);
                authorItems = olAuthor.getElementsByTagName("li");
                for (let t = 0; t < authorItems.length; ++t) {
                    for (let z in authorBlocks) {
                        if (jQuery(authorItems[t]).hasClass(z)) {
                            arrayStringAuthor3.push(parseInt(z));
                        }
                    }
                    for (let f in specialBlocks) {
                        if (jQuery(authorItems[t]).hasClass(f)) {
                            arrayStringAuthor3.push(parseInt(f));
                        }
                    }
                }
                arrayStringAuthor4 = [];
                olAuthor = document.getElementById("partAuthor4_" + id);
                authorItems = olAuthor.getElementsByTagName("li");
                for (let t = 0; t < authorItems.length; ++t) {
                    for (let f in specialBlocks) {
                        if (jQuery(authorItems[t]).hasClass(f)) {
                            arrayStringAuthor4.push(parseInt(f));
                        }
                    }
                }

                let dictArray;
                if (arrayString.includes(-3)) {
                    let indexToSplit = arrayString.indexOf(-3);
                    let first = arrayString.slice(0, indexToSplit);
                    let last = arrayString.slice(indexToSplit + 1);
                    let mid = [1].concat(arrayStringAuthor1).concat([2]).concat(arrayStringAuthor4).concat(arrayStringAuthor2).concat([3]).concat(arrayStringAuthor3).concat([4]);
                    //let txt = '{"-1:"[' + resultArray.toString() + ']}';
                    dictArray = first.concat(mid).concat(last);
                } else {
                    //let txt = '{"-1:"[' + arrayString.toString() + ']}';
                    dictArray = arrayString;
                }
                if (dictArray.length > 0) {
                    if (id === 1) id = -1;
                    dict[id] = dictArray;
                }
            });

            let textField = document.getElementById("jform_string");
            textField.value = JSON.stringify(dict);
            textField.innerText = JSON.stringify(dict);
        }
    }

    jQuery(document).ready(function () {

        //jQuery("#orderedlist_").sortable();

        blocks = JSON.parse('<?php echo json_encode($blocks) ?>');
        blocks["-3"] = "Author";

        authorBlocks = JSON.parse('<?php echo json_encode($authorBlocks) ?>');

        specialBlocks = JSON.parse('<?php echo json_encode($specialBlocks) ?>');


        let ols = document.getElementsByClassName("fixlist");
        let block_arr = sortByValue(blocks);
        for (let i = 0; i < block_arr.length; i++) {
            Array.from(ols).forEach(ol => {
                let li = document.createElement("li");
                li.className = "block original " + block_arr[i][1];
                li.innerText = String.from(block_arr[i][0]);
                ol.appendChild(li);
            });
        }

        ols = document.getElementsByClassName("fixAuthorList");
        block_arr = sortByValue(authorBlocks);
        for (let i = 0; i < block_arr.length; i++) {
            Array.from(ols).forEach(ol => {
                let li = document.createElement("li");
                li.className = "block originalAuthor " + block_arr[i][1];
                li.innerText = String.from(block_arr[i][0]);
                ol.appendChild(li);
            });
        }

        ols = document.getElementsByClassName("fixSpecialList");
        block_arr = sortByValue(specialBlocks);
        for (let i = 0; i < block_arr.length; i++) {
            Array.from(ols).forEach(ol => {
                let li = document.createElement("li");
                li.className = "block originalCharacter " + block_arr[i][1];
                li.innerText = String.from(block_arr[i][0]);
                ol.appendChild(li);
            });
        }

        loadItems();

        jQuery(".block").draggable({helper: "clone", revert: "invalid"});

        jQuery(".clonedArea").droppable({
            accept: ".original, .cloned, .originalCharacter, .clonedCharacter",
            drop: function (ev, ui) {
                if (jQuery(ui.draggable).hasClass("original") || (jQuery(ui.draggable).hasClass("originalCharacter"))) {
                    document.getElementById("jform_string").value = "";
                    let id = jQuery(this)[0].id;
                    let element = ui.draggable.clone();
                    jQuery(element).addClass("clonedBlock");
                    jQuery(element).removeClass("block");
                    if (jQuery(ui.draggable).hasClass("original")) {
                        jQuery(element).addClass("cloned");
                        jQuery(element).removeClass("original");
                    } else {
                        jQuery(element).addClass("clonedCharacter");
                        jQuery(element).removeClass("originalCharacter");
                    }
                    jQuery(element).draggable({
                        connectToSortable: "#orderedList_" + id,
                        revert: function (valid) {
                            if (!valid) {
                                if (jQuery(this).hasClass("-3")) {
                                    jQuery("#orderedAuthorList1_" + id).empty();
                                    jQuery("#orderedAuthorList2_" + id).empty();
                                    jQuery("#orderedAuthorList3_" + id).empty();
                                    document.getElementById("authorArea_" + id).style.display = "none";
                                    document.getElementById("clonedAuthorArea_" + id).style.display = "none";
                                    jQuery(".-3").addClass("original");
                                }
                                jQuery(this).remove();
                                document.getElementById("jform_string").value = "";
                            }
                        },
                    });
                    jQuery("#orderedList_" + id).append(element);
                    if (jQuery(ui.draggable).hasClass("-3")) {
                        jQuery(".-3").removeClass("original");
                        document.getElementById("authorArea_" + id).style.display = "flex";
                        document.getElementById("clonedAuthorArea_" + id).style.display =
                            "flex";
                    }
                }
            },
        });

        jQuery(".partAuthor1").droppable({
            accept: ".originalAuthor, .clonedAuthor1, .clonedCharacter1, .originalCharacter",
            drop: function (ev, ui) {
                if (jQuery(ui.draggable).hasClass("originalAuthor") || (jQuery(ui.draggable).hasClass("originalCharacter"))) {
                    document.getElementById("jform_string").value = "";
                    let id = jQuery(this)[0].id.split("_")[1];
                    let element = ui.draggable.clone();
                    jQuery(element).removeClass("block");
                    jQuery(element).addClass("clonedBlock");
                    if (jQuery(ui.draggable).hasClass("originalAuthor")) {
                        jQuery(element).addClass("clonedAuthor1");
                        jQuery(element).removeClass("originalAuthor");
                    } else {
                        jQuery(element).addClass("clonedCharacter1");
                        jQuery(element).removeClass("originalCharacter");
                    }
                    jQuery(element).draggable({
                        //connectToSortable: "#orderedlist_",
                        revert: function (valid) {
                            if (!valid) {
                                jQuery(this).remove();
                                document.getElementById("jform_string").value = "";
                            }
                        },
                    });
                    jQuery("#orderedAuthorList1_" + id).append(element);
                }
            },
        });


        jQuery(".partAuthor2").droppable({
            accept: ".originalAuthor, .clonedAuthor2, .clonedCharacter2, .originalCharacter",
            drop: function (ev, ui) {
                if (jQuery(ui.draggable).hasClass("originalAuthor") || (jQuery(ui.draggable).hasClass("originalCharacter"))) {
                    document.getElementById("jform_string").value = "";
                    let id = jQuery(this)[0].id.split("_")[1];
                    let element = ui.draggable.clone();
                    jQuery(element).removeClass("block");
                    jQuery(element).addClass("clonedBlock");
                    if (jQuery(ui.draggable).hasClass("originalAuthor")) {
                        jQuery(element).addClass("clonedAuthor2");
                        jQuery(element).removeClass("originalAuthor");
                    } else {
                        jQuery(element).addClass("clonedCharacter2");
                        jQuery(element).removeClass("originalCharacter");
                    }
                    jQuery(element).draggable({
                        //connectToSortable: "#orderedlist_",
                        revert: function (valid) {
                            if (!valid) {
                                jQuery(this).remove();
                                document.getElementById("jform_string").value = "";
                            }
                        },
                    });
                    jQuery("#orderedAuthorList2_" + id).append(element);
                }
            },
        });


        jQuery(".partAuthor3").droppable({
            accept: ".originalAuthor, .clonedAuthor3, .clonedCharacter3, .originalCharacter",
            drop: function (ev, ui) {
                if (jQuery(ui.draggable).hasClass("originalAuthor") || (jQuery(ui.draggable).hasClass("originalCharacter"))) {
                    document.getElementById("jform_string").value = "";
                    let id = jQuery(this)[0].id.split("_")[1];
                    let element = ui.draggable.clone();
                    jQuery(element).removeClass("block");
                    jQuery(element).addClass("clonedBlock");
                    if (jQuery(ui.draggable).hasClass("originalAuthor")) {
                        jQuery(element).addClass("clonedAuthor3");
                        jQuery(element).removeClass("originalAuthor");
                    } else {
                        jQuery(element).addClass("clonedCharacter3");
                        jQuery(element).removeClass("originalCharacter");
                    }
                    jQuery(element).draggable({
                        //connectToSortable: "#orderedlist_",
                        revert: function (valid) {
                            if (!valid) {
                                jQuery(this).remove();
                                document.getElementById("jform_string").value = "";
                            }
                        },
                    });
                    jQuery("#orderedAuthorList3_" + id).append(element);
                }
            },
        });

        jQuery(".partAuthor4").droppable({
            accept: ".clonedCharacter4, .originalCharacter",
            drop: function (ev, ui) {
                if (jQuery(ui.draggable).hasClass("originalCharacter")) {
                    document.getElementById("jform_string").value = "";
                    let id = jQuery(this)[0].id.split("_")[1];
                    let element = ui.draggable.clone();
                    jQuery(element).removeClass("block");
                    jQuery(element).addClass("clonedBlock");
                    jQuery(element).addClass("clonedCharacter4");
                    jQuery(element).removeClass("originalCharacter");
                    jQuery(element).draggable({
                        //connectToSortable: "#orderedlist_",
                        revert: function (valid) {
                            if (!valid) {
                                jQuery(this).remove();
                                document.getElementById("jform_string").value = "";
                            }
                        },
                    });
                    jQuery("#orderedAuthorList4_" + id).append(element);
                }
            },
        });
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
    <?php echo JHtml::_('bootstrap.addTab', 'myTab', $type['name'], JText::_($type['name'])); ?>
      <legend><?php echo JText::_($type['name']); ?></legend>
      <p><?php echo JText::_($type['lable']) ?></p>
      <br>
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



