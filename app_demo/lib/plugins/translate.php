<?php

class translate_plugin extends uf_plugin
{
  public function __construct($controller)
  {
    $this->controller = $controller;
  }

  public function get_magic_methods()
  {
    // $t = $this;
    //$controller->_ = function($id) use ($t) { return $t->_($id); };    
    return array('_' => array('plugins', 'translate', '_'));
  }
  
  public function _($str)
  {
    return strrev($str);
  }
}

/* EOF */