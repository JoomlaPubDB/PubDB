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
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

require(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . "PubdbBibTexImporter.php");
require(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . "PubdbBibTexExporter.php");

/**
 * Importer controller class.
 *
 * @since  1.6
 */
class PubdbControllerImporter extends JControllerAdmin
{
  /**
   * Constructor
   *
   * @throws Exception
   */

  /**
   * Importer Task to import files to literature
   * @throws Exception
   * @since 0.0.7
   */

  public function import()
  {
    $msgtype = '';
    $jinput = JFactory::getApplication()->input;
    $post = $jinput->get('jform', 'array()', 'ARRAY');
    $importfile = $jinput->files->get('jform', null, 'files', 'array');
    $link = 'index.php?option=com_pubdb&view=importer';
    $filename = JFile::makeSafe($importfile['import_file']['name']);
    $src = $importfile['import_file']['tmp_name'];
    $dest = JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $filename;
    JFile::upload($src, $dest);

    $type = $post['type'];
    $msg = "";
    switch ($type) {
      // BIBTEX Import
      case 1:
        $importer = new PubdbBibTexImporter(file_get_contents($dest));
        $msg = JText::sprintf('COM_PUBDB_IMPORT_MSG', count($importer->startImport()));
        break;
    }

    JFile::delete($dest);
    $this->setRedirect($link, $msg, $msgtype);
  }

  /**
   * Export Task to export literatures to file download
   * @throws Exception
   * @since v0.0.7
   */
  public function export()
  {
    $msgtype = '';
    $link = 'index.php?option=com_pubdb&view=importer&format=raw';
    $msg = "";
    $exporter = new PubdbBibTexExporter(array());
    $fileString = $exporter->startExport();
    $this->set('Export', $fileString);
    $session = JFactory::getSession();
    $session->set('Export', $fileString, 'PubDB');
    $this->setRedirect($link, $msg, $msgtype);
  }
}
