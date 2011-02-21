<?php
define(UF_BASE, realpath(dirname(__FILE__).'/../..'));
header('Content-Type: text/css');
$css_file = UF_BASE.'/cache/baked.css';
if(!file_exists($css_file))
{
  echo '*';
  require_once(UF_BASE.'/core/baker.php');
  uf_baker::bake();
}
echo file_get_contents($css_file);  
?>