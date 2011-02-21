<pre>
<?php
define(UF_BASE, realpath(dirname(__FILE__).'/..'));
require_once(UF_BASE.'/core/baker.php');
echo 'baking...'."\n";
uf_baker::bake();
echo 'done.'."\n";
?>
</pre>