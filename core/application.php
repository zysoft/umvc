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
      $dirb = self::app_dir().'/sites/hosts/';
      if (!is_dir($dirb.$n))
      {
        self::$_app_sites_host_dir = $dirb.'FALLBACK';
      }
      else
        self::$_app_sites_host_dir = $dirb.$n;

      if(self::config('always_bake'))
      {
        uf_baker::bake_all();
      }
      
      if(uf_application::config('load_propel'))
      {
        // Initialize Propel with the runtime configuration
        require_once UF_BASE.'/propel/propel-1.5.6/runtime/lib/logger/BasicLogger.php';
        require_once UF_BASE.'/core/propel_logger.php';
        $logger = new MyLogger();
        require_once UF_BASE.'/propel/propel-1.5.6/runtime/lib/Propel.php';
        Propel::setLogger($logger);

        $propel_initial_conf = include(uf_application::propel_app_dir().'/data/build/conf/umvc-conf.php');
        $propel_initial_conf['datasources']['umvc']['connection'] = self::config('propel_db');
        Propel::setConfiguration($propel_initial_conf);
        Propel::initialize();
        
        // Add the generated 'classes' directory to the include path
        set_include_path(uf_application::propel_app_dir().'/data/build/classes'.PATH_SEPARATOR.get_include_path());
      }
    }
  }
  
  public static function run()
  {
    self::init();
    
    // PRE ROUTING
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
    return uf_session::get('language',self::config('language','en_US'));
  }
  
  public static function app_dir()
  {
    return UF_BASE.self::config('app_dir');
  }
  public static function propel_app_dir()
  {
    return UF_BASE.self::config('propel_app_dir');
  }
  public static function app_sites_host_dir()
  {
    return self::$_app_sites_host_dir;
  }

  public static function host()
  {
    return substr(strrchr(self::$_app_sites_host_dir, '/hosts/'),1);
  }
  
  public static function config($name,$default_value = '')
  {
    return isset(self::$_config[$name]) ? self::$_config[$name] : $default_value;
  }
  
  public static function controller_exists($controller)
  {
    $file = 
      uf_application::app_sites_host_dir().
      '/modules/'.
      $controller.
      '/c_'.$controller.'.php';
      
    if(file_exists($file))
    {
      return TRUE;
    }
    
    $file = 
      uf_application::app_dir().
      '/modules/'.
      $controller.
      '/c_'.$controller.'.php';
      
    return file_exists($file);
  }

  public static function is_global_controller($controller)
  {
    return file_exists(
      uf_application::app_sites_host_dir().
      '/modules/'.
      $controller.
      '/c_'.$controller.'.php');
  }
}

?>
