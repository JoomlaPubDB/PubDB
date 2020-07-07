<?php

require(JPATH_COMPONENT . '/helpers/vendor/autoload.php');

//use JFactory;

JLoader::register('PubdbBibTexImporter', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_pubdb' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'PubdbBibTexImporter.php');

class PubdbBibTexImporter
{

  /**
   * PubdbBibTexImporter constructor.
   * @param $file_string
   * @throws \RenanBr\BibTexParser\Exception\ParserException
   * @since 0.0.7
   */

  /**
   * @var \RenanBr\BibTexParser\Parser() BibTex Parser instance
   * @since v0.0.7
   */
  private $parser = null;

  /**
   * @var \RenanBr\BibTexParser\Listener() BibTex Listener instance
   * @since v0.0.7
   */
  private $listener = null;
  /**
   * @var array mapping table for umlauts
   * @since v0.0.7
   */
  private $umlautsMapping = array();
  /**
   * @var array mapping table for database and BibTex format
   * @since v0.0.7
   */
  private $fieldMapping = array();

  function __construct($file_string)
  {
    /**
     *
     */
    $this->parser = new \RenanBr\BibTexParser\Parser();
    $this->listener = new \RenanBr\BibTexParser\Listener();
    $this->parser->addListener($this->listener);
    $this->parser->parseString($file_string);
    $this->umlautsMapping = array(
      '{\"u}' => "ü",
      '{\"U}' => "Ü",
      '{\"a}' => "ä",
      '{\"A}' => "Ä",
      '{\"o}' => "ö",
      '{\"O}' => "Ö",
      '{\ss}' => "ß",
      '{\&}' => "&"
    );

    $this->fieldMapping = array(
      "address" => "place_of_publication",
      "author" => "authors", //reference done
      "booktitle" => "title",
      "doi" => "doi",
      "edition" => "volume",
      "volume" => "volume",
      "eisbn" => "eisbn",
      "isbn" => "isbn",
      "journal" => "periodical_id", //reference done
      "month" => "month",
      "pages" => "page_range",
      "publisher" => "publishers", //reference done
      "institution" => "publishers", //reference done
      "school" => "publishers",
      "type" => "reference_type", //reference done
      "title" => "title",
      "series" => "series_title_id", //reference done
      "url" => "online_address",
      "howpublished" => "online_address",
      "urldate" => "access_date",
      "year" => "year",
      "month" => "month",
      "editor" => "others_involved"
    );
  }


  /**
   * Start the import process by calling the BibTex parser
   * After parsing go through the elements list and remove unnecessary fields and format fields.
   * @return array
   * @since 0.0.7
   */

  public function startImport()
  {
    $this->entries = $this->listener->export();
    $formattedEntries = array();

    //just formatting stuff
    foreach ($this->entries as $literature) {
      $formattedValues = array();
      foreach ($literature as $field => $value) {
        if ($field == "_original") continue;
        if (method_exists(self::class, 'format' . ucfirst($field))) {
          $formattedValues[$field] = call_user_func(array(__CLASS__, 'format' . ucfirst($field)), $value);
        } else {
          $formattedValues[$field] = $this->cleanUpString($value);
        }

      }
      $formattedEntries[] = $formattedValues;
    }

    return $this->importLiterature($formattedEntries);
  }

  /**
   * Go though the literature list and format each.
   * After chechking all relations call database insert function to insert the literature to the database.
   * return an array of IDs of the imported literatures
   * @param $literature_list
   * @return array
   * @since 0.0.7
   */
  private function importLiterature($literature_list)
  {
    $insertedLiteratures = array();

    foreach ($literature_list as $literature) {
      $formattedValues = array();
      foreach ($literature as $field => $value) {
        if (method_exists(self::class, 'checkRelation' . ucfirst($field))) {
          $formattedValues[$field] = call_user_func(array(__CLASS__, 'checkRelation' . ucfirst($field)), array($field, $literature));
        } else {
          $formattedValues[$field] = $this->cleanUpString($value);
        }
      }

      //now we should have values which are ready to import.
      //loop through values and with keys from mapping
      $literature = array();

      foreach ($formattedValues as $key => $value) {
        if ($this->fieldMapping[$key]) $literature[$this->fieldMapping[$key]] = $value;
      }

      $insertedLiteratures[] = $this->insertLiterature($literature);
    }

    return $insertedLiteratures;
  }


  /**
   * Insert the given literature to the database after reformatting all fields.
   * return the id of the literature
   * @param $literature
   * @return mixed
   * @since 0.0.7
   */

  private function insertLiterature($literature)
  {
    $date = DateTime::createFromFormat('m/d/Y', $literature['year']);
    if (!$date) {
      $date = DateTime::createFromFormat('d.m.Y', $literature['year']);
      if (!$date) {
        $date = DateTime::createFromFormat('d-m-Y', $literature['year']);
        if (!$date) {
          $date = DateTime::createFromFormat('d/m/Y', $literature['year']);
          if (!$date) {
            $date = DateTime::createFromFormat('Y/m/d', $literature['year']);
            if (!$date) {
              $date = DateTime::createFromFormat('d.m.Y', '01.01.' . $literature['year']);
            }
          }
        }
      }
    }

    if ($date != null && $date->format('Y') != 0) {
      $date = date_time_set($date, 0, 0);
      $literature['year'] = $date->format('Y');
      $literature['month'] = $date->format('m');
      $literature['day'] = $date->format('d');
    } else {
      $literature['year'] = null;
    }

    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select($db->quoteName('id'));
    $query->from('#__pubdb_literature');
    $query->where($db->quoteName('title') . ' = ' . $db->quote($literature['title']));
    $query->where($db->quoteName('year') . ' = ' . $db->quote($literature['year']));
    if (isset($literature['isbn'])) $query->where($db->quoteName('isbn') . ' = ' . $db->quote($literature['isbn']), 'AND');
    if (isset($literature['eisbn'])) $query->where($db->quoteName('eisbn') . ' = ' . $db->quote($literature['eisbn']), 'AND');
    if (isset($literature['doi'])) $query->where($db->quoteName('doi') . ' = ' . $db->quote($literature['doi']), 'AND');
    if (isset($literature['reference_type'])) $query->where($db->quoteName('reference_type') . ' = ' . $db->quote($literature['reference_type']), "AND");
    $db->setQuery($query);
    $id = $db->loadResult();

    if ($id === null) {
      $db_in = JFactory::getDbo();
      $cols = array();
      $vals = array();
      // set state to published
      $literature['state'] = 1;

      //workaround ...
      foreach ($literature as $col => $val) {
        $cols[] = $col;
        $vals[] = $db_in->quote($val);
      }
      $query_in = $db_in->getQuery(true);
      $query_in->insert('#__pubdb_literature');
      $query_in->columns($db->quoteName($cols));
      $query_in->values(implode(',', $vals));
      $db_in->setQuery($query_in);
      $db_in->execute();
      return $db_in->insertid();
    } else {
      return $id;
    }
  }


  /**
   * Check if authors already exist and return the the id.
   * If not create new one and return the id of the new inserted author.
   * Return multiple Ids as comma separated list when there are more then one author
   * @param $params
   * @return mixed|string
   * @since v0.0.7
   */
  private function checkRelationAuthor($params)
  {
    $arrAuthorIds = array();
    $field = $params[0];
    $literature = $params[1];
    $authors = $literature[$field];
    $author_amount = count(explode(';', $authors));
    //check if it is only one author...
    if ($author_amount == 1) {
      $arrAuthorName = explode(',', $authors);
      if (count($arrAuthorName) == 1) {
        $arrName = explode(' ', $authors);
        return $this->getAuthorIdFromDB($arrName[0], $arrName[count($arrName) - 1]);
      } else {
        return $this->getAuthorIdFromDB($arrAuthorName[1], $arrAuthorName[0]);
      }
    } else {
      foreach (explode(';', $authors) as $author) {
        if (count(explode(',', $author)) == 1) {
          $arrName = explode(' ', $author);
          $arrName = array_filter($arrName);
          $arrAuthorIds[] = $this->getAuthorIdFromDB($arrName[0], $arrName[count($arrName) - 1]);
        } else {
          $arrName = explode(',', $author);
          $arrName = array_filter($arrName);
          $arrAuthorIds[] = $this->getAuthorIdFromDB($arrName[count($arrName) - 1], $arrName[0]);
        }
      }
      return implode(',', $arrAuthorIds);
    }
  }

  /**
   * Check if person or editor already exist and return the the id.
   * If not create new one and return the id of the new inserted person.
   * Return multiple Ids as comma separated list when there are more then one author
   * @param $params
   * @return mixed|string
   * @since v0.0.7
   */
  private function checkRelationEditor($params)
  {
    $arrPersonIds = array();
    $field = $params[0];
    $literature = $params[1];
    $persons = $literature[$field];

    $editor_amount = count(explode('and', $persons));
    //check if it is only one person...
    if ($editor_amount == 1) {
      $arrPersonName = explode(',', $persons);
      if (count($arrPersonName) == 1) {
        $arrName = explode(' ', $persons);
        return $this->getAuthorIdFromDB($arrPersonName[0], $arrPersonName[count($arrName) - 1]);
      } else {
        return $this->getAuthorIdFromDB($arrPersonName[1], $arrPersonName[0]);
      }
    } else {
      foreach (explode(' and ', $persons) as $person) {
        $arrPersonName = explode(',', $person);
        if (count($arrPersonName) == 1) {
          $arrName = explode(' ', $person);
          $arrName = array_filter($arrName);
          $arrPersonIds[] = $this->getAuthorIdFromDB($arrName[0], $arrName[count($arrName) - 1]);
        } else {
          $arrName = explode(',', $person);
          $arrName = array_filter($arrName);
          $arrPersonIds[] = $this->getAuthorIdFromDB($arrName[count($arrName) - 1], $arrName[0]);
        }
      }
      // return person IDs as comma separated list
      return implode(',', $arrPersonIds);
    }
  }

  /**
   * Query database to get author ids by first and last name
   * create new one if none is found
   * @param $first_name
   * @param $last_name
   * @return mixed
   * @since v0.0.7
   */
  private function getAuthorIdFromDB($first_name, $last_name)
  {
    $db = JFactory::getDbo();
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
      $query_in->columns($db->quoteName(array('state', 'first_name', 'last_name', 'first_name_initial')));
      $query_in->values(implode(',', array(1, $db->quote($first_name), $db->quote($last_name), $db->quote($first_name_initial))));
      $db_in->setQuery($query_in);
      $db_in->execute();
      return $db_in->insertid();
    } else {
      return $id;
    }
  }

  /**
   * Check if literature typ exists and return id or misc id as fallback
   * @param $type
   * @return mixed|string
   * @since v0.0.7
   */
  private function checkRelationType($params)
  {
    $field = $params[0];
    $literature = $params[1];
    $type = $literature[$field];

    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select($db->quoteName('id'));
    $query->from('#__pubdb_reference_types');
    $query->where($db->quoteName('name') . '=' . $db->quote(ucfirst($type)));
    $db->setQuery($query);
    $id = $db->loadResult();
    if ($id == null) {
      return 1;
    } else {
      return $id;
    }
  }

  /**
   * Check if publisher already exists and return id of the exists or create new one and return the new id
   * @param $type
   * @return mixed|string
   * @since v0.0.7
   */
  private function checkRelationPublisher($params)
  {
    $field = $params[0];
    $literature = $params[1];
    $publisher = $literature[$field];

    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select($db->quoteName('id'));
    $query->from('#__pubdb_publisher');
    $query->where($db->quoteName('name') . '=' . $db->quote(ucfirst($publisher)));
    $db->setQuery($query);
    $id = $db->loadResult();
    if ($id == null) {
      $insert = $db->getQuery(true);
      $insert->insert('#__pubdb_publisher');
      $insert->columns($db->quoteName('name'));
      $insert->values($db->quote($publisher));
      $db->setQuery($insert);
      $db->execute();
      return $db->insertid();
    } else {
      return $id;
    }
  }

  /**
   * Check if journal already exists and return id of the exists or create new one and return the new id
   * @param $type
   * @return mixed|string
   * @since v0.0.7
   */
  private function checkRelationJournal($params)
  {
    $literature = $params[1];
    $name = $literature['journal'];
    $issn = isset($literature['issn']) ? $literature['issn'] : '';
    $eissn = isset($literature['eissn']) ? $literature['eissn'] : '';

    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select($db->quoteName('id'));
    $query->from('#__pubdb_periodical');
    $query->where($db->quoteName('name') . ' = ' . $db->quote($name));
    if ($eissn != '') $query->where($db->quote('eissn') . '=' . $db->quote($eissn), 'AND');
    if ($issn != '') $query->where($db->quote('issn') . '=' . $db->quote($issn), 'AND');
    $db->setQuery($query);
    $id = $db->loadResult();
    if ($id == null) {
      $insert = $db->getQuery(true);
      $insert->insert('#__pubdb_periodical');
      $insert->columns($db->quoteName(array('state', 'name', 'issn', 'eissn')));
      $insert->values(implode(',', array($db->quote(1), $db->quote($name), $db->quote($issn), $db->quote($eissn))));
      $db->setQuery($insert);
      $db->execute();
      return $db->insertid();
    } else {
      return $id;
    }
  }

  /**
   * Check if series title already exists and return id of the exists or create new one and return the new id
   * @param $type
   * @return mixed|string
   * @since v0.0.7
   */
  private function checkRelationSeries($params)
  {
    $literature = $params[1];
    $field = $params[0];
    $name = $literature[$field];
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select($db->quoteName('id'));
    $query->from('#__pubdb_series_title');
    $query->where($db->quoteName('name') . ' = ' . $db->quote($name));
    $db->setQuery($query);
    $id = $db->loadResult();
    if ($id == null) {
      $insert = $db->getQuery(true);
      $insert->insert('#__pubdb_series_title');
      $insert->columns($db->quoteName(array('name')));
      $insert->values(implode(',', array($db->quote($name))));
      $db->setQuery($insert);
      $db->execute();
      return $db->insertid();
    } else {
      return $id;
    }
  }


  /**
   * Reformat BibTex Author String, explode Author String by "and".
   * Reformat Author names end remove escaping from BibTex format.
   * @param $authors
   * @return string
   * @since v0.0.7
   */

  private function formatAuthor($authors)
  {
    $ret = "";
    if (count(explode('and ', $authors)) > 1) {
      $tmpArray = array();
      foreach (explode('and ', $authors) as $author) {
        $tmpArray[] = $this->cleanUpString($author);
      }
      $ret = implode(';', $tmpArray);
    } else {
      $ret = $this->cleanUpString($authors);
    }
    return $ret;
  }

  /**
   * Format BibTex month String to numeric value jan => 1
   * @param $item
   * @return mixed
   */
  private function formatMonth($item)
  {
    $months = array(
      '1' => 'jan',
      '2' => 'feb',
      '3' => 'mar',
      '4' => 'apr',
      '5' => 'may',
      '6' => 'jun',
      '7' => 'jul',
      '8' => 'aug',
      '9' => 'sep',
      '10' => 'oct',
      '11' => 'nov',
      '12' => 'dec'
    );

    $months = array_flip($months);
    return $months[$item['month']];
  }

  /**
   * remove BibTex standard formatting Brackets from String
   *
   * @param $string String with brackets
   * @return string formatted String
   * @since v0.0.7
   */
  private function removeBracketsFromString($string)
  {
    //remove last and first bracket from string
    $string = rtrim($string, '}');
    $string = ltrim($string, '{');
    return $string;
  }


  /**
   * clean up whitespaces and replace umlauts
   * @param $string String to format
   * @return string formatted String
   * @since v0.0.7
   */

  private function cleanUpString($string)
  {
    $string_return = "";
    //remove whitespace from begin and end of string
    $string = rtrim($string, ' ');
    $string = ltrim($string, ' ');

    // check if string start with a bracket to reformat string
    if (strpos($string, '{') == 0 && substr($string, -1) == '}') {
      $string_return = $this->removeBracketsFromString($string);
    } else {
      $string_return = $string;
    }
    // remove all umlauts from string
    foreach ($this->umlautsMapping as $umlaut => $rep) {
      $string_return = str_replace($umlaut, $rep, $string_return);
    }
    return $string_return;
  }
}
