<?php
define(UF_BASE, realpath(dirname(__FILE__).'/../..'));
header('Content-Type: text/javascript');
echo file_get_contents(UF_BASE.'/cache/baked.js');
?>