<?php

/**
 * @package Joomla
 * @subpackage PubDB
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_('behavior.tabstate');
if(!defined('DS')){
   define('DS',DIRECTORY_SEPARATOR);
}

JLoader::register('PubDBHelper', __DIR__. '/helpers/pubdb.php');
$controller = JControllerLegacy::getInstance('pubdb');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
