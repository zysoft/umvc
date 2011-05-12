<?
define('UF_BASE',realpath(dirname(__FILE__).'/../..'));
require_once(UF_BASE.'/core/umvc.php');
uf_application::init();

header('Content-Type: text/css');
// seconds, minutes, hours, days
$expires = 60*60*24*14;
header("Pragma: public");
header("Cache-Control: public, max-age=".$expires);
header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
if(!ob_start("ob_gzhandler")) ob_start();
$css_file = UF_BASE.'/web/data/baker'.uf_application::config('app_dir').'/'.uf_application::host().'/css/baked.css';
if(uf_application::config('always_bake') || !file_exists($css_file))
{
  uf_baker::bake('css');
}
echo @file_get_contents($css_file)."\n";

@include_once(UF_BASE.'/cache/baker'.uf_application::config('app_dir').'/'.uf_application::host().'/css/baked.css.php');
?>
