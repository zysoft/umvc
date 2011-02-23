<?

require_once(UF_BASE.'/config/config.php');

class uf_application
{  
  private static $_routing_function;

  public static function run()
  {    
    // ROUTING
    global $uf_config;
    $routing_file = UF_BASE.'/cache/baked.routing.php';
    if($uf_config['always_bake'] || !file_exists($routing_file))
    {
      require_once(UF_BASE.'/core/baker.php');
      uf_baker::bake('routing');
      require_once($routing_file);
    }
    else
    {
      require_once($routing_file);
    }

    $request  = new uf_http_request;
    $response = new uf_response;

    /// FRONT CONTROLLER
    $controller = new uf_controller();
    $controller->execute_front('index',$request,$response,array('enable_buffering' => TRUE));

    $response = NULL;
    $request  = NULL;
  }
  
  private static function _set_routing_function($routing_function)
  {
    self::$_routing_function = $routing_function;
  }
  
  public static function apply_routing($uri)
  {
    $routing_function = self::$_routing_function;
    return $routing_function !== NULL ? $routing_function($uri) : $uri;
  }
}

?>