<?php
defined('_JEXEC') or die;
class PubdbModelPeople extends JModelAdmin
{

  protected $text_prefix = 'COM_PUBDB';

  // Get the table definition to be able to read or write location details

  public function getTable($type = 'People', $prefix = 'PubdbTable',
                           $config = array())
  {
    return JTable::getInstance($type, $prefix, $config);
  }
  // Get the form definition from /forms/literature.xml

  public function getForm($data = array(), $loadData = true)
  {
    $app = JFactory::getApplication();

    $form = $this->loadForm('com_pubdb.people', 'people',
      array('control'=>'jform', 'load_data'=>$loadData));
    if (empty($form))
    {
      return false;
    }
    return $form;
  }
  // Populate the form with data

  protected function loadFormData()
  {
    $data = JFactory::getApplication()->getUserState('com_pubdb.edit.people.data', array());
    if (($data))
    {
      $data = $this->getItem();
    }
    return $data;
  }
}
