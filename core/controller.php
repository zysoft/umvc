<?

class uf_controller
{
  // PRIVATE DATA
  private $_buffer_ref_count;
  private $_call_stack;

  private function _load_view($view)
  {
    $controller = uf_controller::str_to_controller(substr(get_class($this),0,-11));

    // include the view
    $dir = uf_application::app_sites_host_dir().'/modules/'.$controller;
    if(!is_dir($dir))
    {
      $dir = uf_application::app_dir().'/modules/'.$controller;
    }        
    uf_include_view($this,$dir.'/view/v_'.$view.'.php');
  }
  
  private function _load_base($view)
  {
    // include the view
    uf_include_view($this,uf_application::app_dir().'/base/view/v_'.$view.'.php');
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

  // PROTECTED METHODS
  
  public function before_action() {}
  public function after_action() {}

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
    $controller_name = uf_controller::str_to_controller($request->controller());
    $action_name     = uf_controller::str_to_controller($request->action());
    $controller_class = $controller_name.'_controller';

    if(class_exists($controller_class))
    {
      // Normal module action
      $controller = new $controller_class;
      $controller->_push_call_stack_frame($controller,$request,$response,$options !== NULL ? $options : array());
      if($controller->execute_action($controller,$action_name,$request,$response,array('enable_buffering' => TRUE)) === FALSE)
      {
        // 404 missing action
        $controller->_error(404);
      }
      $controller->_pop_call_stack_frame();
    }
    else
    {
      // 404 missing module
      $controller = new base_controller;
      $controller->_push_call_stack_frame($controller,$request,$response,$options !== NULL ? $options : array());
      $controller->_error(404);
      $controller->_pop_call_stack_frame();
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
      $controller = NULL;
    }
  }

  public function execute_action($caller,$action,$request,&$response,$options = NULL)
  {    
    // load project/base language files
    uf_include_language($this,uf_application::app_sites_host_dir().'/language/l_base.'.uf_session::get('language',uf_application::config('language','en_US')).'.php');
    // load module/controller local language file
    $controller = substr(get_class($this),0,-11);
    uf_include_language($this,uf_application::app_dir().'/modules/'.$controller.'/language/l_'.$controller.'.'.uf_session::get('language',uf_application::config('language','en_US')).'.php');

    // 404 action?
    // handle  www.foo.com/index/
    if ($action == '') $action = 'index';

    // handle nonexistent controller functions
    if(!method_exists($this,$action))
    {
      if (!method_exists($this,'error'))
      {
        return FALSE;
      }
      else
      {
        $action = 'error';
      }
    }

    $this->_push_call_stack_frame($caller,$request,$response,$options !== NULL ? $options : array());

    // start buffering?
    if($this->option('enable_buffering'))
    {
      $this->start_buffering();
    }

    $action = empty($action) ? 'index' : uf_controller::str_to_controller($action);

    // execute action
    $this->before_action();
    $view = call_user_func(array($this,$action));
    $this->after_action();

    // default view?
    if($view === NULL)
    {
      $view = $action;
    }

    // no view?
    if($view !== FALSE)
    {
      $this->_load_view($view);
    }

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
      uf_include_language($this,uf_application::app_sites_host_dir().'/language/l_base.'.uf_session::get('language',uf_application::config('language','en_US')).'.php');
      uf_include_view($this,uf_application::app_dir().'/errors/v_'.$code.'.php');
      $this->response()->data(ob_get_contents());
    ob_end_clean();
    $this->response()->header404();
    return FALSE;
  }
  
  public static function autoload_controller($class)
  {
    if(substr($class,-10) === 'controller')
    {
      $controller = uf_controller::str_to_controller(substr($class,0,-11));
      $file = '';
      if ($controller[0] == 'b')
      {
        if ($controller == 'base')
        {
          $file = uf_application::app_sites_host_dir().'/base/c_base.php';
        }
      }

      if($file == '')
      {
        $file = uf_application::app_sites_host_dir().'/modules/'.$controller.'/c_'.$controller.'.php';
        if(!file_exists($file))
        {
          $file = uf_application::app_dir().'/modules/'.$controller.'/c_'.$controller.'.php';
        }        
      }
      
      @include_once($file);
    }
  }  
}

function uf_include_view($uf_controller,$uf_view)
{
  // This function is used to create a clean symbol table
  extract(get_object_vars($uf_controller));
  $uf_request  = $uf_controller->request();
  $uf_response = $uf_controller->response();
  require($uf_view);    
}

function uf_include_language($uf_controller,$language_file)
{
  // This function is used to create a clean symbol table
  $language =& $uf_controller->language;
  @include_once($language_file);
}

# register our controller factory
spl_autoload_register('uf_controller::autoload_controller');

?>