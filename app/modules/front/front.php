<?php

class frontController extends ufController {
  public function index() {
    $response = new ufResponse;

    $controller_type = ufController::str_to_controller($this->request()->controller());
    $action = ufController::str_to_controller($this->request()->action());

    $controller_class = $controller_type.'Controller';    

    if(class_exists($controller_class)) {
      $this->mainmenu = $controller_type;
      $controller = new $controller_class;        
      if($controller->execute_action($action, $this->request(), $response, array('enable_buffering' => TRUE)) === FALSE) {
        $this->execute_action('error404', $this->request(), $response);        
      }
      $controller = NULL;
    } else {
      $this->execute_action('error404', $this->request(), $response);
    }

    $this->content = $response->data();    
    return $response->attribute('template');
  }

  function error404() {
    $this->response()->header404();
    $this->response()->data('<h1>Error 404 - Page not found</h1>');
    $this->response()->data('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>');
    return FALSE;
  }

}

?>