<?php

error_reporting(E_ALL);

require_once(UF_BASE.'/config/config.php');
require_once('response.php');
require_once('request.php');
require_once('httprequest.php');
require_once('controller.php');
require_once('application.php');
require_once('baker.php');

// Initialize Propel with the runtime configuration
if($uf_config['load_propel'])
{
  require_once UF_BASE.'/propel/propel-1.5.6/runtime/lib/Propel.php';

  Propel::init(UF_BASE."/app/data/build/conf/umvc-conf.php"); // temporary removed, because i'm to lazy to config propel /David

  // Add the generated 'classes' directory to the include path
  set_include_path("/path/to/bookstore/build/classes" . PATH_SEPARATOR . get_include_path());  
}

# register our controller factory
spl_autoload_register('uf_controller::autoload_controller');

uf_application::run();

?>
