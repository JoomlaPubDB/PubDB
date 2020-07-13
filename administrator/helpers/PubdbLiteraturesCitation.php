<?php


/**
 * @version    CVS: 0.0.3
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('PubdbLiteraturesHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_pubdb' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'literatures.php');

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$blocks = null;

/**
 * Class PubdbLiteraturesCitation
 *
 * @since  1.6
 */
class PubdbLiteraturesCitation
{

  /**
   * Generates formatted String in citation style format
   * for each entry in literature list
   *
   * @param $patternId
   * @param $literatureList
   * @return array String[] with formatted strings in citation style format
   * @since  0.0.1
   */
  public function generateFormattedStings($patternId, $literatureList)
  {
    global $blocks;

    // Get all blocks from DB, associated by id
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query
      ->select('*')
      ->from($db->quoteName('#__pubdb_blocks'));
    $db->setQuery($query);
    $blocks = $db->loadAssocList('id');

    // Get citation style pattern as json
    $pattern = self::getPatternFromDb($patternId);
    $res = array();

    // Each literature literatureEntry is processed
    foreach ($literatureList as $literatureEntry)
      $res[] = self::generateFormattedString($pattern, $literatureEntry);

    return $res;
  }

  /**
   * Generates formatted String in citation style format
   * for given entry. Global variable Blocks need to be filled before.
   *
   * @param $pattern
   * @param $literatureEntry
   *
   * @return String literature string in citation style format
   *
   * @since  0.0.1
   */
  private function generateFormattedString($pattern, $literatureEntry)
  {
    $result = "";

    // Gets reference type of literature (Default = -1)
    $refTypeId = self::getRefIdFromDb($literatureEntry->ref_type);
    if (!array_key_exists($refTypeId, $pattern))
      $refTypeId = -1;

    // State logic
    $repetitionState = 0;
    $nextIsDelimiter = false;
    $firstAuthorIsEmpty = false;
    $delimiter = null;
    $amountAuthors = -1;
    $isInAuthorBlocks = false;
    $personBlocks = [];

    // Format each block
    for ($citationStyleIndex = 0; $citationStyleIndex < count((array)$pattern[$refTypeId]); $citationStyleIndex++) {
      $block = $pattern[$refTypeId][$citationStyleIndex];

      // Start of author blocks (1,...,2,delimiter,...,3,...4)
      if ($block == 1) {
        $isInAuthorBlocks = true;
        $amountAuthors = count(explode(',', $literatureEntry->authors_first_name));
      }

      // Gets delimiter from block logic
      if ($block == 2) {
        $nextIsDelimiter = true;
        $firstAuthorIsEmpty = ($pattern[$refTypeId][$citationStyleIndex - 1] == 1);
      } elseif ($nextIsDelimiter) {
        $delimiter = self::getFormattedBlock($block, $literatureEntry);
        $nextIsDelimiter = false;
        continue;
      }

      // Process blocks for author
      if ($isInAuthorBlocks) {
        // Cash blocks for repetition
        array_push($personBlocks, $block);

        // At the end of author blocks format through repetition
        if ($block == 4) {
          $isInAuthorBlocks = false;

          // For each person
          for ($personIndex = 0; $personIndex < $amountAuthors; $personIndex++) {
            // For each block
            foreach ($personBlocks as $personBlock) {

              // Is repetition helper block (1,2,3,4) update repetition state
              if ($personBlock <= 4) {
                $repetitionState = $personBlock;
                continue;
              }

              // Is a content or format block
              if (27 <= $personBlock && $personBlock <= 38)
                $personPart = explode(',', self::getFormattedBlock($personBlock, $literatureEntry))[$personIndex];
              else
                $personPart = self::getFormattedBlock($personBlock, $literatureEntry);

              // How to handle block according to $repetitionState
              switch ($repetitionState) {
                case 1:
                  // Isn't first author empty and first block, append block
                  if (!$firstAuthorIsEmpty && $personIndex == 0) $result .= $personPart;
                  break;
                case 2:
                  // Is first author empty and first block, append block
                  if ($firstAuthorIsEmpty && $personIndex == 0) $result .= $personPart;
                  // Isn't last author and not first block, append block
                  if ($personIndex != $amountAuthors - 1 && $personIndex != 0) $result .= $personPart;
                  break;
                case 3:
                  // Is last author and not first block, append block
                  if ($personIndex == $amountAuthors - 1 && $personIndex != 0) $result .= $personPart;
                  break;
              }
            }
            // After each, but last, person append delimiter
            if ($personIndex < $amountAuthors - 2) $result .= $delimiter;
          }

          //Reset states
          $repetitionState = 0;
          $nextIsDelimiter = false;
          $firstAuthorIsEmpty = false;
          $delimiter = null;
          $amountAuthors = -1;
        }
        continue;
      }

      // Append formatted non author block
      $result .= self::getFormattedBlock($block, $literatureEntry);
    }
    return $result;
  }

  /**
   * Format block
   *
   * @param $block String block to be formatted
   * @param $literatureEntry stdClass literature entry to get data from
   * @return String formatted block
   *
   * @since  0.0.1
   */

  private function getFormattedBlock($block, $literatureEntry)
  {
    global $blocks;
    $result = "";

    // Has format function -> call it
    $blockName = ucfirst(strtolower($blocks[$block]['name']));
    if (method_exists(self::class, 'format' . $blockName . 'Field'))
      $content = call_user_func(array(__CLASS__, 'format' . $blockName . 'Field'), $literatureEntry);
    else
      // Get data from DB
      $content = strtolower($blocks[$block]['name']);

    // Is content from DB or just format block (e.g. "," or ":")
    if ($content != null && array_key_exists($content, (array)$literatureEntry))
      // Get from $literatureEntry
      $result .= $literatureEntry->$content;
    else
      // Get translation for block
      $result .= JText::_('COM_PUBDB_CITATIONSTLYE_' . strtoupper($content));

    return $result;
  }

  /**
   * Get citation style from DB
   *
   * @param $citation_style_id int Id of the citation style
   *
   * @return json Pattern of the citation style
   *
   * @since  0.0.1
   */

  private function getPatternFromDb($citation_style_id)
  {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query
      ->select($db->qn('string'))
      ->from($db->quoteName('#__pubdb_citation_style'))
      ->where($db->quoteName('id') . ' = ' . (int)$citation_style_id);
    $db->setQuery($query);
    $pattern = $db->loadResult();
    //fallback if the pattern does not exist
    if ($pattern == null) {
      $query = $db->getQuery(true);
      $query
        ->select($db->qn('string'))
        ->from($db->quoteName('#__pubdb_citation_style'))
        ->where($db->quoteName('id') . ' = 1');
      $db->setQuery($query);
      $pattern = $db->loadResult();
    }
    $pattern = json_decode($pattern, 1);
    return $pattern;
  }

  /**
   * Get reference type id by name
   *
   * @param $refTypeName String reference type name
   * @return int reference type id
   *
   * @since  0.0.1
   */

  private function getRefIdFromDb($refTypeName)
  {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query
      ->select($db->qn('id'))
      ->from($db->quoteName('#__pubdb_reference_types'))
      ->where($db->quoteName('name') . ' LIKE ' . $db->quote($refTypeName));
    $db->setQuery($query);
    return $db->loadResult();
  }
}
