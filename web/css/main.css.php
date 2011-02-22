<?php
define(UF_BASE, realpath(dirname(__FILE__).'/../..'));
header('Content-Type: text/css');
require_once(UF_BASE.'/config/config.php');
global $uf_config;
$css_file = UF_BASE.'/cache/baked.css';
if($uf_config['always_bake'] || !file_exists($css_file))
{
  require_once(UF_BASE.'/core/baker.php');
  echo uf_baker::bake('css');
}
else
{
  echo file_get_contents($css_file);  
}
?>
