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

    // Add author if in subform
    if (isset($data['author_subform']) && trim($data['author_subform']['author_subform0']['first_name']) != '' && trim($data['author_subform']['author_subform0']['last_name']) != '') {
      $arrAuthors = array();
      foreach ($data['author_subform'] as $author) {
        $arrAuthors[] = (int)$this->checkForNewPerson($author);
      }
      $authors = isset($data['authors']) ? $data['authors'] : array();
      $arr_merged = array_unique(array_merge($arrAuthors, $authors));

      $data['authors'] = $arr_merged;
      $this->input->post->set('jform', $data);
    }

    // Add translator if in subform
    if (isset($data['translator_subform']) && trim($data['translator_subform']['translator_subform0']['first_name']) != '' && trim($data['translator_subform']['translator_subform0']['last_name']) != '') {
      $arrTranslators = array();
      foreach ($data['translator_subform'] as $translator) {
        $arrTranslators[] = (int)$this->checkForNewPerson($translator);
      }
      $translators = isset($data['translators']) ? $data['translators'] : array();
      $arr_merged = array_unique(array_merge($arrTranslators, $translators));

      $data['translators'] = $arr_merged;
      $this->input->post->set('jform', $data);
    }

    // Add others involved if in subform
    if (isset($data['other_subform']) && trim($data['other_subform']['other_subform0']['first_name']) != '' && trim($data['other_subform']['other_subform0']['last_name']) != '') {
      $arrOthersInvolved = array();
      foreach ($data['other_subform'] as $otherInvolved) {
        $arrOthersInvolved[] = (int)$this->checkForNewPerson($otherInvolved);
      }
      $othersInvolved = isset($data['others_involved']) ? $data['others_involved'] : array();
      $arr_merged = array_unique(array_merge($arrOthersInvolved, $othersInvolved));

      $data['others_involved'] = $arr_merged;
      $this->input->post->set('jform', $data);
    }

    // Add publisher if in subform
    if (isset($data['publisher_subform']) && trim($data['publisher_subform']['publisher_subform0']['name'])) {
      $arrPublishers = array();
      foreach ($data['publisher_subform'] as $publisher) {
        $arrPublishers[] = (int)$this->checkForNewPublisher($publisher);
      }
      $publishers = isset($data['publishers']) ? $data['publishers'] : array();
      $arr_merged = array_unique(array_merge($arrPublishers, $publishers));

      $data['publishers'] = $arr_merged;
      $this->input->post->set('jform', $data);
    }

    // Add keywords if in subform
    if (isset($data['keyword_subform']) && trim($data['keyword_subform']['keyword_subform0']['name'])) {
      $arrKeywords = array();
      foreach ($data['keyword_subform'] as $keyword) {
        $arrKeywords[] = (int)$this->checkForNewKeyword($keyword);
      }
      $keywords = isset($data['keywords']) ? $data['keywords'] : array();
      $arr_merged = array_unique(array_merge($arrKeywords, $keywords));

      $data['keywords'] = $arr_merged;
      $this->input->post->set('jform', $data);
    }

    // Add periodical if in subform
    if (isset($data['periodical_subform']) && trim($data['periodical_subform']['periodical_subform0']['issn']) != '' && trim($data['periodical_subform']['periodical_subform0']['name']) != '') {
      $data['periodical_id'] = (int)$this->checkForNewPeriodical($data['periodical_subform']);
      $this->input->post->set('jform', $data);
    }

    // Add series title if in subform
    if (isset($data['series_title_subform']) && trim($data['series_title_subform']['series_title_subform0']['name']) != '') {
      $data['series_title_id'] = (int)$this->checkForNewSeriesTitle($data['series_title_subform']);
      $this->input->post->set('jform', $data);
    }
    return parent::save($key, $urlVar);
  }

  /**
   * Check if new Person already exists and insert new one. Return person id in both cases.
   * @param $person stdClass person object
   * @return int id of the person
   * @since 0.0.7
   */
  private function checkForNewPerson($person)
  {
    $db = JFactory::getDbo();
    $first_name = $person['first_name'];
    $last_name = $person['last_name'];

    // Check if person already exists
    $query = $db->getQuery(true);
    $query->select($db->quoteName('id'));
    $query->from('#__pubdb_person');
    $query->where($db->quoteName('first_name') . '=' . $db->quote($first_name));
    $query->where($db->quoteName('last_name') . '=' . $db->quote($last_name), 'AND');
    $db->setQuery($query);
    $id = $db->loadResult();

    // Insert new person if not existing
    if ($id == null) {
      // Generate first name initial
      $first_name_initial = "";
      $first = trim($first_name);
      foreach (explode(" ", $first) as $part)
        $first_name_initial .= " " . ucfirst(trim($part)[0]) . ".";
      if (trim($first_name_initial) == ".") $first_name_initial = null;

      // Insert into db
      $db_in = JFactory::getDbo();
      $query_in = $db_in->getQuery(true);
      $query_in->insert('#__pubdb_person');
      $query_in->columns($db->quoteName(array('first_name', 'last_name', 'first_name_initial', 'middle_name', 'title', 'sex', 'created_by', 'modified_by')));
      $query_in->values(implode(',', array($db->quote($first_name), $db->quote($last_name), $db->quote($first_name_initial),
        $db->quote($person['middle_name']), $db->quote($person['title']), $db->quote($person['sex']), JFactory::getUser()->id, JFactory::getUser()->id
      )));
      $db_in->setQuery($query_in);
      $db_in->execute();
      return $db_in->insertid();
    } else {
      return $id;
    }
  }

  /**
   * Check if new Publisher already exists and insert new one. Return publisher id in both cases.
   * @param $publisher stdClass publisher object
   * @return int id of publisher
   * @since 0.0.7
   */
  private function checkForNewPublisher($publisher)
  {
    $db = JFactory::getDbo();
    $name = $publisher['name'];

    // Check if publisher already exists
    $query = $db->getQuery(true);
    $query->select($db->quoteName('id'));
    $query->from('#__pubdb_publisher');
    $query->where($db->quoteName('name') . '=' . $db->quote($name));
    $db->setQuery($query);
    $id = $db->loadResult();

    // Insert new publisher if not existing
    if ($id == null) {
      $db_in = JFactory::getDbo();
      $query_in = $db_in->getQuery(true);
      $query_in->insert('#__pubdb_publisher');
      $query_in->columns($db->quoteName(array('name', 'created_by', 'modified_by')));
      $query_in->values(implode(',', array($db->quote($name), JFactory::getUser()->id, JFactory::getUser()->id
      )));
      $db_in->setQuery($query_in);
      $db_in->execute();
      return $db_in->insertid();
    } else {
      return $id;
    }
  }

  /**
   * Check if new Keyword already exists and insert new one. Return keyword id in both cases.
   * @param $keyword stdClass keyword objects
   * @return int id of keyword
   * @since 0.0.7
   */
  private function checkForNewKeyword($keyword)
  {
    $db = JFactory::getDbo();
    $name = $keyword['name'];

    // Check if keyword already exists
    $query = $db->getQuery(true);
    $query->select($db->quoteName('id'));
    $query->from('#__pubdb_keywords');
    $query->where($db->quoteName('name') . '=' . $db->quote($name));
    $db->setQuery($query);
    $id = $db->loadResult();

    // Insert new keyword if not existing
    if ($id == null) {
      $db_in = JFactory::getDbo();
      $query_in = $db_in->getQuery(true);
      $query_in->insert('#__pubdb_keywords');
      $query_in->columns($db->quoteName(array('name', 'created_by', 'modified_by')));
      $query_in->values(implode(',', array($db->quote($name), JFactory::getUser()->id, JFactory::getUser()->id
      )));
      $db_in->setQuery($query_in);
      $db_in->execute();
      return $db_in->insertid();
    } else {
      return $id;
    }
  }

  /**
   * Check if new Periodical already exists and insert new one. Return periodical id in both cases.
   * @param $periodical stdClass periodical objects
   * @return int id of keyword
   * @since 0.0.7
   */
  private function checkForNewPeriodical($periodical)
  {
    $db = JFactory::getDbo();
    $issn = $periodical['issn'];
    $eissn = $periodical['eissn'];
    $name = $periodical['name'];

    // Check if periodical already exists
    $query = $db->getQuery(true);
    $query->select($db->quoteName('id'));
    $query->from('#__pubdb_periodical');
    $query->where($db->quoteName('issn') . '=' . $db->quote($issn));
    $query->where($db->quoteName('name') . '=' . $db->quote($name), 'AND');
    $db->setQuery($query);
    $id = $db->loadResult();

    // Insert new periodical if not existing
    if ($id == null) {
      $db_in = JFactory::getDbo();
      $query_in = $db_in->getQuery(true);
      $query_in->insert('#__pubdb_periodical');
      $query_in->columns($db->quoteName(array('name', 'issn', 'eissn', 'created_by', 'modified_by')));
      $query_in->values(implode(',', array($db->quote($name), $db->quote($issn), $db->quote($eissn),
        JFactory::getUser()->id, JFactory::getUser()->id
      )));
      $db_in->setQuery($query_in);
      $db_in->execute();
      return $db_in->insertid();
    } else {
      return $id;
    }
  }

  /**
   * Check if new Series Title already exists and insert new one. Return series title id in both cases.
   * @param $seriesTitle stdClass series title objects
   * @return int id of keyword
   * @since 0.0.7
   */
  private function checkForNewSeriesTitle($seriesTitle)
  {
    $db = JFactory::getDbo();
    $seriesTitleEditor = $seriesTitle['series_title_editor'];
    $name = $seriesTitle['name'];

    // Check if periodical already exists
    $query = $db->getQuery(true);
    $query->select($db->quoteName('id'));
    $query->from('#__pubdb_series_title');
    $query->where($db->quoteName('name') . '=' . $db->quote($name));
    $db->setQuery($query);
    $id = $db->loadResult();

    // Insert new periodical if not existing
    if ($id == null) {
      $db_in = JFactory::getDbo();
      $query_in = $db_in->getQuery(true);
      $query_in->insert('#__pubdb_series_title');
      $query_in->columns($db->quoteName(array('name', 'series_title_editor', 'created_by', 'modified_by')));
      $query_in->values(implode(',', array($db->quote($name), $db->quote($seriesTitleEditor),
        JFactory::getUser()->id, JFactory::getUser()->id
      )));
      $db_in->setQuery($query_in);
      $db_in->execute();
      return $db_in->insertid();
    } else {
      return $id;
    }
  }

}
