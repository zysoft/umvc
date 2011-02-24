<?

error_reporting(E_ALL);

require_once(UF_BASE.'/config/config.php');
require_once('response.php');
require_once('request.php');
require_once('httprequest.php');
require_once('controller.php');
require_once('application.php');
require_once('baker.php');

if(uf_application::config('load_propel'))
{
  // Initialize Propel with the runtime configuration
  require_once UF_BASE.'/propel/propel-1.5.6/runtime/lib/Propel.php';

  Propel::init(UF_BASE.uf_application::config('app_dir').'/data/build/conf/umvc-conf.php');

  // Add the generated 'classes' directory to the include path
  set_include_path('/path/to/bookstore/build/classes'.PATH_SEPARATOR.get_include_path());  
}

# register our controller factory
spl_autoload_register('uf_controller::autoload_controller');

uf_application::run();

?>
