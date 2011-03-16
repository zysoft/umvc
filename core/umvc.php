<?
error_reporting(E_ALL);

//include_once(UF_BASE.'/core/debug.php');
require_once(UF_BASE.'/core/application.php');
require_once(UF_BASE.'/core/baker.php');
require_once(UF_BASE.'/core/session.php');
require_once(UF_BASE.'/core/response.php');
require_once(UF_BASE.'/core/request.php');
require_once(UF_BASE.'/core/httprequest.php');
require_once(UF_BASE.'/core/controller.php');

if(uf_application::config('load_propel'))
{
  // Initialize Propel with the runtime configuration
  require_once UF_BASE.'/propel/propel-1.5.6/runtime/lib/Propel.php';

  Propel::init(uf_application::app_dir().'/data/build/conf/umvc-conf.php');

  // Add the generated 'classes' directory to the include path
  set_include_path('/path/to/bookstore/build/classes'.PATH_SEPARATOR.get_include_path());  
}

?>