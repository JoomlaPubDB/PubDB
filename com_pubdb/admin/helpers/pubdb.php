<?php
/**
 * @package Joomla
 * @subpackage PubDB
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

class PubDBHelper extends JHelperContent
{
	public static $extension = 'com_pubdb';
        public static function addSubmenu($vName)
        {
                JHtmlSidebar::addEntry(
                        JText::_('COM_PUBDB_SUBMENU_CPANEL'),
                        'index.php?option=com_pubdb&view=cpanel',
                        $vName == 'cpanel'
                );
		JHtmlSidebar::addEntry(
                        JText::_('COM_PUBDB_SUBMENU_LITERATURE'),
                        'index.php?option=com_pubdb&view=literatures',
                        $vName == 'literatures'
                );
                JHtmlSidebar::addEntry(
                        JText::_('COM_PUBDB_SUBMENU_AUTHORS'),
                        'index.php?option=com_pubdb&view=authors',
                        $vName == 'authors'
                );
                JHtmlSidebar::addEntry(
                        JText::_('COM_PUBDB_SUBMENU_PEOPLE'),
                        'index.php?option=com_pubdb&view=people',
                        $vName == 'people'
                );
        }

}
