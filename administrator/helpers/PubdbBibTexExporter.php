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
  private $fieldMapping = array();

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

    $this->fieldMapping = array(
      "address" => "place_of_publication",
      "author" => "authors", //reference done
      "booktitle" => "title",
      "doi" => "doi",
      "edition" => "volume",
      "eisbn" => "eisbn",
      "institution" => "publisher_name", //reference done
      "isbn" => "isbn",
      "journal" => "periodical_name", //reference done
      "issn" => "periodical_issn",
      "eissn" => "periodical_eissn",
      "month" => "month",
      "pages" => "page_range",
      "publisher" => "publisher_name", //reference done
      "type" => "ref_type", //reference done
      "title" => "title",
      "series" => "series_title_name", //reference done
      "url" => "online_address",
      "urldate" => "access_date",
      "year" => "year"
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
    $fieldMapping = array_flip($this->fieldMapping);
    $arrItems = array();
    foreach ($items as $item) {
      $formattedValues = array();
      foreach ($item as $field => $value) {
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
    if (preg_match('/\s/', $value)) {
      $value = "{" . $value . "}";
    }

    // replace all special chars in value
    foreach ($mapping as $s => $r) {
      $value = str_replace($s, $r, $value);
    }

    return $value;
  }
}
