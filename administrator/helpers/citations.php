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
   * Was passiert hier
   *
   * @param $patternId
   * @param $literatureList
   * @since  0.0.1
   */
  public function mapList($patternId, $literatureList)
  {
    global $blocks;
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);

    $query
      ->select('*')
      ->from($db->quoteName('#__pubdb_blocks'));
    $db->setQuery($query);
    $blocks = $db->loadAssocList('id');

    $pattern = self::getPatternFromDb($patternId);
    $res = array();

    foreach ($literatureList as $entry) {
      $res[] = self::mapEntry($pattern, $entry);
    }

    return $res;
  }

  /**
   * Was passiert hier
   *
   * @param $pattern
   * @param $entry
   *
   * @return String citation
   *
   * @since  0.0.1
   */
  private function mapEntry($pattern, $entry)
  {
    global $blocks;
    $result = "";
    $ref_type = $entry->reference_type;
    // Block is int id from block_db
    foreach ((array)$pattern[$ref_type] as $k => $block) {
      $blockName = ucfirst(strtolower($blocks[$block]['name']));
      print($blockName);
      var_dump(method_exists(self::class, 'format' . $blockName . 'Field'));
      if (method_exists(self::class, 'format' . $blockName . 'Field')) {
        $content = call_user_func(array(__CLASS__, 'format' . $blockName . 'Field'), $entry);
      } else {
        $content = strtolower($blocks[$block]['name']);
      }
      $prefix = $blocks[$block]['prefix'];
      $suffix = $blocks[$block]['suffix'];
      if ($content != null) {
        if ($prefix != null) $result .= $prefix;
        $result .= $entry->$content;
        if ($suffix != null) $result .= $suffix;
        $result .= " ";
      }
    }
    return $result;
  }

  /**
   * Was passiert hier
   *
   * @param $citation_style_id
   *
   * @return String pattern
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
    $pattern = json_decode($pattern, 1);
    return $pattern;
  }

  private function formatAuthorsField($item)
  {
    $arrAuthors = explode(",", $item->author_last_name);
  }
}
