<?
define('UF_BASE',realpath(dirname(__FILE__).'/../..'));
require_once(UF_BASE.'/core/umvc.php');
uf_application::init();

header('Content-Type: text/css');

$css_file = UF_BASE.'/web/data/baker'.uf_application::app_name().'/'.uf_application::host().'/css/baked.css';
if(uf_application::get_config('always_bake') || !file_exists($css_file))
{
  uf_baker::bake('css');
}
echo @file_get_contents($css_file)."\n";

@include_once(UF_BASE.'/cache/baker'.uf_application::app_name().'/'.uf_application::host().'/css/baked.css.php');
?>
