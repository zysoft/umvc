<?php

class pfRequest {
  private $_parameters;

  public function parameters($parameters = NULL) {
    if($parameters !== NULL) {
      $this->_parameters = $parameters;
    } else {
      return $this->_parameters;
    }
  }
  
  public function parameter($name, $default_value = NULL) {
    return array_key_exists($name, $this->_parameters) ? $this->_parameters[$name] : $default_value;
  }
}

class pfResponse {
  private $_attributes;
  private $_content_type;
  private $_data;
  
  public function __construct() {
    $this->_attributes = array();
    $this->content_type('text/html');
    $this->_data = '';
  }

  public function attribute($name, $value = NULL) {
    if($value !== NULL) {
      $this->_attributes[$name] = $value;
    } else {
      return array_key_exists($name, $this->_attributes) ? $this->_attributes[$name] : $value;
    }
  }

  
  public function content_type($type = NULL) {
    if($type !== NULL) {
      $this->_content_type = $type;      
    } else {
      return $this->_content_type;
    }
  }
  
  public function data($data = NULL) {
    if($data !== NULL) {
      $this->_data .= $data;      
    } else {
      return $this->_data;      
    }
  }
}

class pfController {
  private $_buffer_ref_count;

  private $_request;
  private $_response;
  private $_options;
  
  private function _load_view($view) {
    $controller = pfController::str_to_controller(substr(get_class($this), 0, -10));

    // include the view
    pf_include_view($this, 'application/controller/'.$controller.'/view/'.$view.'.php');
  }

  public function __construct() {
    $this->_buffer_ref_count = 0;
  }

  public function __destruct() {
    while($this->_buffer_ref_count) {
      $this->end_buffering();
    }
  }

  public function request() {
    return $this->_request;
  }
  
  public function response() {
    return $this->_response;
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
    return array_key_exists($name, $this->_options) ? $this->_options[$name] : $default_value;
  }

  public function execute_action($action, $request, $response, $options = NULL) {
    $this->_options = $options !== NULL ? $options : array();
    $this->_response = $response;
    $this->_request = $request;

    // start buffering?
    if($this->option('enable_buffering')) {
      $this->start_buffering();
    }

    // default action?
    if(empty($action)) {
      $action = 'index';
    }

    // 404 action?
    if(!method_exists($this, $action)) {
      $action = 'error404';
    }
    
    $action = pfController::str_to_controller($action);

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
  }

  public function index() {
  }

  public function error404() {
    echo '404';
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
    $input = array_merge($_GET, $_POST);

    // cleanup
    //unset($input['_controller']);
    //unset($input['_action']);
    unset($_GET);
    unset($_POST);

    $request = new pfRequest;
    $request->parameters($input);
    $response = new pfResponse;

    $front_controller = new frontController;
    $front_controller->execute_action('index', $request, $response, array('enable_buffering' => FALSE));
    echo $response->data();
    
    $response = NULL;
    $request = NULL;
  }
}

function pf_include_view($pf_controller, $pf_view) {
  // view variables
  $pf_request  = $pf_controller->request();
  $pf_response = $pf_controller->response();
  extract(get_object_vars($pf_controller));
  require($pf_view);
}

function __autoload($class) {
  if(substr($class, -10) === 'Controller') {
    $controller = pfController::str_to_controller(substr($class, 0, -10));
    require_once('application/controller/'.$controller.'/'.$controller.'.php');    
  }
}

$application = new Application();
$application->run();
$application = NULL;

?>