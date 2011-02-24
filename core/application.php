<?

require_once(UF_BASE.'/config/config.php');

class uf_application
{  
  private static $_routing_function;

  public static function run()
  {    
    // ROUTING
    $routing_file = UF_BASE.'/cache/baked.routing.php';
    $pre_routing_file = UF_BASE.'/cache/baked.pre.routing.php';
    $post_routing_file = UF_BASE.'/cache/baked.post.routing.php';

    // PRE ROUTING
    if(uf_application::config('always_bake') || !file_exists($routing_file))
    {
      uf_baker::bake('routing');
    }
    require_once($routing_file);

    // NORMAL ROUTING
    if(uf_application::config('always_bake') || !file_exists($pre_routing_file))
    {
      uf_baker::bake('pre_routing');
    }
    require_once($pre_routing_file);

    // POST ROUTING
    if(uf_application::config('always_bake') || !file_exists($post_routing_file))
    {
      uf_baker::bake('post_routing');
    }
    require_once($post_routing_file);

    $request  = new uf_http_request;
    $response = new uf_response;

    /// FRONT CONTROLLER
    $controller = new uf_controller();
    $controller->execute_front('index',$request,$response,array('enable_buffering' => TRUE));

    $response = NULL;
    $request  = NULL;
  }
  
  public static function config($name,$default_value = '')
  {
    global $uf_config;
    return isset($uf_config[$name]) ? $uf_config[$name] : $default_value;
  }
  
  public static function apply_routing($uri)
  {
    $uri = uf_internal_pre_routing_function($uri);
    $uri = uf_internal_routing_function($uri);
    $uri = uf_internal_post_routing_function($uri);
    return $uri;
  }
}

?>