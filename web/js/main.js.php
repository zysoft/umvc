<?
define(UF_BASE,realpath(dirname(__FILE__).'/../..'));
require_once(UF_BASE.'/core/umvc.php');

header('Content-Type: text/javascript');

$dir = UF_BASE.'/web/data'.uf_application::config('app_dir').'/baker/js';
if(!is_dir($dir))
{
  mkdir($dir,0777,TRUE);
}
$js_file = $dir.'/baked.js';
if(uf_application::config('always_bake') || !file_exists($js_file))
{
  echo uf_baker::bake('js');
}
else
{
  echo file_get_contents($js_file);  
}
?>
