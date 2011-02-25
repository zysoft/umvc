<?
define(UF_BASE,realpath(dirname(__FILE__).'/../..'));
require_once(UF_BASE.'/core/umvc.php');

header('Content-Type: text/css');

require_once('default.css.php');

$dir = UF_BASE.'/web/data'.uf_application::config('app_dir').'/baker/css';
if(!is_dir($dir))
{
  mkdir($dir,0777,TRUE);
}
$css_file = $dir.'/baked.css';
if(uf_application::config('always_bake') || !file_exists($css_file))
{
  echo uf_baker::bake('css');
}
else
{
  echo file_get_contents($css_file);  
}
?>
