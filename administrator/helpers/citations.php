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
        $result = "";
        $ref_type_id = self::getRefIdFromDb($entry->ref_type);

        $author_state = 0;
        $next_is_delimiter = false;
        $first_is_empty = false;
        $delimiter = null;
        // Block is int id from block_  db
        for ($i = 0; $i < count((array)$pattern[$ref_type_id]); $i++) {
            $block = $pattern[$ref_type_id][$i];

            //Author logic
            if ($block <= 4) {
                if ($block == 2) {
                    $next_is_delimiter = true;
                    $first_is_empty = ($pattern[$ref_type_id][$i - 1] == 1);
                }
                $author_state = $block;
                continue;
            } elseif ($next_is_delimiter) {
                $delimiter = self::tmp($block, $entry);
                $next_is_delimiter = false;
                continue;
            }

            switch ($author_state) {
                //Split First/Main
                case 2:
                    $result .= $delimiter;
                    if ($first_is_empty) {
                        $result = substr($result, 0, strlen($delimiter) * -1);
                        $first_is_empty = false;
                    }
                    //Repetition logic
                    break;
                //End
                case 4:
                    $author_state = 0;
                    $next_is_delimiter = false;
                    $first_is_empty = false;
                    $delimiter = null;
                    break;
            }

            $result .= self::tmp($block, $entry);

        }
        return $result;
    }

    /**
     * Was passiert hier
     *
     * @param $block
     * @param $entry
     * @return String
     *
     * @since  0.0.1
     */

    private function tmp($block, $entry)
    {
        global $blocks;
        $result = "";

        //All the other stuff
        //Has format function or get from DB
        $blockName = ucfirst(strtolower($blocks[$block]['name']));
        if (method_exists(self::class, 'format' . $blockName . 'Field')) {
            $content = call_user_func(array(__CLASS__, 'format' . $blockName . 'Field'), $entry);
        } else {
            $content = strtolower($blocks[$block]['name']);
        }

        //Content is from DB or just format (e.g. "," or ":")
        if ($content != null && array_key_exists($content, (array)$entry)) {
            $result .= $entry->$content;
            $result .= " ";
        } else {
            $result .= $content;
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

    /**
     * Was passiert hier
     *
     * @param $ref_type
     * @return String pattern
     *
     * @since  0.0.1
     */

    private function getRefIdFromDb($ref_type)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select($db->qn('id'))
            ->from($db->quoteName('#__pubdb_reference_types'))
            ->where($db->quoteName('name') . ' LIKE ' . $db->quote($ref_type));
        $db->setQuery($query);
        return $db->loadResult();
    }

    private function formatAuthorsField($item)
    {
        $arrAuthors = explode(",", $item->author_last_name);
    }
}
