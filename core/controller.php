<?php
/**
 * Project: umvc: A Mode View Controller framework
 *
 * @author David Brännvall, Jonatan Wallmander, HR North Sweden AB http://hrnorth.se, Copyright (C) 2011.
 * @see The GNU Public License (GPL)
 */
/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

class uf_controller
{
  // PRIVATE DATA
  private $_validator;
  private $_buffer_ref_count;
  private $_call_stack;

  public function load_view($view, $data = array())
  {
    $controller_identifier = substr(get_class($this),0,-11);

    // include the view
    $dir = uf_application::app_sites_host_dir().'/modules/'.$controller_identifier;
    if(!is_dir($dir))
    {
      $dir = uf_application::app_dir().'/modules/'.$controller_identifier;
    }

    if(!is_file($dir.'/view/v_'.$view.'.php'))
    {
      $dir = uf_application::app_dir().'/lib';
    }
    
    uf_include_view($this,$dir.'/view/v_'.$view.'.php');
  }
  
  private function _load_base($view)
  {
    // try to load view from controller, else fallback to base
    if(file_exists($dir.'/view/v_'.$view.'.php'))
    {
      uf_include_view($this,$dir.'/view/v_'.$view.'.php', $data);
      if(file_exists($dir.'/view/v_'.$view.'.js'))
      {
        $this->response()->javascript(file_get_contents($dir.'/view/v_'.$view.'.js'));
      }      
    } 
    else {
      uf_include_view($this,uf_application::app_sites_host_dir().'/base/view/v_'.$view.'.php', $data);
      if(file_exists(uf_application::app_sites_host_dir().'/base/view/v_'.$view.'.js'))
      {
        $this->response()->javascript(file_get_contents(uf_application::app_sites_host_dir().'/base/view/v_'.$view.'.js'));
      }
    }
  }

  private function _push_call_stack_frame($caller,$request,$response,$options)
  {
    array_push(
      $this->_call_stack,
      array(
        'caller' => $caller,
        'request' => $request,
        'response' => $response,
        'options' => $options
      )
    );
  }

  private function _pop_call_stack_frame()
  {
    array_pop($this->_call_stack);
  }

  // BASE METHODS
  
  public function before_action() { return TRUE; }
  public function after_action() { return TRUE; }



  // Language/translation support in UMVC
  // For how to build language codes, see
  // See RFC 5646 (http://tools.ietf.org/html/rfc5646)

  // For optimization purpouses, UMVC limits combinations to
  // the syntax: language-location
  // i.e. en-GB, en-US, en-AU etc.
  //

  // to support parameter translations, define a method like this (if the action is "debug"), see umvc examples for
  // a test case
  // parameters coming from the query string for the index action
  /*public function debug_translate_param($in_parameter_name)
  {
    echo 'translate-param';
    switch ($in_parameter_name)
    {
      case 'parameter1': return 'param1';
      case 'myparameter1': return 'param1';
      case 'parameter2': return 'param2';
    }
  }
  */

  // view API

  // input:    english name
  // returns:  translated name into current language (overridable)
  public function view_lang_get_module_name($controller = '', $language = '')
  {
    // include the baked file above based on module name
    //
    if (empty($controller))
    {
      $controller = $this->request()->get_controller();
    }

    // save the controller name for subsequent calls for action and parameter translations
    global $uf_controller_lang_module_name_cache;
    $uf_controller_lang_module_name_cache = $controller;
    
    $lang = $language;
    if ($language == '') $lang = uf_application::get_language();

    $controller_identifier = uf_controller::str_to_controller($controller);
    $file = uf_application::app_sites_host_dir().'/modules/'.$controller_identifier.'/am_'.$controller_identifier.'.php';
    if(!file_exists($file))
    {
      $file = uf_application::app_dir().'/modules/'.$controller_identifier.'/am_'.$controller_identifier.'.php';
    }
    $ret = NULL;
    if(file_exists($file))
      $ret = include($file);
    if (!is_string($ret))
      return $controller;
    else
      return $ret;
  }


  // input:    english/code name of action
  // returns:  current language version

  public function view_lang_get_action_name($action, $controller = '', $language = '')
  {
    $lang = $language;
    if ($language == '') $lang = uf_application::get_language();

    // deal with the cached names
    if ($controller == '')
    {
      global $uf_controller_lang_module_name_cache;
      $controller = $uf_controller_lang_module_name_cache;
      if ($controller == '') return FALSE;
    }
    global $uf_controller_lang_action_name_cache;
    $uf_controller_lang_action_name_cache = $action;
    //------------------------

    $controller_identifier = uf_controller::str_to_controller($controller);
    $file = uf_application::app_sites_host_dir().'/modules/'.$controller_identifier.'/aa_'.$controller_identifier.'.php';
    if(!file_exists($file))
    {
      $file = uf_application::app_dir().'/modules/'.$controller.'/aa_'.$controller.'.php';
    }
    $ret = NULL;
    if (file_exists($file))
      $ret = include($file);

    if (!is_string($ret))
      return $action;
    else
      return $ret;
  }

  // input:    english/code name of action
  // returns:  current language version

  public function view_lang_get_parameter_name($param, $action = '', $controller = '', $language = '')
  {
    $lang = $language;
    if ($language == '') $lang = uf_application::get_language();

    // deal with the cached names
    if ($controller == '')
    {
      global $uf_controller_lang_module_name_cache;
      $controller = $uf_controller_lang_module_name_cache;
      if ($controller == '') return FALSE;
    }
    if ($action == '')
    {
      global $uf_controller_lang_action_name_cache;
      $action = $uf_controller_lang_action_name_cache;
      if ($action == '') return FALSE;
    }
    //------------------------


    $controller_identifier = uf_controller::str_to_controller($controller);
    $file = uf_application::app_sites_host_dir().'/modules/'.$controller_identifier.'/ap_'.$controller_identifier.'.php';

    if(!file_exists($file))
    {
      $file = uf_application::app_dir().'/modules/'.$controller_identifier.'/ap_'.$controller_identifier.'.php';
    }
    $ret = include($file);
    if (!is_string($ret))
      return $param;
    else
      return $ret;
  }

  // PUBLIC METHODS

  static public function str_to_controller($str)
  {
    $f = array('ö','å','ä','ø','æ','ñ','ü','.',',',';','-','–','/',' ');
    $t = array('o','a','a','o','a','n','u','_','_','_','_','_','_','_');
    $result = preg_replace('/[^\d\w_-]/','',str_replace($f,$t,$str));
    return preg_replace('/_+/','_',$result);
  }


  public function __construct()
  {
    $this->_call_stack = array();
    $this->_buffer_ref_count = 0;    
  }

  public function __destruct()
  {
    while($this->_buffer_ref_count)
    {
      $this->end_buffering();
    }
  }

  public function caller()
  {
    if (!count($this->_call_stack)) return NULL;
    return $this->_call_stack[count($this->_call_stack) - 1]['caller'];
  }

  public function request()
  {
    if (!count($this->_call_stack)) return NULL;
    return $this->_call_stack[count($this->_call_stack) - 1]['request'];
  }

  public function response()
  {
    if (!count($this->_call_stack)) return NULL;
    return $this->_call_stack[count($this->_call_stack) - 1]['response'];
  }

  public function start_buffering()
  {
    $this->_buffer_ref_count++;
    ob_start();
  }

  public function end_buffering()
  {
    $this->_buffer_ref_count--;
    $this->response()->data(ob_get_contents());
    ob_end_clean();
  }

  public function option($name,$default_value = NULL)
  {
    return array_key_exists($name,$this->_call_stack[count($this->_call_stack) - 1]['options']) ? $this->_call_stack[count($this->_call_stack) - 1]['options'] : $default_value;
  }


  static public function execute_base($request,$response,$options = NULL)
  {
    $controller_class = self::str_to_controller($request->get_controller()).'_controller';

    if(class_exists($controller_class))
    {
      // Normal module action
      $controller = new $controller_class;
      $controller->_push_call_stack_frame($controller,$request,$response,$options !== NULL ? $options : array());
      if($controller->execute_action($controller,$request->get_action(),$request,$response,array('enable_buffering' => TRUE)) === FALSE)
      {
        // 404 missing action
        $controller->_error(404);
      }
    }
    else
    {
      // 404 missing module
      $controller = new base_controller;
      $controller->_push_call_stack_frame($controller,$request,$response,$options !== NULL ? $options : array());
      $controller->_error(404);
    }
    $controller->content = $response->data();

    // Send headers
    foreach($response->headers() as $header)
    {
      header($header);
    }

    uf_include_view($controller, uf_application::app_sites_host_dir().'/base/view/v_'.$response->attribute('template').'.php');
    if(class_exists($controller_class))
    {
      $controller->_pop_call_stack_frame();
      $controller = NULL;
    }
  }
  
  public function validator()
  {
    return $this->_validator;
  }
  
  public function execute_action($caller,$action,$request,&$response,$options = NULL)
  {
    if ($action == '')
    {
      $action_identifier = 'index';
    } else {
      $action_identifier = uf_controller::str_to_controller($action);
    }

    // load project/base language files
    uf_include_language($this, uf_application::app_sites_host_dir().'/language/l_base.'.uf_application::get_language().'.php');

    // load module/controller local language file
    $controller = substr(get_class($this),0,-11);
    if(file_exists(
      uf_application::app_sites_host_dir().
      '/modules/'.
      $controller.
      '/c_'.$controller.'.php'))
    {
      uf_include_language($this,uf_application::app_sites_host_dir().'/modules/'.$controller.'/language/l_'.$controller.'.'.uf_application::get_language().'.php');      
    }
    else
    {
      uf_include_language($this,uf_application::app_dir().'/modules/'.$controller.'/language/l_'.$controller.'.'.uf_application::get_language().'.php');      
    }

    // 404 action?
    // handle  www.foo.com/index/

    // loop the request parameters and translate them
    if (method_exists($this,$action_identifier.'_translate_param'))
    {
      $param_names = $request->get_parameter_names();
      if (count($param_names))
      foreach ($param_names as $name)
      {
        $n_name = call_user_func(array($this,$action_identifier.'_translate_param'),$name);
        if (is_string($n_name))
        $request->set_parameter_name($name, $n_name);
      }
    }
    
    // handle nonexistent controller functions
    if(!method_exists($this, $action_identifier))
    {
      if (!method_exists($this, 'error'))
      {
        return FALSE;
      }
      else
      {
        $action_identifier = 'error';
      }
    }

    $this->_push_call_stack_frame($caller,$request,$response,$options !== NULL ? $options : array());

    // start buffering?
    if($this->option('enable_buffering'))
    {
      $this->start_buffering();
    }

    $this->_validator = new uf_validator($request, $response);

    $before_action_ret = $this->before_action();
    if ($before_action_ret === TRUE || $before_action_ret === NULL)
    {
      // execute action
      $view = call_user_func(array($this, $action_identifier));

      $this->after_action();

      $this->_validator = NULL;

      if (is_integer($view))
      {
        $this->_error($view);
      } else
      {
        // default view?
        if($view === NULL || $view === TRUE)
        {
          $view = $action_identifier;
        }

        // no view?
        if($view !== FALSE)
        {
          $this->load_view($view);
        }
      }
    } else
    if ($before_action_ret === FALSE)
    {
    } else
    $this->_error($before_action_ret);

    // stop buffering?
    if($this->option('enable_buffering'))
    {
      $this->end_buffering();
    }

    $this->_pop_call_stack_frame();

    return TRUE;
  }

  public function index() {}

  public function _error($code)
  {
    ob_start();
      uf_include_language($this,uf_application::app_sites_host_dir().'/language/l_base.'.uf_application::get_language().'.php');
      uf_include_view($this,uf_application::app_dir().'/errors/v_'.$code.'.php');
      $this->response()->data(ob_get_contents());
    ob_end_clean();
    if ($code == 404) $this->response()->header404();
    return FALSE;
  }
  
  public static function autoload_controller($class)
  {
    if(substr($class,-11) === '_controller')
    {
      $controller_identifier = substr($class,0,-11);

      if ($controller_identifier == 'base')
      {
        $file = uf_application::app_sites_host_dir().'/base/c_base.php';
      }

      else 
      {
        $file = uf_application::app_sites_host_dir().'/modules/'.$controller_identifier.'/c_'.$controller_identifier.'.php';
        if(!file_exists($file))
        {
          $file = uf_application::app_dir().'/modules/'.$controller_identifier.'/c_'.$controller_identifier.'.php';
        }
      }
      if (file_exists($file))
      {
        include_once($file);        
      }
    }
  }  
}


class uf_view
{
  var $controller = NULL;
  public function set_controller($new_controller)
  {
    $this->controller = $new_controller;
  }

  public function lang_build_uri_module($module, $values = NULL, $override_language = '')
  {
    // TODO
  }

  // grabs all existing parameters and merges with new values from $values
  public function cap($controller_name, $action_name = NULL, $parameters = NULL, $override_language = '')
  {
    $language = uf_application::get_language();
    $internal_language_override = 0;

    if (!empty($override_language))
    {
      if ($override_language != $language)
      {
        $language = $override_language;
        $internal_language_override = 1;
      }
    }

    $new_uri = '';


    $controller = NULL;
    if (empty($controller_name))
    {
      $controller = '';
    }
    else
    {
      $controller = $this->controller->view_lang_get_module_name($controller_name, $language);
    }
    $action = '';
    if (!empty($action_name))
    {
      $action = $this->controller->view_lang_get_action_name($action_name, $controller_name, $language);
    }


    if ($internal_language_override || uf_application::is_language_overridden())
    {
      // add language prefix
      $new_uri = '/'.$language;
    }

    $new_uri .= '/'.$controller;
    if (!empty($action))
    {
      $new_uri .= '/'.$action;
    }

    if (is_array($parameters))
    while (list($key, $val) = each($parameters))
    {
      $new_uri .= '/'.$this->controller->view_lang_get_parameter_name($key, $request->get_action(), $request->get_controller(), $language)
          .'/'.$val;
    }
    
    return $new_uri;
  }
  
  public function lpm_uri($override_parameters = NULL, $override_language = '') { return $this->local_parameter_merge_uri($override_parameters,$override_language); }
  private function local_parameter_merge_uri($override_parameters = NULL, $override_language = '')
  {
    $language = uf_application::get_language();
    $internal_language_override = 0;

    if (!empty($override_language))
    {
      if ($override_language != $language)
      {
        $language = $override_language;
        $internal_language_override = 1;
      }
    }

    $new_uri = '';

    $request = $this->controller->request();

    $controller = $this->controller->view_lang_get_module_name($request->get_controller(),$language);
    $action = $this->controller->view_lang_get_action_name($request->get_action(), $request->get_controller(), $language);

    $parameters = $request->get_uri_parameters();

    if (!empty($override_parameters))
    while (list($key, $val) = each($override_parameters))
    {
      $parameters[$key] = $val;
    }
    
    if ($internal_language_override || uf_application::is_language_overridden())
    {
      // add language prefix
      $new_uri = '/'.$language;
    }

    $new_uri .= '/'.$controller;
    if (!empty($action))
    {
      $new_uri .= '/'.$action;
    }

    if (is_array($parameters))
    while (list($key, $val) = each($parameters))
    {
      $new_uri .= '/'.$this->controller->view_lang_get_parameter_name($key, $request->get_action(), $request->get_controller(), $language)
          .'/'.$val;
    }
    return $new_uri;

  }
}

function uf_include_view($uf_controller,$view)
{
  // This function is used to create a clean symbol table
  extract(get_object_vars($uf_controller));
  extract(array('uf_dir_web_lib' => '/data/baker'.uf_application::app_name().'/lib'));

  // init view class
  $uf_view = new uf_view();
  $uf_view->set_controller($uf_controller);

  $uf_request  = $uf_controller->request();
  $uf_response = $uf_controller->response();
  
  require($view);
}

function uf_include_language($uf_controller,$language_file)
{
  // This function is used to create a clean symbol table
  $language =& $uf_controller->language;
  if(file_exists($language_file))
  {
    include_once($language_file);    
  }
}

# register our controller factory
spl_autoload_register('uf_controller::autoload_controller');

/* EOF */