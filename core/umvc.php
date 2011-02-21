<?
error_reporting(E_ALL);

include('response.php');
include('request.php');
include('httprequest.php');
include('controller.php');
include('application.php');

require_once UF_BASE.'/propel/propel-1.5.6/runtime/lib/Propel.php';

// Initialize Propel with the runtime configuration
#Propel::init(UF_BASE."/app/data/build/conf/umvc-conf.php"); // temporary removed, because i'm to lazy to config propel /David

// Add the generated 'classes' directory to the include path
set_include_path("/path/to/bookstore/build/classes" . PATH_SEPARATOR . get_include_path());

# register our controller factory
spl_autoload_register('uf_controller::autoload_controller');

$application = new uf_application();
$application->run();
$application = NULL;

?>
