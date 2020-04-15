<?php


/**
 * @version    CVS: 0.0.2
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::register('PubdbLiteraturesHelper', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_pubdb' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'literatures.php');

use \Joomla\CMS\Factory;
use \Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * Class PubdbLiteraturesHelper
 *
 * @since  1.6
 */
class PubdbLiteraturesHelper
{
  /**
   * Public entrypoint for class usage
   * @param $literatures
   * @param $citation_style
   * @since  0.0.1
   */
  public function getFormattedLiterature($literatures, $citation_style)
  {
    $pattern = self::getCitationPattern($citation_style);
  }

  /**
   * Get the citation pattern of a citation style id from the database
   * @param $citation_style
   * @return pattern String
   * @since  0.0.1
   */
  private function getCitationPattern($citation_style)
  {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select($db->quoteName(array('string')));
    $query->from($db->quoteName('#__pubdb_citation_style'));
    $query->where($db->quoteName('id') . ' = ' . $citation_style);
    $db->setQuery($query);
    return $db->loadResult();
  }

  /**
   * Create Output table with all items
   * @param $items
   * @since  0.0.1
   */
  private function createItemTable($items)
  {


  }

  /**
   * create formatted string of an item with corresponding citation pattern
   * @param $item
   * @param $citation_pattern
   * @since  0.0.1
   */
  private function createFormattedString($item, $citation_pattern)
  {

  }

}
