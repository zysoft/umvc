<?
error_reporting(E_ALL);

include('response.php');
include('request.php');
include('httprequest.php');
include('controller.php');
include('application.php');

require_once UF_BASE.'/propel/propel-1.5.6/runtime/lib/Propel.php';

// Initialize Propel with the runtime configuration
Propel::init(UF_BASE."/app/data/build/conf/umvc-conf.php");

// Add the generated 'classes' directory to the include path
set_include_path("/path/to/bookstore/build/classes" . PATH_SEPARATOR . get_include_path());


function __autoload($class) {
  if(substr($class, -10) === 'Controller') {
    $controller = ufController::str_to_controller(substr($class, 0, -10));
    @include_once(UF_BASE.'/app/modules/'.$controller.'/c_'.$controller.'.php');
  }
}

$application = new Application();
$application->run();
$application = NULL;

?>