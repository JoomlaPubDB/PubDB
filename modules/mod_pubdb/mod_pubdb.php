<?php

/**
 * @version     CVS: 0.0.1
 * @package     PubDB
 * @subpackage  mod_pubdb
 * @author      Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @copyright   2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license     GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */
defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Helper\ModuleHelper;

// Include the syndicate functions only once
JLoader::register('ModPubdbHelper', dirname(__FILE__) . '/helper.php');

$doc = Factory::getDocument();

/* */
$doc->addStyleSheet(URI::base() . 'media/mod_pubdb/css/style.css');

/* */
$doc->addScript(URI::base() . 'media/mod_pubdb/js/script.js');

require ModuleHelper::getLayoutPath('mod_pubdb', $params->get('content_type', 'blank'));
