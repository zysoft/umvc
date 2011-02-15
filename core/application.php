<?

class Application
{
  public function run()
  {
    $request = new ufHTTPRequest;
    $response = new ufResponse;

    /// FRONT CONTROLLER
    $controller = new ufController();
    $controller->execute_front('index',$request,$response,array('enable_buffering' => TRUE));

    $response = NULL;
    $request = NULL;
  }
}
