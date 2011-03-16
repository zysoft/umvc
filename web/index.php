<?
define('UF_BASE',realpath(dirname(__FILE__).'/..'));
require_once(UF_BASE.'/core/umvc.php');
uf_application::run();
die('<pre>'.print_r(get_defined_vars(),1).'</pre>');
?>