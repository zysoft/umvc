<?

class uf_application
{
  public function run()
  {
    $request = new uf_http_request;
    $response = new uf_response;

    /// FRONT CONTROLLER
    $controller = new uf_controller();
    $controller->execute_front('index',$request,$response,array('enable_buffering' => TRUE));

    $response = NULL;
    $request = NULL;
  }
}
