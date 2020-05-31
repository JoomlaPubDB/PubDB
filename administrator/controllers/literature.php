<?php
/**
 * @version    CVS: 0.0.5
 * @package    Com_Pubdb
 * @author     Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke <>
 * @copyright  2020 Max Dunger, Julian Pfau, Robert Strobel, Florian Warnke
 * @license    GNU General Public License Version 2 oder neuer; siehe LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Literature controller class.
 *
 * @since  1.6
 */
class PubdbControllerLiterature extends \Joomla\CMS\MVC\Controller\FormController
{
  /**
   * Constructor
   *
   * @throws Exception
   */
  public function __construct()
  {
    $this->view_list = 'literatures';
    parent::__construct();
  }

  /**
   * Override default save function to get the author sub form.
   * Create new authors or get the id if they already exist and override / merge with real author field.
   * Return / call parent save function to use joomla defaults.
   * @param null $key
   * @param null $urlVar
   * @return bool
   * @throws Exception
   * @since 0.0.7
   */
  public function save($key = null, $urlVar = null)
  {
    $model = $this->getModel();
    $data = $this->input->post->get('jform', array(), 'array');
    if (isset($data['person_subform'])) {
      $arrAuthors = array();
      foreach ($data['person_subform'] as $author) {
        $arrAuthors[] = (int)$this->checkForNewAuthor($author);
      }
      $authors = isset($data['authors']) ? $data['authors'] : array();
      $arr_merged = array_unique(array_merge($arrAuthors, $authors));

      $data['authors'] = $arr_merged;
      $this->input->post->set('jform', $data);
    }
    return parent::save($key, $urlVar);
  }

  /**
   * Check if new Author already exists and insert new one. Return person id in both cases.
   * @param $author
   * @return mixed
   * @since 0.0.7
   */
  private function checkForNewAuthor($author)
  {
    $db = JFactory::getDbo();
    $first_name = $author['first_name'];
    $last_name = $author['last_name'];

    $query = $db->getQuery(true);
    $query->select($db->quoteName('id'));
    $query->from('#__pubdb_person');
    $query->where($db->quoteName('first_name') . '=' . $db->quote($first_name));
    $query->where($db->quoteName('last_name') . '=' . $db->quote($last_name), 'AND');
    $db->setQuery($query);
    $id = $db->loadResult();

    if ($id == null) {
      $first_name_initial = "";
      $first = trim($first_name);
      foreach (explode(" ", $first) as $part)
        $first_name_initial .= " " . ucfirst(trim($part)[0]) . ".";
      if (trim($first_name_initial) == ".") $first_name_initial = null;
      $db_in = JFactory::getDbo();
      $query_in = $db_in->getQuery(true);
      $query_in->insert('#__pubdb_person');
      $query_in->columns($db->quoteName(array('first_name', 'last_name', 'first_name_initial', 'middle_name', 'title', 'sex')));
      $query_in->values(implode(',', array($db->quote($first_name), $db->quote($last_name), $db->quote($first_name_initial),
        $db->quote($author['middle_name']), $db->quote($author['title']), $db->quote($author['sex'])
      )));
      $db_in->setQuery($query_in);
      $db_in->execute();
      return $db_in->insertid();
    } else {
      return $id;
    }
  }

}
