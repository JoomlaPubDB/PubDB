<?php
/**
 * @version    CVS: 0.0.5
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

require(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . "PubdbBibTexExporter.php");

/**
 * Publicationlists list controller class.
 *
 * @since  1.6
 */
class PubdbControllerPublicationlists extends PubdbController
{
  /**
   * Proxy for getModel.
   *
   * @param string $name The model name. Optional.
   * @param string $prefix The class prefix. Optional
   * @param array $config Configuration array for model. Optional
   *
   * @return object  The model
   *
   * @since  1.6
   */
  public function &getModel($name = 'Publicationlists', $prefix = 'PubdbModel', $config = array())
  {
    $model = parent::getModel($name, $prefix, array('ignore_request' => true));

    return $model;
  }

  /**
   * Export Task to export literatures to file download
   * @throws Exception
   */
  public function export()
  {
    $msgtype = '';
    $jinput = JFactory::getApplication()->input;
    $link = 'index.php?option=com_pubdb&view=publicationlists&format=raw';
    $export_ids = $jinput->get('export_id', 'array()', 'ARRAY')[0];
    $export_ids = explode(',', $export_ids);
    $msg = "";
    $exporter = new PubdbBibTexExporter($export_ids);
    $fileString = $exporter->startExport();
    $this->set('Export', $fileString);
    $session = JFactory::getSession();
    $session->set('Export', $fileString, 'PubDB');
    $this->setRedirect($link, $msg, $msgtype);
  }
}
