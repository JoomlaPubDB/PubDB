<?php
/**
 * @version    CVS: 0.0.5
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * Class PubdbViewPublicationlists
 * Used to enable downloading the publication list as BibTex file.
 * Echos the export file and set HTTP header to download the output.
 * @since v0.0.7
 */
class PubdbViewPublicationlists extends JViewLegacy
{
  /**
   * Joomla! display function
   * @param null $tpl
   * @return mixed|void
   * @since v0.0.7
   * @see JViewLegacy
   */
  function display($tpl = null)
  {
    global $mainframe;
    $file = $this->get('Export');

    $session = JFactory::getSession();

    $file = $session->get('Export', '', 'PubDB');
    $doc = JFactory::getDocument();
    $doc->setMimeEncoding('text/plain');

    $filename = "pubdb" . "-export-" . date('d-m-Y-H-i') . '.bib';
    JResponse::setHeader('Content-disposition', 'attachment' . '; filename=' . $filename);
    echo $file;
  }
}
