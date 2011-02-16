<?

class ufController {
  // PRIVATE DATA
  private $_buffer_ref_count;
  private $_call_stack;

  // PRIVATE METHODS
  static private function include_view($uf_controller, $uf_view) {
    // view variables
    $uf_request  = $uf_controller->request();
    $uf_response = $uf_controller->response();
    extract(get_object_vars($uf_controller));
    require($uf_view);
  }

  private function _load_view($view) {
    $controller = ufController::str_to_controller(substr(get_class($this), 0, -10));
    // include the view
    ufController::include_view($this, UF_BASE.'/app/modules/'.$controller.'/view/v_'.$view.'.php');
  }
  private function _load_front($view) {
    $controller = ufController::str_to_controller(substr(get_class($this), 0, -10));
    // include the view
    ufController::include_view($this, UF_BASE.'/app/front/'.$view.'.php');
  }

  private function _push_call_stack_frame($caller, $request, $response, $options) {
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

  private function _pop_call_stack_frame() {
    array_pop($this->_call_stack);
  }

  // PROTECTED METHODS
  
  protected function before_action() {}
  protected function after_action() {}

  // PUBLIC METHODS

  static public function str_to_controller($str) {
    $f = array('ö','å','ä','ø','æ','ñ','ü','.',',',';','-','–','/',' ');
    $t = array('o','a','a','o','a','n','u','_','_','_','_','_','_','_');
    $result = preg_replace('/[^\d\w_-]/', '', str_replace($f, $t, $str));
    return preg_replace('/_+/', '_', $result);
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

  public function caller() {
    if (!count($this->_call_stack)) return NULL;
    return $this->_call_stack[count($this->_call_stack) - 1]['caller'];
  }

  public function request() {
    if (!count($this->_call_stack)) return NULL;
    return $this->_call_stack[count($this->_call_stack) - 1]['request'];
  }

  public function response() {
    if (!count($this->_call_stack)) return NULL;
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

  public function execute_front($action, $request, $response, $options = NULL)
  {
    $this->_push_call_stack_frame($this, $request, $response, $options !== NULL ? $options : array());
    $controller = ufController::str_to_controller($request->controller());
    $action     = ufController::str_to_controller($request->action());

    $controller_class = $controller.'Controller';

    if(class_exists($controller_class)) {
      $controller = new $controller_class;
      if($controller->execute_action($this, $action, $request, $response, array('enable_buffering' => TRUE)) === FALSE)
      {
        $this->_error(404);
      }
      $controller = NULL;
    } else {
      $this->_error(404);
    }

    $this->content = $response->data();

    // Send headers
    foreach($response->headers() as $header) {
      header($header);
    }
    
    $this->_load_front($response->attribute('template'));
    $this->_pop_call_stack_frame();
  }

  public function execute_action($caller,$action,$request,&$response,$options = NULL) {
    // 404 action?
    // handle  www.foo.com/index/
    if ($action == '') $action = 'index';
        echo 'xxx'.$action;

    // handle nonexistent controller functions
    if(!method_exists($this, $action)) {
      if (!method_exists($this,'error'))
      {
        return FALSE;
      } else
      {
        $action = 'error';
      }
    }

    $this->_push_call_stack_frame($caller, $request, $response, $options !== NULL ? $options : array());

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
    $this->before_action();
    $view = call_user_func(array($this, $action));
    $this->after_action();

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

  public function index() {}

  public function _error($code)
  {
    ob_start();
      ufController::include_view($this, UF_BASE.'/app/error/'.$code.'.php');
      $this->response()->data(ob_get_contents());
    ob_end_clean();
    $this->response()->header404();
    return FALSE;
  }
}

