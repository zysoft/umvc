<?php

class ufRequest {
  private $_parameters;
  
  protected function parameters($parameters = NULL) {
    if($parameters !== NULL) {
      $this->_parameters = $parameters;
    } else {
      return $this->_parameters;
    }
  }

  public function parameter($name, $default_value = NULL) {
    return array_key_exists($name, $this->_parameters) ? $this->_parameters[$name] : $default_value;
  }

  public function controller() {
    return $this->parameter('_controller', 'default');
  }

  public function action() {
    return $this->parameter('_action', 'index');
  }
}

class ufHTTPRequest extends ufRequest {
  private $_segments;
  
  public function __construct() {
    $uri = $_SERVER['REQUEST_URI'];
    $pos = strpos($uri, '?');
    if($pos !== FALSE) {
      $uri = substr($uri, 0, $pos);
    }
    $this->_segments = explode('/', $uri);
    array_shift($this->_segments);
    $input = array_merge($_GET, $_POST);
    $this->parameters($input);
  }
  
  public function controller() {
    return isset($this->_segments[0]) && !empty($this->_segments[0]) ? $this->_segments[0] : parent::controller();
  }

  public function action() {
    return isset($this->_segments[1]) ? $this->_segments[1] : parent::action();
  }  
}

class ufResponse {
  private $_attributes;
  private $_headers;
  private $_data;
  
  public function __construct() {
    $this->_attributes = array('template' => 'default');
    $this->_headers = array();
    $this->header('Content-Type', 'text/html');
    $this->_data = '';
  }

  public function attribute($name, $value = NULL) {
    if($value !== NULL) {
      $this->_attributes[$name] = $value;
    } else {
      return array_key_exists($name, $this->_attributes) ? $this->_attributes[$name] : $value;
    }
  }

  public function header($name, $value = NULL) {
    if($value !== NULL) {
      $this->_headers[$name] = $value;
    } else {
      return array_key_exists($name, $this->_headers) ? $this->_headers[$name] : $value;
    }
  }
  
  public function header404() {
    $this->header('#HTTP/', $_SERVER["SERVER_PROTOCOL"].' 404 Not Found');
    $this->header('Status', '404 Not Found');
  }

  public function headers() {
    $headers = array();
    foreach($this->_headers as $name => $value) {
      if($name == '#HTTP/') {
        // Special header for 404 errors
        $headers[] = $value;
      } else {
        // Normal header
        $headers[] = $name.': '.$value;
      }
    }
    return $headers;
  }
  
  public function data($data = NULL) {
    if($data !== NULL) {
      $this->_data .= $data;      
    } else {
      return $this->_data;      
    }
  }
}

class ufController {
  private $_buffer_ref_count;
  private $_call_stack;

  private function _load_view($view) {
    $controller = ufController::str_to_controller(substr(get_class($this), 0, -10));

    // include the view
    uf_include_view($this, UF_BASE.'app/modules/'.$controller.'/view/'.$view.'.php');
  }

  private function _push_call_stack_frame($request, $response, $options) {
    array_push(
      $this->_call_stack,
      array(
        'request' => $request,
        'response' => $response,
        'options' => $options));    
  }

  private function _pop_call_stack_frame() {
    array_pop($this->_call_stack);    
  }

  public function __construct() {
    $this->_call_stack = array();
    $this->_buffer_ref_count = 0;
  }

  public function __destruct() {
    while($this->_buffer_ref_count) {
      $this->end_buffering();
    }
  }

  public function request() {
    return $this->_call_stack[count($this->_call_stack) - 1]['request'];
  }
  
  public function response() {
    return $this->_call_stack[count($this->_call_stack) - 1]['response'];
  }

  public function start_buffering() {
    $this->_buffer_ref_count++;
    ob_start();
  }

  public function end_buffering() {
    $this->_buffer_ref_count--;
    $this->response()->data(ob_get_contents());
    ob_end_clean();
  }
  
  public function option($name, $default_value = NULL) {
    return array_key_exists($name, $this->_call_stack[count($this->_call_stack) - 1]['options']) ? $this->_call_stack[count($this->_call_stack) - 1]['options'] : $default_value;
  }

  public function execute_action($action, $request, $response, $options = NULL) {
    // 404 action?
    if(!method_exists($this, $action)) {
      return FALSE;
    }

    $this->_push_call_stack_frame($request, $response, $options !== NULL ? $options : array());

    // start buffering?
    if($this->option('enable_buffering')) {
      $this->start_buffering();
    }

    // default action?
    if(empty($action)) {
      $action = 'index';
    }
    
    $action = ufController::str_to_controller($action);

    // execute action
    $view = call_user_func(array($this, $action));

    // default view?
    if($view === NULL) {
      $view = $action;
    }

    // no view?
    if($view !== FALSE) {
      $this->_load_view($view);
    }

    // stop buffering?
    if($this->option('enable_buffering')) {
      $this->end_buffering();
    }

    $this->_pop_call_stack_frame();

    return TRUE;
  }

  public function index() {
  }

  public function error404() {
    $this->response()->header404();
    $this->response()->data('Error 404 - Page not found');
    return FALSE;
  }
  
  static public function str_to_controller($str) {
    $f = array('ö','å','ä','ø','æ','ñ','ü','.',',',';','-','–','/',' ');
    $t = array('o','a','a','o','a','n','u','_','_','_','_','_','_','_');
    $result = preg_replace('/[^\d\w_-]/', '', str_replace($f, $t, $str));
    return preg_replace('/_+/', '_', $result);
  }
}

class Application {
  public function run() {
    $request = new ufHTTPRequest;
    $response = new ufResponse;

    $front_controller = new frontController;
    $front_controller->execute_action('index', $request, $response, array('enable_buffering' => TRUE));

    // Send headers
    foreach($response->headers() as $header) {
      header($header);
    }

    // Send data
    echo $response->data();
    
    $response = NULL;
    $request = NULL;
  }
}

function uf_include_view($uf_controller, $uf_view) {
  // view variables
  $uf_request  = $uf_controller->request();
  $uf_response = $uf_controller->response();
  extract(get_object_vars($uf_controller));
  require($uf_view);
}

function __autoload($class) {
  if(substr($class, -10) === 'Controller') {
    $controller = ufController::str_to_controller(substr($class, 0, -10));
    @include_once(UF_BASE.'app/modules/'.$controller.'/controller.php');
  }
}

$application = new Application();
$application->run();
$application = NULL;

?>