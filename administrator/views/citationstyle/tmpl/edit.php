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
      border-color: grey;
      border-width: 1px;
      border-radius: 10px;
      border-style: dotted;
      display: none;
      flex-direction: row;
      align-items: center;
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
    var arrayStringAuthor = [];


    function loadItems(){
      if(document.getElementById("jform_string").value !== ""){
        document.getElementById("authorArea").style.display = "none";
        document.getElementById("clonedAuthorArea").style.display ="none";
        jQuery("#orderedlist").empty();
        jQuery("#orderedAuthorList").empty();
        var fieldText = document.getElementById("jform_string").value;
        var newArray = fieldText.split(",");
        var authorFlag = false;
        newArray.forEach(function (item, index) {
          if(authorFlag == true ){
                var li = document.createElement("li");
                jQuery(li).addClass("clonedBlock clonedAuthor");
                jQuery(li).addClass(item);
                if(item in authorBlocks){
                  var content = document.createTextNode(authorBlocks[item]);
                } else {
                  var content = document.createTextNode(specialBlocks[item]);
                }
                li.appendChild(content);
                jQuery(li).draggable({
                  connectToSortable: "#orderedAuthorList",
                  revert: function (valid) {
                    if (!valid) {
                      jQuery(this).remove();
                      document.getElementById("jform_string").value = "";
                    } else {
                      jQuery(".clonedAuthor").css("top", "auto").css("left", "auto");
                      return false;
                    }
                  },
                });
                var ol = document.getElementById("orderedAuthorList");
                ol.appendChild(li);
                if (item == "10"){
                  authorFlag = false;
                }      
          } else {
                if (item=="7") {
                  document.getElementById("authorArea").style.display = "flex";
                  document.getElementById("clonedAuthorArea").style.display =
                    "flex";
                  var li = document.createElement("li");
                  jQuery(li).addClass("clonedBlock clonedAuthor");
                  jQuery(li).addClass(item);
                  var content = document.createTextNode(authorBlocks[item]);
                  li.appendChild(content);
                  jQuery(li).draggable({
                    connectToSortable: "#orderedAuthorList",
                    revert: function (valid) {
                      if (!valid) {
                        jQuery(this).remove();
                        document.getElementById("jform_string").value = "";
                      } else {
                        jQuery(".clonedAuthor").css("top", "auto").css("left", "auto");
                        return false;
                      }
                    },
                  });
                  var ol = document.getElementById("orderedAuthorList");
                  ol.appendChild(li);
                  item="3";
                  authorFlag = true;
                } 
                var li = document.createElement("li");
                jQuery(li).addClass("clonedBlock cloned");
                jQuery(li).addClass(item);
                if(item in blocks){
                  var content = document.createTextNode(blocks[item]);
                } else {
                  var content = document.createTextNode(specialBlocks[item]);
                }
                li.appendChild(content);
                jQuery(li).draggable({
                  connectToSortable: "#orderedlist",
                  revert: function (valid) {
                    if (!valid) {
                      if (jQuery(this).hasClass("3")) {
                          jQuery("#orderedAuthorList").empty();
                          document.getElementById("authorArea").style.display =
                            "none";
                          document.getElementById("clonedAuthorArea").style.display =
                            "none";
                        }
                      jQuery(this).remove();
                      document.getElementById("jform_string").value = "";
                    } else {
                      jQuery(".cloned").css("top", "auto").css("left", "auto");
                      return false;
                    }
                  },
                });
                var ol = document.getElementById("orderedlist");
                ol.appendChild(li);  
          }
        });
      }
    };

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
        arrayStringAuthor = [];
        var olAuthor = document.getElementById("clonedAuthorArea");
        var authorItems = olAuthor.getElementsByTagName("li");
        for (var t = 0; t < authorItems.length; ++t) {
          for (z in authorBlocks) {
            if (jQuery(authorItems[t]).hasClass(z)) {
              arrayStringAuthor.push(z);
            }
          }
          for (f in specialBlocks){
            if (jQuery(authorItems[t]).hasClass(f)) {
              arrayStringAuthor.push(f);
            }
          }
        }
        if(arrayString.includes("3")){
          var indexToSplit = arrayString.indexOf("3");
          var first = arrayString.slice(0, indexToSplit);
          var third = arrayString.slice(indexToSplit + 1);
          var resultArray = first.concat(arrayStringAuthor).concat(third);
          var textField = document.getElementById("jform_string");
          var txt = resultArray.toString();
          textField.value = txt;
        } else {
          var textField = document.getElementById("jform_string");
          var txt = arrayString.toString();
          textField.value = txt;
        }
      }
    }

    jQuery(document).ready(function () {
      
      //jQuery("#orderedlist").sortable();

      blocks = {
        1: "Titel",
        2: "Untertitel",
        3: "Autor",
        4: "Verlag",
        5: "Erscheinungsjahr",
        6: "Seitenzahl",
      };

      authorBlocks = {
        7: "RepetitionStart",
        8: "SplitFirstMain",
        9: "SplitMainLast",
        10: "RepetitionEnd",
        11: "Vorname",
        12: "Nachname",
        13: "Initial",
      };

      specialBlocks = {
        14: ",",
        15: ";",
        16: '"',
        17: '(',
        18: ")",
        19: "[",
        20: "]",
      };

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
            if (jQuery(ui.draggable).hasClass("3")) {
              document.getElementById("authorArea").style.display = "flex";
              document.getElementById("clonedAuthorArea").style.display =
                "flex";
            }
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
                  if (jQuery(this).hasClass("3")) {
                    jQuery("#orderedAuthorList").empty();
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
            jQuery("#orderedlist").append(element);
          }
        },
      });

      jQuery(".clonedAuthorArea").droppable({
        accept: ".originalAuthor, .clonedAuthor, .clonedCharacter, .originalCharacter",
        drop: function (ev, ui) {
          if (jQuery(ui.draggable).hasClass("originalAuthor") || (jQuery(ui.draggable).hasClass("originalCharacter"))) {
            document.getElementById("jform_string").value = "";
            var element = ui.draggable.clone();
            jQuery(element).removeClass("block");
            jQuery(element).addClass("clonedBlock");
            if(jQuery(ui.draggable).hasClass("originalAuthor")){
              jQuery(element).addClass("clonedAuthor");
              jQuery(element).removeClass("originalAuthor");
            } else{
              jQuery(element).addClass("clonedCharacter");
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
            jQuery("#orderedAuthorList").append(element);
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
                        <ol
                          style="list-style-type: none;"
                          class="containers"
                          id="orderedAuthorList"
                        ></ol>
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


