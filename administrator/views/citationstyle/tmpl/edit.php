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
$blocks=$db->loadAssocList('id', 'name');

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
  /*->where($db->quoteName('id') . '> 4')*/;
$db->setQuery($query);
$authorBlocks=$db->loadAssocList('id', 'name');

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
    }
    .clonedBlock {
      margin-right: 3px;
      font-size:20px;
    }
    .containers {
      display: flex;
      flex-direction: row;
      align-items: center;
      flex-wrap: wrap;
      margin: 0px 0px 0px 20px !important;
    } 
    .specialContainer {
      display: flex;
      flex-direction: row;
      align-items: center;
      flex-wrap: wrap;
      margin: 0px 0px 0px 0px !important;
    }
    .partAuthor1 {
      height:100px;
      width: 100px;
      border-color: "grey";
      border-width: 1px;
      border-radius: 10px;
      border-style: dotted;
      flex-grow:1;
      display: flex;
      flex-direction: row;
      align-items: center;
    }
    .partAuthor2 {
      margin-right: 20px;
      margin-left: 20px;
      height:100px;
      width: 100px;
      border-color: "grey";
      border-width: 1px;
      border-radius: 10px;
      border-style: dotted;
      flex-grow:1;
      display: flex;
      flex-direction: row;
      align-items: center;
    }
    .partAuthor3 {
      height:100px;
      width: 100px;
      border-color: "grey";
      border-width: 1px;
      border-radius: 10px;
      border-style: dotted;
      flex-grow:1;
      display: flex;
      flex-direction: row;
      align-items: center;
    }
  </style>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});

	Joomla.submitbutton = function (task) {
		if (task == 'citationstyle.cancel') {
			Joomla.submitform(task, document.getElementById('citationstyle-form'));
		}
		else {
			
			if (task != 'citationstyle.cancel' && document.formvalidator.isValid(document.id('citationstyle-form'))) {
				
				Joomla.submitform(task, document.getElementById('citationstyle-form'));
			}
			else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>
<script type="text/javascript">
    var blocks;
    var authorBlocks;
    var specialBlocks;
    var arrayString = [];
    var arrayStringAuthor1 = [];
    var arrayStringAuthor2 = [];
    var arrayStringAuthor3 = [];


    //Excecuted when the page is loaded. If a citationstlye is available, it loads it's blocks into the views.
    function loadItems(){

      if(document.getElementById("jform_string").value !== ""){
        document.getElementById("authorArea").style.display = "none";
        document.getElementById("clonedAuthorArea").style.display ="none";
        jQuery("#orderedlist").empty();
        jQuery("#orderedAuthorList1").empty();
        jQuery("#orderedAuthorList2").empty();
        jQuery("#orderedAuthorList3").empty();
        var fieldText = document.getElementById("jform_string").value;
        var newArray = fieldText.split(",");

        if(newArray.includes("1")){
          //Split Array until author starts (1) and add "-3" for author field
          var firstIndexToSplit = newArray.indexOf("1");
          var first = newArray.slice(0, firstIndexToSplit).concat(["-3"]);
          var rest = newArray.slice(firstIndexToSplit + 1);

          var secondIndexToSplit = rest.indexOf("2");
          var second = rest.slice(0, secondIndexToSplit);
          rest = rest.slice(secondIndexToSplit + 1);

          var thirdIndexToSplit = rest.indexOf("3");
          var third = rest.slice(0, thirdIndexToSplit);
          rest = rest.slice(thirdIndexToSplit + 1);

          var fourthIndexToSplit = rest.indexOf("4");
          var fourth = rest.slice(0, fourthIndexToSplit);
          var last = rest.slice(fourthIndexToSplit + 1);
          
          var newResulArray = first.concat(last);
          
          document.getElementById("authorArea").style.display = "flex";
          document.getElementById("clonedAuthorArea").style.display = "flex";

          newResulArray.forEach(function (item, index) {
                var li = document.createElement("li");
                jQuery(li).addClass(item);
                jQuery(li).addClass("clonedBlock");
                if(item in blocks){
                  var content = document.createTextNode(blocks[item]);
                  jQuery(li).addClass("cloned");
                } else {
                  var content = document.createTextNode(specialBlocks[item]);
                  jQuery(li).addClass("clonedCharacter");
                }
                li.appendChild(content);
                jQuery(li).draggable({
                  //connectToSortable: "#orderedlist",
                  revert: function (valid) {
                    if (!valid) {
                      if (jQuery(this).hasClass("-3")) {
                          jQuery("#orderedAuthorList1").empty();
                          jQuery("#orderedAuthorList2").empty();
                          jQuery("#orderedAuthorList3").empty();
                          document.getElementById("authorArea").style.display =
                            "none";
                          document.getElementById("clonedAuthorArea").style.display =
                            "none";
                        }
                      jQuery(this).remove();
                      document.getElementById("jform_string").value = "";
                    } else {
                      //jQuery(".cloned").css("top", "auto").css("left", "auto");
                      return true;
                    }
                  },
                });
                var ol = document.getElementById("orderedlist");
                ol.appendChild(li); 
          });

          second.forEach(function (item, index) {
                var li = document.createElement("li");
                jQuery(li).addClass(item);
                jQuery(li).addClass("clonedBlock");
                if(item in authorBlocks){
                  var content = document.createTextNode(authorBlocks[item]);
                  jQuery(li).addClass("clonedAuthor1");
                } else {
                  var content = document.createTextNode(specialBlocks[item]);
                  jQuery(li).addClass("clonedCharacter1");
                }
                li.appendChild(content);
                jQuery(li).draggable({
                  //connectToSortable: "#orderedAuthorList",
                  revert: function (valid) {
                    if (!valid) {
                      jQuery(this).remove();
                      document.getElementById("jform_string").value = "";
                    } else {
                      //jQuery(".clonedAuthor").css("top", "auto").css("left", "auto");
                      return true;
                    }
                  },
                });
                var ol = document.getElementById("orderedAuthorList1");
                ol.appendChild(li);
          });

          third.forEach(function (item, index) {
                var li = document.createElement("li");
                jQuery(li).addClass(item);
                jQuery(li).addClass("clonedBlock");
                if(item in authorBlocks){
                  var content = document.createTextNode(authorBlocks[item]);
                  jQuery(li).addClass("clonedAuthor2");
                } else {
                  var content = document.createTextNode(specialBlocks[item]);
                  jQuery(li).addClass("clonedCharacter2");
                }
                li.appendChild(content);
                jQuery(li).draggable({
                  //connectToSortable: "#orderedAuthorList",
                  revert: function (valid) {
                    if (!valid) {
                      jQuery(this).remove();
                      document.getElementById("jform_string").value = "";
                    } else {
                      //jQuery(".clonedAuthor").css("top", "auto").css("left", "auto");
                      return true;
                    }
                  },
                });
                var ol = document.getElementById("orderedAuthorList2");
                ol.appendChild(li);
          });

          fourth.forEach(function (item, index) {
                var li = document.createElement("li");
                jQuery(li).addClass(item);
                jQuery(li).addClass("clonedBlock");
                if(item in authorBlocks){
                  var content = document.createTextNode(authorBlocks[item]);
                  jQuery(li).addClass("clonedAuthor3");
                } else {
                  var content = document.createTextNode(specialBlocks[item]);
                  jQuery(li).addClass("clonedCharacter3");
                }
                li.appendChild(content);
                jQuery(li).draggable({
                  //connectToSortable: "#orderedAuthorList",
                  revert: function (valid) {
                    if (!valid) {
                      jQuery(this).remove();
                      document.getElementById("jform_string").value = "";
                    } else {
                      //jQuery(".clonedAuthor").css("top", "auto").css("left", "auto");
                      return true;
                    }
                  },
                });
                var ol = document.getElementById("orderedAuthorList3");
                ol.appendChild(li);
          });
        }
      }
    }



        

    function submitClicked() {
      if (document.getElementById("jform_string").value == "") {
        arrayString = [];
        var ol = document.getElementById("orderedlist");
        var items = ol.getElementsByTagName("li");
        for (var i = 0; i < items.length; ++i) {
          for (y in blocks) {
            if (jQuery(items[i]).hasClass(y)) {
              arrayString.push(y);
            }
          }
          for (h in specialBlocks){
            if (jQuery(items[i]).hasClass(h)) {
              arrayString.push(h);
            }
          }
        }
        arrayStringAuthor1 = [];
        var olAuthor = document.getElementById("partAuthor1");
        var authorItems = olAuthor.getElementsByTagName("li");
        for (var t = 0; t < authorItems.length; ++t) {
          for (z in authorBlocks) {
            if (jQuery(authorItems[t]).hasClass(z)) {
              arrayStringAuthor1.push(z);
            }
          }
          for (f in specialBlocks){
            if (jQuery(authorItems[t]).hasClass(f)) {
              arrayStringAuthor1.push(f);
            }
          }
        }
        arrayStringAuthor2 = [];
        var olAuthor = document.getElementById("partAuthor2");
        var authorItems = olAuthor.getElementsByTagName("li");
        for (var t = 0; t < authorItems.length; ++t) {
          for (z in authorBlocks) {
            if (jQuery(authorItems[t]).hasClass(z)) {
              arrayStringAuthor2.push(z);
            }
          }
          for (f in specialBlocks){
            if (jQuery(authorItems[t]).hasClass(f)) {
              arrayStringAuthor2.push(f);
            }
          }
        }
        arrayStringAuthor3 = [];
        var olAuthor = document.getElementById("partAuthor3");
        var authorItems = olAuthor.getElementsByTagName("li");
        for (var t = 0; t < authorItems.length; ++t) {
          for (z in authorBlocks) {
            if (jQuery(authorItems[t]).hasClass(z)) {
              arrayStringAuthor3.push(z);
            }
          }
          for (f in specialBlocks){
            if (jQuery(authorItems[t]).hasClass(f)) {
              arrayStringAuthor3.push(f);
            }
          }
        }

        if(arrayString.includes("-3")){
          var indexToSplit = arrayString.indexOf("-3");
          var first = arrayString.slice(0, indexToSplit);
          var last = arrayString.slice(indexToSplit + 1);
          var mid = [1].concat(arrayStringAuthor1).concat([2]).concat(arrayStringAuthor2).concat([3]).concat(arrayStringAuthor3).concat([4]);
          var resultArray = first.concat(mid).concat(last);
          var textField = document.getElementById("jform_string");
          //var txt = '{"-1:"[' + resultArray.toString() + ']}';
          var txt = resultArray.toString();
          textField.value = txt;
        } else {
          var textField = document.getElementById("jform_string");
          //var txt = '{"-1:"[' + arrayString.toString() + ']}';
          var txt = arrayString.toString();
          textField.value = txt;
        }
        

      }
    }

    jQuery(document).ready(function () {
      
      //jQuery("#orderedlist").sortable();

        blocks = JSON.parse('<?php echo json_encode($blocks) ?>');
        blocks["-3"] = "Author";

        authorBlocks = JSON.parse('<?php echo json_encode($authorBlocks) ?>');

        specialBlocks = JSON.parse('<?php echo json_encode($specialBlocks) ?>');

      loadItems();

      Object.entries(blocks).forEach(([key, value]) => {
        var li = document.createElement("li");
        jQuery(li).addClass("block original");
        jQuery(li).addClass(key);
        var content = document.createTextNode(value);
        li.appendChild(content);
        var ol = document.getElementById("fixlist");
        ol.appendChild(li);
      });

      Object.entries(authorBlocks).forEach(([key, value]) => {
        var li = document.createElement("li");
        jQuery(li).addClass("block originalAuthor");
        jQuery(li).addClass(key);
        var content = document.createTextNode(value);
        li.appendChild(content);
        var ol = document.getElementById("fixAuthorList");
        ol.appendChild(li);
      });

      Object.entries(specialBlocks).forEach(([key, value]) => {
        var li = document.createElement("li");
        jQuery(li).addClass("block originalCharacter");
        jQuery(li).addClass(key);
        var content = document.createTextNode(value);
        li.appendChild(content);
        var ol = document.getElementById("fixSpecialList");
        ol.appendChild(li);
      });

      jQuery(".block").draggable({ helper: "clone", revert: "invalid" });

      jQuery(".clonedArea").droppable({
        accept: ".original, .cloned, .originalCharacter, .clonedCharacter",
        drop: function (ev, ui) {
          if (jQuery(ui.draggable).hasClass("original") || (jQuery(ui.draggable).hasClass("originalCharacter"))) {
            document.getElementById("jform_string").value = "";
            var element = ui.draggable.clone();
            jQuery(element).addClass("clonedBlock");
            jQuery(element).removeClass("block");
            if(jQuery(ui.draggable).hasClass("original")){
              jQuery(element).addClass("cloned");
              jQuery(element).removeClass("original");
            } else{
              jQuery(element).addClass("clonedCharacter");
              jQuery(element).removeClass("originalCharacter");
            }
            jQuery(element).draggable({
              connectToSortable: "#orderedlist",
              revert: function (valid) {
                if (!valid) {
                  if (jQuery(this).hasClass("-3")) {
                    jQuery("#orderedAuthorList1").empty();
                    jQuery("#orderedAuthorList2").empty();
                    jQuery("#orderedAuthorList3").empty();
                    document.getElementById("authorArea").style.display =
                      "none";
                    document.getElementById("clonedAuthorArea").style.display =
                      "none";
                  }
                  jQuery(this).remove();
                  jQuery(".-3").addClass("original");
                  document.getElementById("jform_string").value = "";
                } else {
                  //jQuery(".cloned").css("top", "auto").css("left", "auto");
                  return true;
                }
              },
            });
            jQuery("#orderedlist").append(element);
            if (jQuery(ui.draggable).hasClass("-3")) {
              jQuery(".-3").removeClass("original");
              document.getElementById("authorArea").style.display = "flex";
              document.getElementById("clonedAuthorArea").style.display =
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
            var element = ui.draggable.clone();
            jQuery(element).removeClass("block");
            jQuery(element).addClass("clonedBlock");
            if(jQuery(ui.draggable).hasClass("originalAuthor")){
              jQuery(element).addClass("clonedAuthor1");
              jQuery(element).removeClass("originalAuthor");
            } else{
              jQuery(element).addClass("clonedCharacter1");
              jQuery(element).removeClass("originalCharacter");
            }
            jQuery(element).draggable({
              //connectToSortable: "#orderedlist",
              revert: function (valid) {
                if (!valid) {
                  jQuery(this).remove();
                  document.getElementById("jform_string").value = "";
                } else {
                  //jQuery(".clonedAuthor").css("top", "auto").css("left", "auto");
                  return true;
                }
              },
            });
            jQuery("#orderedAuthorList1").append(element);
          }
        },
      });


      jQuery(".partAuthor2").droppable({
        accept: ".originalAuthor, .clonedAuthor2, .clonedCharacter2, .originalCharacter",
        drop: function (ev, ui) {
          if (jQuery(ui.draggable).hasClass("originalAuthor") || (jQuery(ui.draggable).hasClass("originalCharacter"))) {
            document.getElementById("jform_string").value = "";
            var element = ui.draggable.clone();
            jQuery(element).removeClass("block");
            jQuery(element).addClass("clonedBlock");
            if(jQuery(ui.draggable).hasClass("originalAuthor")){
              jQuery(element).addClass("clonedAuthor2");
              jQuery(element).removeClass("originalAuthor");
            } else{
              jQuery(element).addClass("clonedCharacter2");
              jQuery(element).removeClass("originalCharacter");
            }
            jQuery(element).draggable({
              //connectToSortable: "#orderedlist",
              revert: function (valid) {
                if (!valid) {
                  jQuery(this).remove();
                  document.getElementById("jform_string").value = "";
                } else {
                  //jQuery(".clonedAuthor").css("top", "auto").css("left", "auto");
                  return true;
                }
              },
            });
            jQuery("#orderedAuthorList2").append(element);
          }
        },
      });


      jQuery(".partAuthor3").droppable({
        accept: ".originalAuthor, .clonedAuthor3, .clonedCharacter3, .originalCharacter",
        drop: function (ev, ui) {
          if (jQuery(ui.draggable).hasClass("originalAuthor") || (jQuery(ui.draggable).hasClass("originalCharacter"))) {
            document.getElementById("jform_string").value = "";
            var element = ui.draggable.clone();
            jQuery(element).removeClass("block");
            jQuery(element).addClass("clonedBlock");
            if(jQuery(ui.draggable).hasClass("originalAuthor")){
              jQuery(element).addClass("clonedAuthor3");
              jQuery(element).removeClass("originalAuthor");
            } else{
              jQuery(element).addClass("clonedCharacter3");
              jQuery(element).removeClass("originalCharacter");
            }
            jQuery(element).draggable({
              //connectToSortable: "#orderedlist",
              revert: function (valid) {
                if (!valid) {
                  jQuery(this).remove();
                  document.getElementById("jform_string").value = "";
                } else {
                  //jQuery(".clonedAuthor").css("top", "auto").css("left", "auto");
                  return true;
                }
              },
            });
            jQuery("#orderedAuthorList3").append(element);
          }
        },
      });
    });
  </script>




<form
	action="<?php echo JRoute::_('index.php?option=com_pubdb&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="citationstyle-form" class="form-validate form-horizontal">

	
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
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
			</fieldset>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>

</form>


<body>
  <div style="display: flex; flex-direction: column;">
    <div style="display: flex; flex-direction: row;">
          <div style="display: flex; flex-direction: column; flex:0.6">
                      <div class="elementArea">
                        <ol style="list-style-type: none;" class="containers" id="fixlist"></ol>
                      </div>
                      <div class="clonedArea">
                        <ol
                          style="list-style-type: none;"
                          class="containers"
                          id="orderedlist"
                        ></ol>
                      </div>
                      <div class="authorArea" id="authorArea">
                        <ol
                          style="list-style-type: none;"
                          class="containers"
                          id="fixAuthorList"
                        ></ol>
                      </div>
                      <div class="clonedAuthorArea" id="clonedAuthorArea">
                        <div class="partAuthor1" id="partAuthor1">
                          <ol
                            style="list-style-type: none;"
                            class="containers"
                            id="orderedAuthorList1"
                          ></ol>
                      </div>
                      <div class="partAuthor2" id="partAuthor2">
                          <ol
                            style="list-style-type: none;"
                            class="containers"
                            id="orderedAuthorList2"
                          ></ol>
                      </div>
                      <div class="partAuthor3" id="partAuthor3">
                          <ol
                            style="list-style-type: none;"
                            class="containers"
                            id="orderedAuthorList3"
                          ></ol>
                      </div>
                      </div>
            </div>
            <div class="specialCharacters" style="flex:0.4">
                    <ol style="list-style-type: none;" class="specialContainer" id="fixSpecialList"></ol>
            </div>
    </div>
    <button
      onclick="submitClicked()"
      style="align-self: center; margin-top: 40px;"
    >
    Submit
    </button>
  </div>
</body>


