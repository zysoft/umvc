<?php
define(UF_BASE, realpath(dirname(__FILE__).'/../..'));
header('Content-Type: text/javascript');
$js_file = UF_BASE.'/cache/baked.js';
if(!file_exists($js_file))
{
  require_once(UF_BASE.'/core/baker.php');
  uf_baker::bake();
}
echo file_get_contents($js_file);  
?>
