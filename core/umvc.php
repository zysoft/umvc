<?
error_reporting(E_ALL);

include('response.php');
include('request.php');
include('httprequest.php');
include('controller.php');
include('application.php');


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