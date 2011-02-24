<?
define(UF_BASE,realpath(dirname(__FILE__).'/../..'));

header('Content-Type: text/javascript');

require_once(UF_BASE.'/config/config.php');

$js_file = UF_BASE.'/cache/baked.js';
if(uf_application::config('always_bake') || !file_exists($js_file))
{
  require_once(UF_BASE.'/core/baker.php');
  echo uf_baker::bake('js');
}
else
{
  echo file_get_contents($js_file);  
}
?>
