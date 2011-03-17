<?

class uf_application
{  
  private static $_is_initialized;
  private static $_app_sites_host_dir;
  private static $_config;  
  
  public static function init()
  {
    if(!isset(self::$_is_initialized))
    {
      self::$_is_initialized = TRUE;
      
      // LOAD CONFIG FILE
      require_once(UF_BASE.'/config/config.php');
      self::$_config =& $uf_config;

      $n = str_replace('www.','',$_SERVER['SERVER_NAME'],$c);
      $dirb = uf_application::app_dir().'/sites/hosts/';
      if (!is_dir($dirb.$n))
      {
        self::$_app_sites_host_dir = $dirb.'FALLBACK';
      }
      else
        self::$_app_sites_host_dir = $dirb.$n;

      if(uf_application::config('always_bake'))
      {
        uf_baker::bake_all();
      }          
    }
  }
  
  public static function run()
  {
    self::init();
    
    // NORMAL ROUTING
    $routing_file = UF_BASE.'/cache/baker'.uf_application::config('app_dir').'/routing/baked.routing.php';
    if(uf_application::config('always_bake') || !file_exists($routing_file))
    {
      uf_baker::bake('routing');
    }
    @include_once($routing_file);

    // PRE ROUTING
    $pre_routing_file = UF_BASE.'/cache/baker'.uf_application::config('app_dir').'/routing/baked.pre.routing.php';
    if(uf_application::config('always_bake') || !file_exists($pre_routing_file))
    {
      uf_baker::bake('pre_routing');
    }
    @include_once($pre_routing_file);

    // POST ROUTING
    $post_routing_file = UF_BASE.'/cachebaker/'.uf_application::config('app_dir').'/routing/baked.post.routing.php';
    if(uf_application::config('always_bake') || !file_exists($post_routing_file))
    {
      uf_baker::bake('post_routing');
    }
    @include_once($post_routing_file);

    $request  = new uf_http_request;
    $response = new uf_response;

    /// CREATE AND EXECUTE CONTROLLER
    uf_controller::execute_base($request,$response,array('enable_buffering' => TRUE));

    $response = NULL;
    $request  = NULL;
  }

  public static function clear_log()
  {
    if(self::config('log'))
    {
      file_put_contents(UF_BASE.'/log/log.txt', '');
    }
  }

  public static function log($message)
  {
    if(self::config('log'))
    {
      date_default_timezone_set('UTC'); //temp hack
      $fp = fopen(UF_BASE.'/log/log.txt', 'a');
      fwrite($fp, date('Y-m-d h:i:s').' '.$message."\n");
      fclose($fp);      
    }
  }

  public static function language()
  {
    return uf_session::get('language',uf_application::config('language','en_US'));
  }
  
  public static function app_dir()
  {
    return UF_BASE.self::config('app_dir');
  }
  public static function app_sites_host_dir()
  {
    return self::$_app_sites_host_dir;
  }
  
  public static function config($name,$default_value = '')
  {
    return isset(self::$_config[$name]) ? self::$_config[$name] : $default_value;
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