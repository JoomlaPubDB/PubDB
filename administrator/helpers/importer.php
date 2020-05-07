<?php

require(JPATH_COMPONENT . '/helpers/vendor/autoload.php');

//use JFactory;

JLoader::register('PubdbBibTexImporter', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_pubdb' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'importer.php');

class PubdbBibTexImporter
{

  function __construct($file_string)
  {
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
      "eisbn" => "eisbn",
      "institution" => "publishers", //reference done
      "isbn" => "isbn",
      "journal" => "periodical_id", //reference done
      "month" => "month",
      "pages" => "page_count",
      "publisher" => "publishers", //reference done
      "type" => "reference_type", //reference done
      "title" => "title",
      "series" => "series_title_id", //reference done
      "url" => "online_address",
      "urldate" => "access_date",
      "year" => "year"
    );
  }


  /**
   *
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

    // after all values are formatted begin importing
    //return $this->importLiterature($formattedEntries);

    return $this->importLiterature($formattedEntries);
  }

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
   *
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
   * @param $authors
   * @return mixed|string
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
      return $this->getAuthorIdFromDB($arrAuthorName[1], $arrAuthorName[0]);
    } else {
      foreach (explode(';', $authors) as $author) {
        $arrAuthorName = explode(',', $author);
        $arrAuthorIds[] = $this->getAuthorIdFromDB($arrAuthorName[1], $arrAuthorName[0]);
      }
      // return author IDs as comma separated list
      return implode(',', $arrAuthorIds);
    }
  }

  /**
   * Query database to get author ids by first and last name
   * create new one if none is found
   * @param $first_name
   * @param $last_name
   * @return mixed
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
      $query_in->columns($db->quoteName(array('first_name', 'last_name', 'first_name_initial')));
      $query_in->values(implode(',', array($db->quote($first_name), $db->quote($last_name), $db->quote($first_name_initial))));
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
   * Check if literature typ exists and return id or misc id as fallback
   * @param $type
   * @return mixed|string
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
   * Check if literature typ exists and return id or misc id as fallback
   * @param $type
   * @return mixed|string
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
      $insert->columns($db->quoteName(array('name', 'issn', 'eissn')));
      $insert->values(implode(',', array($db->quote($name), $db->quote($issn), $db->quote($eissn))));
      $db->setQuery($insert);
      $db->execute();
      return $db->insertid();
    } else {
      return $id;
    }
  }

  /**
   * Check if literature typ exists and return id or misc id as fallback
   * @param $type
   * @return mixed|string
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
   * format author name
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
   * remove BibTex standard formatting Brackets from String
   */
  private function removeBracketsFromString($string)
  {
    //remove last and first bracket from string
    $string = rtrim($string, '}');
    $string = ltrim($string, '{');
    return $string;
  }

  /**
   * trim whitespaces, remove brackets etc...
   */
  private function cleanUpString($string)
  {
    $string_return = "";
    //remove whitespace from begin and end of string
    $string = rtrim($string, ' ');
    $string = ltrim($string, ' ');

    // check if string start with a bracket to reformat string
    if (strpos($string, '{') == 0) {
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
