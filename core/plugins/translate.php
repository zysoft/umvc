<?php

class translate_plugin extends uf_plugin
{
  public function __construct($controller)
  {
    $this->controller = $controller;
    $this->locale = uf_application::get_config('language');
    $this->namespace = ''; 
  }
  
  public function process_string($str, $args = array())
  {
    if (!empty($args))
    {
      foreach ($args as $key => $value)
      {
        $key_prefix = $key[0];
        if (strcmp($key_prefix, '!'))
        {
          $args[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        } 
        else if (strcmp($key_prefix, '@')) 
        {
          $args[$key] = $value;
        }
      }
      return strtr($str, $args);
    }
    return $str;
  }

  public function set_namespace($namespace) 
  {
    $this->namespace = $namespace;
  }

  public function set_locale($locale)
  {
    $this->locale = $locale;
  }

  public function parse_lang_file($filepath)
  {
    return parse_ini_file($filepath);
  }

  public function get_magic_methods()
  {
    // $t = $this;
    //$controller->_ = function($id) use ($t) { return $t->_($id); }; 
    return array('_' => array('plugins', 'translate', '_'));
  }
}

function t($str, $args = array(), $namespace = '') 
{
  $language = new array_translate_plugin(NULL);
  return $language->t($str, $args, $namespace);
}

/* EOF */
