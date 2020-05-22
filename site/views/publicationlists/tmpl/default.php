<?php
/**
 * @version    CVS: 0.0.4
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user       = Factory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_pubdb') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'literatureform.xml');
$canEdit    = $user->authorise('core.edit', 'com_pubdb') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'literatureform.xml');
$canCheckin = $user->authorise('core.manage', 'com_pubdb');
$canChange  = $user->authorise('core.edit.state', 'com_pubdb');
$canDelete  = $user->authorise('core.delete', 'com_pubdb');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_pubdb/css/list.css');
//$document->addScript('https://code.jquery.com/jquery-3.5.0.js"');

//add Datatable.JS Scripts
$document->addScript('https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js');
$document->addScriptDeclaration('jQuery.noConflict();');

$document->addScript('https://nightly.datatables.net/searchpanes/js/dataTables.searchPanes.js');
$document->addScript('https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js');

//add Datatable.JS CSS
$document->addStyleSheet('https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css');

$document->addStyleSheet('https://cdn.datatables.net/searchpanes/1.0.1/css/searchPanes.dataTables.min.css');
//print_r($this->state);
$stateArr = (array) $this->state;
$filter = $stateArr['parameters.menu']['frontend_filter'];
$arrFilterMapping = array(
  'publisher_name' => 1,
  'authors' => 2,
  'year' => 3,
  'keywords' => 4,
  'series_title_name' => 5,
  'ref_type' => 6
);

$targets = array();
foreach ($filter as $key => $v){
  $targets[] = $arrFilterMapping[$v];
}
$default = array(1,2,3,4,5,6);
$remove = array_diff($default, $targets);
?>
<table id="example" class="display">
  <thead>
  <tr>
    <th>Publication List</th>
    <th><?php echo JText::sprintf('COM_PUBDB_FORM_LBL_LITERATURE_PUBLISHERS');?></th>
    <th><?php echo JText::sprintf('COM_PUBDB_FORM_LBL_LITERATURE_AUTHORS');?></th>
    <th> <?php echo JText::sprintf('COM_PUBDB_YEAR');?> </th>
    <th><?php echo JText::sprintf('COM_PUBDB_LITERATURES_KEYWORDS');?></th>
    <th><?php echo JText::sprintf('COM_PUBDB_TITLE_SERIES_TITLES');?></th>
    <th><?php echo JText::sprintf('COM_PUBDB_TITLE_REFERENCETYPES');?></th>
  </tr>
  </thead>
  <tbody>
  </tbody>
</table>


<script>
  let data = <?php echo json_encode($this->items)?>;
  jQuery(document).ready(function() {
      //init data table with citation  style column
      jQuery('#example').DataTable({
        "data": data,
        "stateSave": false,
        "columns": [
          {"data": "formatted_string"},
          {"data": "publisher_name"},
          {"data": "authors"},
          {"data": "year"},
          {"data": "keywords"},
          {"data": "series_title_name"},
          {"data": "ref_type"}
        ],
        searchPanes:{
          layout: 'columns-4',
          viewTotal: true,
          cascadePanes: true,
        },
        dom: 'Pfrtip',
        language: {
          searchPanes: {
            count: '{total} found',
            countFiltered: '{shown} of {total}'
          }
        },
        columnDefs:[
          {
            targets: [<?php echo implode(',', $targets);?>],
            searchPanes:{
              show: true
            }
          },
          {
            targets: [<?php echo implode(',', $remove);?>],
            searchPanes:{
              show: false
            }
          },
          {
            targets: [0],
            visible: true,
            searchable: true,
          },
          {
            targets: [1,2,3,4,5,6],
            visible: false,
            searchable: true
          }

        ]
      });
    }
  );
</script>

<?php

if (false){


  ?>

  <form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
        name="adminForm" id="adminForm">

    <?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
    <div class="table-responsive">
      <table class="table table-striped" id="literatureList">
        <thead>
        <tr>
          <th width="5%">
            Publikation
          </th>
        </tr>
        </thead>
        <tfoot>
        <tr>
          <td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
          </td>
        </tr>
        </tfoot>
        <tbody>
        <?php foreach ($this->items as $i => $item) : ?>
          <tr><td><?php print( $item); ?></td></tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php if ($canCreate) : ?>
      <a href="<?php echo Route::_('index.php?option=com_pubdb&task=literatureform.edit&id=0', false, 0); ?>"
         class="btn btn-success btn-small"><i
          class="icon-plus"></i>
        <?php echo Text::_('COM_PUBDB_ADD_ITEM'); ?></a>
    <?php endif; ?>

    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo HTMLHelper::_('form.token'); ?>
  </form>

  <?php if($canDelete) : ?>
    <script type="text/javascript">

      jQuery(document).ready(function () {
        jQuery('.delete-button').click(deleteItem);
      });

      function deleteItem() {

        if (!confirm("<?php echo Text::_('COM_PUBDB_DELETE_MESSAGE'); ?>")) {
          return false;
        }
      }
    </script>
  <?php endif; ?>
<?php } ?>
