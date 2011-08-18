<?php

class translate_plugin extends uf_plugin
{
  public function __construct($controller)
  {
    $t = $this;
    $controller->_ = function($id) use ($t) { return $t->_($id); };    
  }
  
  public function _($str)
  {
    return strrev($str);
  }
}

/* EOF */