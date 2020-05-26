<?php

/**
 * @version     CVS: 0.0.7
 * @package     com_pubdb
 * @subpackage  mod_pubdb
 * @author      Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright   2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license     GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */
defined('_JEXEC') or die;

require (JPATH_ADMINISTRATOR.'/components/com_pubdb/helpers/citations.php');

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;

/**
 * Helper for mod_pubdb
 *
 * @package     com_pubdb
 * @subpackage  mod_pubdb
 * @since       1.6
 */
class ModPubdbHelper
{
    /**
     * Retrieve component items
     *
     * @param   Joomla\Registry\Registry &$params module parameters
     *
     * @return array Array with all the elements
     *
     * @throws Exception
     */
    public static function getList(&$params)
    {
        $ids = $params->get('lit_ids');
        $citation_style = $params->get('citation_style_id');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__pubdb_publication_list');

        if(!empty($ids)){
            foreach ($ids as $id){
                $query->where($db->quoteName('id'). " = " . $id, 'OR' );
            }
        }
        $db->setQuery($query);
        $items = $db->loadAssocList();
        $itemlist = array();
        foreach ($items as $item){
            $itemlist[] = (object) $item;
        }
        $formattedStrings = PubdbLiteraturesCitation::mapList($citation_style, $itemlist);
        $arrStrings = array();
        for ($i = 0 ; $i < count($items); $i ++){
            $arrStrings[] = " <a href=" . JRoute::_('index.php?option=com_pubdb&view=literature&id=' . (int)$items[$i]['id']) . ">" . $formattedStrings[$i] . "</a>";
        }

        return $arrStrings;
    }
}