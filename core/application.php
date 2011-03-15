<?

require_once(UF_BASE.'/config/config.php');

class uf_application
{  
  private static $_routing_function;

  public static function run()
  {
    $n = str_replace('www.','',$_SERVER['SERVER_NAME'],$c);
    $dirb = UF_BASE.uf_application::config('app_dir').'/sites/hosts/';
    global $uf_global;
    if (!is_dir($dirb.$n))
    {
      $uf_global['app_sites_host_dir'] = $dirb.'FALLBACK/';
    }
    else
      $uf_global['app_sites_host_dir'] = $dirb.$n.'/';
    
    // ROUTING
    if(!is_dir(UF_BASE.'/cache'.uf_application::config('app_dir').'/baker/routing'))
    {
      mkdir(UF_BASE.'/cache'.uf_application::config('app_dir').'/baker/routing',0777,TRUE);
    }
    $routing_file = UF_BASE.'/cache'.uf_application::config('app_dir').'/baker/routing/baked.routing.php';
    $pre_routing_file = UF_BASE.'/cache'.uf_application::config('app_dir').'/baker/routing/baked.pre.routing.php';
    $post_routing_file = UF_BASE.'/cache'.uf_application::config('app_dir').'/baker/routing/baked.post.routing.php';

    // PRE ROUTING
    if(uf_application::config('always_bake') || !file_exists($routing_file))
    {
      uf_baker::bake('routing');
    }
    @include_once($routing_file);

    // NORMAL ROUTING
    if(uf_application::config('always_bake') || !file_exists($pre_routing_file))
    {
      uf_baker::bake('pre_routing');
    }
    @include_once($pre_routing_file);

    // POST ROUTING
    if(uf_application::config('always_bake') || !file_exists($post_routing_file))
    {
      uf_baker::bake('post_routing');
    }
    @include_once($post_routing_file);

    $request  = new uf_http_request;
    $response = new uf_response;

    /// BASE CONTROLLER
    uf_controller::execute_base($request,$response,array('enable_buffering' => TRUE));

    $response = NULL;
    $request  = NULL;
  }
  
  public static function app_sites_host_dir()
  {
    global $uf_global;
    return $uf_global['app_sites_host_dir'];
  }
  
  public static function config($name,$default_value = '')
  {
    global $uf_config;
    return isset($uf_config[$name]) ? $uf_config[$name] : $default_value;
  }
  
  public static function apply_routing($uri)
  {
    if(function_exists('uf_internal_pre_routing_function'))
    {
      $uri = uf_internal_pre_routing_function($uri);      
    }
    if(function_exists('uf_internal_routing_function'))
    {
      $uri = uf_internal_routing_function($uri);
    }
    if(function_exists('uf_internal_post_routing_function'))
    {
      $uri = uf_internal_post_routing_function($uri);
    }
    return $uri;
  }
}

?>