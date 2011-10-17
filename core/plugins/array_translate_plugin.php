<?php

require_once(UF_BASE.'/umvc/core/plugins/translate.php');
require_once(UF_BASE.'/core/application.php');

class array_translate_plugin extends translate_plugin
{
  public function t($str, $args = array(), $namespace = '')
  {
    if (empty($namespace))
    {
      $stack = debug_backtrace();
      $stack = $stack[1];
      $filename = $stack['file'];
      $filename = str_replace(uf_application::app_dir(), '', $filename);
      $filename = str_replace(UF_BASE, '', $filename);
      $filename = str_replace('/', '.', $filename);
      $this->namespace = $filename;
    } 
    else {
      $this->namespace = $namespace;
    }

    $language = require(UF_BASE.'/cache/baker'.uf_application::app_name().'/'.uf_application::host().'/language/language.php');
    if (is_array($language))
    {
      $key = $this->namespace.'.'.$this->locale.'.'.$str;
      if (isset($language[$key]))
      {
        return nl2br($this->process_string($language[$key], $args));
      }
    }
    return htmlentities($str, ENT_QUOTES, 'UTF-8');
  }
}

/* EOF */
