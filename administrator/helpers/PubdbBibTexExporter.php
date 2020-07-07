<?php
/**
 * @version    CVS: 0.0.5
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */

//use JFactory;

JLoader::register('PubdbBibTexExporter', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_pubdb' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'PubdbBibTexExporter.php');

/**
 * Class PubdbBibTexExporter
 * class to export literature in BibTeX formatting for download
 * @since v0.0.5
 */
class PubdbBibTexExporter
{
  /**
   * @var array array with literature ids to export
   * @since v0.0.7
   */
  private $ids = array();
  /**
   * @var array mapping table for umlauts
   * @since v0.0.7
   */
  private $umlautsMapping = array();
  /**
   * @var array mapping table for database and BibTex format
   * @since v0.0.7
   */

  /**
   * @var array mapping table for our database fields to  the default BibTex fields
   */
  private $defaultFields = array(
    'ref_type' => 'type',
    'authors' => 'author',
    'others_involved' => 'editor',
    'title' => 'title',
    'access_data' => 'note',
    'keywords' => 'keywords',
    'language' => 'language',
    'online_address' => 'url',
    'page_count' => 'pagetotal',
    'page_range' => 'pages',
    'year' => 'year',
    'month' => 'month',
    'day' => 'day',
    'volume' => 'volume',
    'doi' => 'doi',
    'isbn' => 'isbn',
    'periodical_issn' => 'issn',
    'periodical_eissn' => 'eissn',
    'volume' => 'volume'
  );
  /**
   * @var array mapping for article ref types journal, publisher, address, subtitle
   */
  private $articleFields = array(
    'periodical_name' => 'journal',
    'publisher_name' => 'publisher',
    'place_of_publication' => 'address',
    'subtitle' => 'subtitle'
  );

  /**
   * @var array mapping for book ref types publisher, address, subtitle
   */

  private $bookFields = array(
    'publisher_name' => 'publisher',
    'place_of_publication' => 'address',
    'subtitle' => 'subtitle'
  );

  /**
   * @var array mapping for booklet ref types howpublished, address, subtitle
   */

  private $bookletFields = array(
    'publisher_name' => 'howpublished',
    'place_of_publication' => 'address',
    'subtitle' => 'subtitle'
  );
  /**
   * @var array mapping for inbook ref type publisher, address, subtitle
   */
  private $inbookFields = array(
    'publisher_name' => 'publisher',
    'place_of_publication' => 'address',
    'subtitle' => 'subtitle'

  );
  /**
   * @var array mapping for incollection ref type editor, publisher, address, title
   */
  private $incollectionFields = array(
    'publisher_name' => 'publisher',
    'place_of_publication' => 'address',
    'subtitle' => 'title',
  );

  /**
   * @var array mapping for inproceedings ref type, editor, publisher, address, title, series
   */

  private $inproceedingsFields = array(
    'publisher_name' => 'publisher',
    'place_of_publication' => 'address',
    'subtitle' => 'title',
    'series_title_name' => 'series'
  );

  /**
   * @var array mapping for manual ref type address organization
   */
  private $manualFields = array(
    'place_of_publication' => 'address',
    'publisher_name' => 'organization'
  );

  /**
   * @var array mapping for masterthesis ref type school, address
   */
  private $masterthesisFields = array(
    'publisher_name' => 'school',
    'place_of_publication' => 'address'
  );

  /**
   * @var array mapping for misc ref type howpublished, note
   */
  private $miscFields = array(
    'online_address' => 'howpublished',
    'accessed_on' => 'note'
  );

  /**
   * @var array mapping for phd ref type school, address
   */
  private $phdFields = array(
    'publisher_name' => 'school',
    'place_of_publication' => 'address'
  );
  /**
   * @var array mapping for proceedings ref type editor, publisher, address, series
   */
  private $proceedingsFields = array(
    'publisher_name' => 'publisher',
    'place_of_publication' => 'address',
    'series_title_name' => 'series'
  );

  /**
   * @var array mapping for techreport ref type editor, institution, address, series
   */

  private $techreportFields = array(
    'publisher_name' => 'institution',
    'place_of_publication' => 'address',
    'series_title_name' => 'series'
  );

  function __construct($ids)
  {
    $this->ids = $ids;

    $this->umlautsMapping = array(
      '{\"u}' => "ü",
      '{\"U}' => "Ü",
      '{\"a}' => "ä",
      '{\"A}' => "Ä",
      '{\"o}' => "ö",
      '{\"O}' => "Ö",
      '{\ss}' => "ß",
      '{\&}' => "&"
    );
  }

  /**
   * Public entry point to trigger export, call database function to get all items
   * and build export
   *
   * @return string formatted BibTeX String
   * @since v0.0.5
   */
  public function startExport()
  {
    $items = $this->getItems();
    $formattedItems = $this->formatItems($items);
    return $this->buildBibTexOutput($formattedItems);
  }

  /**
   * Query database to get all records of stored literature and if id filter exists use only these
   * @return mixed
   * @since v0.0.5
   */
  private function getItems()
  {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select('*');
    $query->from('#__pubdb_publication_list');
    if (count($this->ids) > 0) {
      foreach ($this->ids as $id) {
        $query->where($db->quoteName('id') . " = " . $db->quote($id), 'OR');
      }
    }
    $db->setQuery($query);
    return $db->loadAssocList();
  }

  /**
   * Loop through all Items and map all values to corresponding bibtex fields
   * If an extra formatting function exists use it in addition
   *
   * @param $items Array with all Items
   * @return array Array with formatted Items
   * @since v0.0.5
   */
  private function formatItems($items)
  {
    $arrItems = array();
    foreach ($items as $item) {
      $formattedValues = array();
      foreach ($item as $field => $value) {
        $typeFields = strtolower($item['ref_type']) . 'Fields';
        $fieldMapping = (isset($this->$typeFields)) ? array_merge($this->defaultFields, $this->$typeFields) : $this->defaultFields;
        if (method_exists(self::class, 'format' . ucfirst($field))) {
          $formattedValues[$fieldMapping[$field]] = call_user_func(array(__CLASS__, 'format' . ucfirst($field)), $item);
        } else {
          $formattedValues[$fieldMapping[$field]] = $this->formatBibTexString($value);
        }
      }
      $formattedValues['citekey'] = $this->getCiteKey($item);
      $arrItems[] = $formattedValues;
    }

    return $arrItems;
  }

  /**
   * Build BibTeX Literature Blocks from given items and generate random citekey for identification
   * and remove empty fields
   * @param $items Array with all items
   * @return string formatted BibTex String
   * @since v0.0.5
   */
  private function buildBibTexOutput($items)
  {
    $strReturn = "";
    foreach ($items as $item) {
      $itemBlock = "";
      //start of the block with type and citekey
      $itemBlock .= "@" . $item['type'] . "{" . $item['citekey'] . ',' . PHP_EOL;
      // unset value for later loop operation
      $item['type'] = "";
      $item['citekey'] = "";

      foreach ($item as $field => $value) {
        if ($value == "" || $field == "") continue;
        $itemBlock .= $field . " = " . $value . ',' . PHP_EOL;
      }

      $strReturn .= $itemBlock . "}" . PHP_EOL;
    }

    return $strReturn;

  }

  /**
   * generate unique citekey for corresponding literature with it's author, year and random number
   * @param $item literature item
   * @return string citekey
   * @since v0.0.5
   */
  private function getCiteKey($item)
  {
    $rnd = rand(1, 10000);
    $last_names = explode(',', $item['authors_last_name']);
    $author = preg_replace('/\s+/', '', $last_names[0]);
    //remove umlauts..
    $author = iconv('UTF-8', 'ASCII//IGNORE', $author);
    $year = $item['year'];
    return $author . $year . $rnd;
  }

  /**
   * Format Authors of an Literature with last and first name
   * In addition connect them with an "and" to get BibTeX format
   * @param $item
   * @return string
   * @since v0.0.5
   */
  private function formatAuthors($item)
  {
    $arrReturn = array();
    $arrAuthors = array();
    if ($item['authors_last_name'] == "" && $item['authors_first_name'] == "") return "";
    $last_names = explode(',', $item['authors_last_name']);
    $first_names = explode(',', $item['authors_first_name']);

    for ($i = 0; $i < count($last_names); $i++) {
      $arrAuthors[] = $last_names[$i] . "," . $first_names[$i];
    }

    foreach ($arrAuthors as $author) {
      $arrReturn[] = $this->formatBibTexString($author);
    }

    return implode(' and ', $arrReturn);
  }

  /**
   * Format Authors of an Literature with last and first name
   * In addition connect them with an "and" to get BibTeX format
   * @param $item
   * @return string
   * @since v0.0.5
   */
  private function formatOthers_involved($item)
  {
    $arrReturn = array();
    $arrEditors = array();
    if ($item['others_involved_last_name'] == "" && $item['others_involved_first_name'] == "") return "";

    $last_names = explode(',', $item['others_involved_last_name']);
    $first_names = explode(',', $item['others_involved_first_name']);

    for ($i = 0; $i < count($last_names); $i++) {
      $arrEditors[] = $last_names[$i] . "," . $first_names[$i];
    }

    foreach ($arrEditors as $editor) {
      $arrReturn[] = $this->formatBibTexString($editor);
    }
    return implode(' and ', $arrReturn);
  }

  /**
   * Replace numeric month value with BibTex String
   * @param $item stdClass Item Object to Fromat
   * @return String month String in BibTex format
   */

  private function formatMonth($item)
  {
    $months = array(
      '1' => 'jan',
      '2' => 'feb',
      '3' => 'mar',
      '4' => 'apr',
      '5' => 'may',
      '6' => 'jun',
      '7' => 'jul',
      '8' => 'aug',
      '9' => 'sep',
      '10' => 'oct',
      '11' => 'nov',
      '12' => 'dec'
    );
    return $months[$item['month']];
  }

  /**
   * Format strings to get ensure BibTex format.
   * Add Brackets, replace special chars with escaping etc
   * @param $value
   * @return string|string[]
   * @since v0.0.5
   */
  private function formatBibTexString($value)
  {
    $mapping = array_flip($this->umlautsMapping);

    //check if whitespaces are in the value
    $value = str_replace(',', ', ', $value);
    $value = trim($value);
    if (preg_match('/\s/', $value) || preg_match('/,/', $value)) {
      $value = "{" . $value . "}";
    }

    // replace all special chars in value
    foreach ($mapping as $s => $r) {
      $value = str_replace($s, $r, $value);
    }

    return $value;
  }
}
