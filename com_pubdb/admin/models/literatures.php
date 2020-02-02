<?php
/**
 * @package Joomla
 * @subpackage PubDB
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

class PubDBModelLiteratures extends JModelList
{
  protected function getListQuery()
  {
    $db = $this->getDbo();
    $query = $db->getQuery(true);
    $query->select($db->quoteName(array('pubdb_literatur_id', 'Title', 'Subtitle')));
    $query->from($db->quoteName('#__pubdb_Literature'));
    return $query;
  }
}
