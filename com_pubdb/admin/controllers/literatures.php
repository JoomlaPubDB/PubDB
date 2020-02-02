<?php
defined('_JEXEC') or die;
class PubdbControllerLiteratures extends JControllerAdmin
{
  public function getModel($name = 'Literature', $prefix =
  'LiteratureModel', $config = array('ignore_request' => true))
  {
    $model = parent::getModel($name, $prefix, $config);
    return $model;
  }
}
