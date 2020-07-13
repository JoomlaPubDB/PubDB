<?php
/**
 * @version     CVS: 0.0.5
 * @package     com_pubdb
 * @subpackage  mod_pubdb
 * @author      Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright   2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license     GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */
defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;

JHtml::_('jquery.framework');

$elements = ModPubdbHelper::getList($params);
$helper = new ModPubdbHelper($title);
$app = JFactory::getApplication();
$export_ids = $app->get('mod_pubdb_export_id');

// Import CSS
$document = Factory::getDocument();

//add Datatable.JS Scripts
$document->addScript(Uri::root() . 'media/com_pubdb/js/jquery.dataTables.js');

$document->addScriptDeclaration('jQuery.noConflict();');

$document->addScript(Uri::root() . 'media/com_pubdb/js/dataTables.searchPanes.js');
$document->addScript(Uri::root() . 'media/com_pubdb/js/dataTables.select.min.js');

//add Datatable.JS CSS
$document->addStyleSheet(Uri::root() . 'media/com_pubdb/css/jquery.dataTables.css');
$document->addStyleSheet(Uri::root() . 'media/com_pubdb/css/searchPanes.dataTables.min.css');


$stateArr = array();

//use all params from mod_pubdb.php
foreach ($allParams as $key => $value) {
  $stateArr[$key] = $value;
}

$filter = $stateArr['frontend_filter'];
$filter_active = isset($stateArr['frontend_filter_active']) ? True : False;
$filter_paging = isset($stateArr['frontend_paging']) ? 'true' : 'false';
$group_by = $stateArr['pubdb_group_by'];

/**
 * Multidimensional sort function to sort by column value from array
 * @param $arr input array to sort
 * @param $col col key to sort by
 * @param int $dir sort order
 * @return mixed sorted array
 * @since v0.0.7
 */

function array_sort_by_column($arr, $col, $dir = SORT_ASC)
{
  $sort_col = array();
  foreach ($arr as $key => $row) {
    $sort_col[$key] = $row[$col];
  }

  array_multisort($sort_col, $dir, $arr);

  return $arr;
}


//basically same code as in the component publication list

//ignore datatables if grouping
if ($group_by == '0' || !isset($group_by)) {

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
            <th><?php echo JText::sprintf('COM_PUBDB_TITLE_LITERATURE'); ?></th>
            <th><?php echo JText::sprintf('COM_PUBDB_TITLE_PUBLISHER'); ?></th>
            <th><?php echo JText::sprintf('COM_PUBDB_LITERATURES_AUTHORS'); ?></th>
            <th> <?php echo JText::sprintf('COM_PUBDB_YEAR'); ?> </th>
            <th><?php echo JText::sprintf('COM_PUBDB_LITERATURES_KEYWORDS'); ?></th>
            <th><?php echo JText::sprintf('COM_PUBDB_TITLE_SERIES_TITLE'); ?></th>
            <th><?php echo JText::sprintf('COM_PUBDB_TITLE_REFERENCETYPES'); ?></th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <script>
        let data = <?php echo json_encode($elements)?>;
        jQuery(document).ready(function () {
                //init data table with citation  style column
                jQuery('#example').DataTable({
                    language: {
                        sSearch: "<?php echo JText::sprintf('COM_PUBDB_FILTER_PANE_SSEARCH'); ?>",
                        sInfo: "<?php echo JText::sprintf('COM_PUBDB_FILTER_PANE_SINFO'); ?>",
                        searchPanes: {
                            title: {
                                _: "<?php echo "%d " . JText::sprintf('COM_PUBDB_FILTER_PANE_TITLE_MULTI')?>",
                                0: "<?php echo JText::sprintf('COM_PUBDB_FILTER_PANE_TITLE_NONE'); ?>",
                                1: "<?php echo JText::sprintf('COM_PUBDB_FILTER_PANE_TITLE_ONE'); ?>",
                            },
                            count: "<?php echo JText::sprintf('COM_PUBDB_FILTER_PANE_COUNT'); ?>",
                            countFiltered: "<?php echo JText::sprintf('COM_PUBDB_FILTER_PANE_COUNT_FILTERED'); ?>",
                            clearMessage: "<?php echo JText::sprintf('COM_PUBDB_FILTER_PANE_CLEAR_MSG'); ?>",
                        }
                    },
                  <?php if ($filter_active) echo "dom: 'Pfrtip',"; ?>
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

                // add show / hide button to filter pane
                if (document.getElementsByClassName('dtsp-clearAll')[0]) {

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


            }
        );
    </script>
<?php } else {

  $order = (int)$stateArr['pubdb_group_by_order'];
  $grouped_items = array_sort_by_column($elements, $group_by, $order);

  if (isset($stateArr['pubdb_group_by_order'])) {
    $current_group = $grouped_items[0][$group_by];
    $sorting = explode('_', $stateArr['pubdb_group_by_order_in_group']);
    $field = $sorting[0];
    $dir = (int)$sorting[1];
    $arrSorted = array();
    $tmpArray = array();

    foreach ($grouped_items as $item) {
      if ($current_group != $item[$group_by]) {
        $current_group = $item[$group_by];
        $tmpArray[$current_group][$item['id']] = $item;
      }
      $tmpArray[$current_group][$item['id']] = $item;
    }

    foreach ($tmpArray as $groupedItems) {
      $sorted = array_sort_by_column($groupedItems, $field, $dir);
      $arrSorted = array_merge($arrSorted, $sorted);
    }

    $grouped_items = $arrSorted;

  }

  ?>
    <table id="grouped_table" class="display">
        <thead>
        <tr>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <ul>
                  <?php
                  $current_group = $elements[0][$group_by];
                  $tmpArray = array();
                  foreach ($grouped_items as $item) {
                    $tmpArray[$item[$group_by]][] = $item;
                  }

                  foreach ($tmpArray as $key => $list) {
                    if ($group_by == 'ref_type') $key = JText::sprintf('COM_PUBDB_REF_TYPE_' . strtoupper($key));
                    $output .= "<li><h2>" . $key . "</h2></li>";
                    $output .= "<ul>";
                    foreach ($list as $item) {
                      $output .= "<li>" . $item['formatted_string'] . "</li>";
                    }
                    $output .= "</ul>";
                  }
                  print $output;
                  ?>
                </ul>
            </td>
        </tr>
        </tbody>
    </table>
  <?php
};
if (isset($stateArr['allow_citation_change'])) {
  ?>
    <br>
    <form class="form-inline">
        <div class="form-group">
            <label for="citation_selection">
              <?php echo JText::_('COM_PUBDB_VIEW_PUBLICATION_LIST_CHOOSE_CITATION_STYLE'); ?>
            </label>
            <select class="form-control" style="width:auto;" id="citation_selection" onchange="reloadPage(event);">
              <?php
              $current_style = isset($_GET['citation_style']) ? $_GET['citation_style'] : $stateArr['citation_style_id'];
              foreach ($helper->getCitationStyles() as $style) {
                if ($current_style == $style['id']) {
                  echo '<option selected value="' . $style['id'] . '">' . $style['name'] . '</option>';
                } else {
                  echo '<option value="' . $style['id'] . '">' . $style['name'] . '</option>';
                }
              }
              ?>
            </select>
        </div>
    </form>
    <script>
        function reloadPage(evt) {
            location.href = URL_add_parameter(location.href, 'citation_style', evt.target.value);
        }

        function URL_add_parameter(url, param, value) {
            var hash = {};
            var parser = document.createElement('a');

            parser.href = url;

            var parameters = parser.search.split(/\?|&/);

            for (var i = 0; i < parameters.length; i++) {
                if (!parameters[i])
                    continue;

                var ary = parameters[i].split('=');
                hash[ary[0]] = ary[1];
            }

            hash[param] = value;

            var list = [];
            Object.keys(hash).forEach(function (key) {
                list.push(key + '=' + hash[key]);
            });

            parser.search = '?' + list.join('&');
            return parser.href;
        }

    </script>
  <?php
}
if (isset($stateArr['allow_export'])) {
  ?>
    <form action="<?php echo JRoute::_('index.php?option=com_pubdb&view=publicationlists'); ?>" method="post"
          name="adminForm" id="adminForm" class="form-validate form-inline" enctype="multipart/form-data">
        <button class="btn btn-primary pull-right" type="submit"
                onclick="document.getElementById('task').value = 'publicationlists.export';this.form.submit()"/>
        <i class="icon-download icon-white"></i>
      <?php echo JText::_('COM_PUBDB_EXPORT'); ?></button>
      <?php echo JHTML::_('form.token'); ?>
        <input type="hidden" name="option" value="com_pubdb"/>
        <input type="hidden" name="task" id="task" value="importer.import"/>
        <input type="hidden" name="export_id" value="<?php echo implode(',', $export_ids); ?>">
        <input type="hidden" name="controller" value="publicationlists"/>
    </form>
  <?php
}
