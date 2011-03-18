<?
define('UF_BASE',realpath(dirname(__FILE__).'/../..'));
require_once(UF_BASE.'/core/umvc.php');
uf_application::init();

header('Content-Type: text/javascript');

$js_file = UF_BASE.'/web/data/baker'.uf_application::config('app_dir').'/js/baked.js';
if(uf_application::config('always_bake') || !file_exists($js_file))
{
  uf_baker::bake('js');
}
echo @file_get_contents($js_file)."\n";

@include_once(UF_BASE.'/cache/baker'.uf_application::config('app_dir').'/js/baked.js.php');
?>
