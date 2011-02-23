<?
define(UF_BASE,realpath(dirname(__FILE__).'/..'));
require_once(UF_BASE.'/core/baker.php');
uf_baker::bake_all();
?>