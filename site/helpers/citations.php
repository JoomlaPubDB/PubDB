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
 * Class PubdbFrontendHelper
 *
 * @since  1.6
 */
class PubdbLiteraturesCitation
{

    /**
     * Was passiert hier
     *
     * @param $pattern
     * @param $literatureList
     * @since  0.0.1
     */
    public function mapList($pattern, $literatureList)
    {
        global $query, $db, $blocks;

        $query
            ->select($db->qn('*'))
            ->from($db->quoteName('#__pubdb_blocks'));
        $db->setQuery($query);
        $blocks = $db->loadAssocList('id');

        $result = "";
        foreach ($literatureList as $entry) {
            $result .= $this->mapEntry($pattern, $entry);
            $result .= "\n";
        }
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

        // Block is int id from block_db
        foreach ($pattern as $block) {
            $content = $blocks[$block]['name'];
            $prefix = $blocks[$block]['prefix'];
            $suffix = $blocks[$block]['suffix'];
            if ($content != null) {
                if ($prefix != null) $result .= $prefix;

                $result .= $entry[$content];

                if ($suffix != null) $result .= $suffix;
            }
        }

        return $result;
    }
}