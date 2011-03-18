<?
define('UF_BASE',realpath(dirname(__FILE__).'/../..'));
require_once(UF_BASE.'/core/umvc.php');
uf_application::init();

header('Content-Type: text/css');

require_once('default.css.php');

$css_file = UF_BASE.'/web/data/baker'.uf_application::config('app_dir').'/css/baked.css';
if(uf_application::config('always_bake') || !file_exists($css_file))
{
  uf_baker::bake('css');
}
echo @file_get_contents($css_file)."\n";

@include_once(UF_BASE.'/cache'.uf_application::config('app_dir').'/baker/css/baked.css.php');
?>
