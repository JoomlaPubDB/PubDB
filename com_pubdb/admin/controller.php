<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_pubdb
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * General Controller of HelloWorld component
 *
 * @package     Joomla.Administrator
 * @subpackage  com_pubdb
 * @since       0.0.1
 */
class PubdbController extends JControllerLegacy
{
	/**
	 * The default view for the display method.
	 *
	 * @var string
	 * @since 12.2
	 */
	protected $default_view = 'cpanel';

	public function display($cachable = false, $urlparams = false){
		//PubDBHelper::addSubmenu($vName);
		$view = $this->input->get('view','cpanel');
		$layout = $this->input->get('layout', 'default');
		$id = $this->input->getInt("pubdb_literatur_id");
		parent::display();
		return $this;
	}

}
