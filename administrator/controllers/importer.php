<?php
/**
 * @version    CVS: 0.0.5
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

require(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers'. DIRECTORY_SEPARATOR ."importer.php");

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

  public function import() {
    $msgtype = '';
    $jinput = JFactory::getApplication()->input;
    $post   = $jinput->get('jform', 'array()', 'ARRAY');
    $importfile = $jinput->files->get('jform', null, 'files', 'array' );
    $link = 'index.php?option=com_pubdb&view=importer';
    $filename = JFile::makeSafe($importfile['import_file']['name']);
    $src = $importfile['import_file']['tmp_name'];
    $dest = JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers'. DIRECTORY_SEPARATOR ."uploads" . DIRECTORY_SEPARATOR . $filename;
    JFile::upload($src, $dest);

    $type = $post['type'];
    $msg = "";
    switch ($type){
      // BIBTEX Import
      case 1:
        $importer = new PubdbBibTexImporter(file_get_contents($dest));
        $msg = json_encode($importer->startImport());
        break;
    }

    $this->setRedirect($link, $msg, $msgtype);
  }
}
