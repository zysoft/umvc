<?php

class uf_plugin
{
  private $controller;
  
  public function get_controller()
  {
    return $this->controller;
  }
  
  public static function load_plugin($controller, $plugin_name)
  {
    $plugin_name = uf_controller::str_to_controller($plugin_name);

    if(isset($controller->$plugin_name))
    {
      return $controller->$plugin_name;
    }

    $plugin_file = uf_application::app_dir().'/lib/plugin/'.$plugin_name.'.php';
    if(!file_exists($plugin_file))
    {
      $plugin_file = UF_BASE.'/core/plugin/'.$plugin_name.'.php';
      if(!file_exists($plugin_file))
      {
        trigger_error('Missing plugin: '.$plugin_name, E_USER_ERROR);      
      }
    }

    require($plugin_file);
    
    $plugin_class = $plugin_name.'_plugin';
    $plugin = new $plugin_class($controller);
    $plugin->controller = $controller;

    return $plugin;
  }
}

/* EOF */