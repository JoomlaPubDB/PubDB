<?php

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );
JHTML::addIncludePath(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers');

/**
 * Cpanel View
 *
 * @since  0.0.1
 */
class PubDBViewCpanel extends JViewLegacy
{
	/**
	 * Display the Literature View
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{
            
                PubDBHelper::addSubmenu("cpanel");

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();
		// Display the template
		parent::display($tpl);
	}

	protected function addToolbar()
	{
			$document       = JFactory::getDocument();
			//$document->addStyleSheet('components/com_abook/assets/css/com_abook.css');

			JToolBarHelper::title(JText::_( 'COM_PUBDB' ), 'book abook-main' );
			require_once JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'pubdb.php';
	}
}
