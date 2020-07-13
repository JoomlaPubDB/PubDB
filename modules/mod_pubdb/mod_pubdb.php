<?php

/**
 * @version     CVS: 0.0.5
 * @package     com_pubdb
 * @subpackage  mod_pubdb
 * @author      Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright   2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license     GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */
defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Helper\ModuleHelper;

// Include the syndicate functions only once
JLoader::register('ModPubdbHelper', dirname(__FILE__) . '/helper.php');

$doc = Factory::getDocument();

//allocate params
$allParams = $params;

require ModuleHelper::getLayoutPath('mod_pubdb', 'list');
