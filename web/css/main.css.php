<?
define(UF_BASE,realpath(dirname(__FILE__).'/../..'));
require_once(UF_BASE.'/core/umvc.php');

header('Content-Type: text/css');

require_once('default.css.php');

$css_file = UF_BASE.'/cache/baked.css';
if(uf_application::config('always_bake') || !file_exists($css_file))
{
  require_once(UF_BASE.'/core/baker.php');
  echo uf_baker::bake('css');
}
else
{
  echo file_get_contents($css_file);  
}
?>
