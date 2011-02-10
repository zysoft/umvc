<?

class frontController extends ufController {
  public function index() {
    $controller_type = ufController::str_to_controller($this->request()->parameter('_controller', 'index'));
    $action = ufController::str_to_controller($this->request()->parameter('_action', 'index'));

    $this->mainmenu = $controller_type;

    $controller_class = $controller_type.'Controller';
    $controller = new $controller_class();

    $response = new ufResponse;
    $controller->execute_action($action, $this->request(), $response, array('enable_buffering' => TRUE));
    $this->content = $response->data();    

    $template = $controller->response()->attribute('template');
    header('Content-Type: '.$response->content_type());
    $controller = NULL;
    return $template !== NULL ? $template : 'index';
  }
}

?>