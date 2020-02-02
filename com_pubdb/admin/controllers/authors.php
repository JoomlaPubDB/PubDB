<?php
defined('_JEXEC') or die;
class PubdbControllerAuthors extends JControllerAdmin
{
  public function getModel($name = 'Author', $prefix =
  'LiteratureModel', $config = array('ignore_request' => true))
  {
    $model = parent::getModel($name, $prefix, $config);
    return $model;
  }
}