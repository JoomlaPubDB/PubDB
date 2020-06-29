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

$user = Factory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canCreate = $user->authorise('core.create', 'com_pubdb') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'literatureform.xml');
$canEdit = $user->authorise('core.edit', 'com_pubdb') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'literatureform.xml');
$canCheckin = $user->authorise('core.manage', 'com_pubdb');
$canChange = $user->authorise('core.edit.state', 'com_pubdb');
$canDelete = $user->authorise('core.delete', 'com_pubdb');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_pubdb/css/list.css');
//$document->addScript('https://code.jquery.com/jquery-3.5.0.js"');

//add Datatable.JS Scripts
$document->addScript(Uri::root() . 'media/com_pubdb/js/jquery.dataTables.js');

$document->addScriptDeclaration('jQuery.noConflict();');

$document->addScript(Uri::root() . 'media/com_pubdb/js/dataTables.searchPanes.js');
$document->addScript(Uri::root() . 'media/com_pubdb/js/dataTables.select.min.js');

//add Datatable.JS CSS
$document->addStyleSheet(Uri::root() . 'media/com_pubdb/css/jquery.dataTables.css');
$document->addStyleSheet(Uri::root() . 'media/com_pubdb/css/searchPanes.dataTables.min.css');

$stateArr = (array)$this->state;
$filter = $stateArr['parameters.menu']['frontend_filter'];
$filter_active = isset($stateArr['parameters.menu']['frontend_filter_active']) ? True : False;
$filter_paging = isset($stateArr['parameters.menu']['frontend_paging']) ? 'true' : 'false';
$group_by = $stateArr['parameters.menu']['pubdb_group_by'];

/**
 * Multidimensional sort function to sort by column value from array
 * @param $arr input array to sort
 * @param $col col key to sort by
 * @param int $dir sort order
 */

function array_sort_by_column(&$arr, $col, $dir = SORT_ASC)
{
  $sort_col = array();
  foreach ($arr as $key => $row) {
    $sort_col[$key] = $row[$col];
  }

  array_multisort($sort_col, $dir, $arr);
}


//ignore datatables if grouping
if ($group_by == '0') {

  if ($filter_active) {
    $targets = array();
    $default = array(1, 2, 3, 4, 5, 6);
    $arrFilterMapping = array(
      'publisher_name' => 1,
      'authors' => 2,
      'year' => 3,
      'keywords' => 4,
      'series_title_name' => 5,
      'ref_type' => 6
    );

    if (!empty($filter)) {
      foreach ($filter as $key => $v) {
        $targets[] = $arrFilterMapping[$v];
      }
    } else {
      $targets = $default;
    }

    $remove = array_diff($default, $targets);

    $filter_json = "
  searchPanes:{
          layout: 'columns-4',
          viewTotal: true,
          cascadePanes: true,
          orderable: false
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
            targets: [" . implode(',', $targets) . "],
            searchPanes:{
              show: true
            }
          },
          {
            targets: [" . implode(',', $remove) . "],
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

        ]";
  } else {
    $filter_json = "columnDefs:[
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

        ]";
  }
  ?>
  <table id="example" class="display">
    <thead>
    <tr>
      <th>Publication List</th>
      <th><?php echo JText::sprintf('COM_PUBDB_FORM_LBL_LITERATURE_PUBLISHERS'); ?></th>
      <th><?php echo JText::sprintf('COM_PUBDB_FORM_LBL_LITERATURE_AUTHORS'); ?></th>
      <th> <?php echo JText::sprintf('COM_PUBDB_YEAR'); ?> </th>
      <th><?php echo JText::sprintf('COM_PUBDB_LITERATURES_KEYWORDS'); ?></th>
      <th><?php echo JText::sprintf('COM_PUBDB_TITLE_SERIES_TITLES'); ?></th>
      <th><?php echo JText::sprintf('COM_PUBDB_TITLE_REFERENCETYPES'); ?></th>
    </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  <script>
    let data = <?php echo json_encode($this->items)?>;
    jQuery(document).ready(function () {
        //init data table with citation  style column
        jQuery('#example').DataTable({
          "data": data,
          "stateSave": false,
          "paging": <?php echo $filter_paging ?>,
          "columns": [
            {"data": "formatted_string"},
            {"data": "publisher_name"},
            {"data": "authors"},
            {"data": "year"},
            {"data": "keywords"},
            {"data": "series_title_name"},
            {"data": "ref_type"}
          ],
          <?php echo $filter_json ?>
        });
      jQuery('.dtsp-searchPane').hide();
      let btn = document.createElement('button');
      btn.innerHTML = "<?php echo JText::sprintf('COM_PUBDB_FILTER_SHOW')?>";
      btn.setAttribute('class', 'dtsp-clearAll');
      let container = document.getElementsByClassName('dtsp-titleRow')[0];
      let btn_ref = document.getElementsByClassName('dtsp-clearAll')[0];
      container.insertBefore(btn, btn_ref);
      let hide = false;
      btn.addEventListener('click', function () {
        if (hide) {
          jQuery('.dtsp-searchPane').hide();
          hide = !hide;
          btn.innerHTML = "<?php echo JText::sprintf('COM_PUBDB_FILTER_SHOW')?>";
          } else {
            jQuery('.dtsp-searchPane').show();
            hide = !hide;
            btn.innerHTML = "<?php echo JText::sprintf('COM_PUBDB_FILTER_HIDE')?>";
          }
        });

      }
    );
  </script>
<?php } else {

  $order = (int)$stateArr['parameters.menu']['pubdb_group_by_order'];
  array_sort_by_column($this->items, $group_by, $order);

  $output = "";

  ?>
  <table id="grouped_table" class="display">
    <thead>
    <tr>
      <th></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $current_group = $this->items[0][$group_by];
    $output .= "<tr><th align='left'><h2>" . $current_group . "</h2></th></tr>";
    foreach ($this->items as $item) {
      if ($current_group != $item[$group_by]) {
        $current_group = $item[$group_by];
        $output .= "<tr><th align='left'><h2>" . $current_group . "</h2></th></tr>";
      }
      $output .= "<tr><td>" . $item['formatted_string'] . "</td></tr>";
    }
    print $output;
    ?>
    </tbody>
  </table>
  <?php
};
