<h2>This is the error view for the error id 5000.</h2>

<h3>This file is in <i>app_demo/errors/v_5000.php</i> and as you can see it's a normal view</h3>

<p>An error can be generated in the before_action() method as well - if you want to protect a module requiring login etc. you can simply override this function and look for a valid session.</p>

<h4>Dump of the views variable environment:</h4>
<p></p>
<pre><?=print_r(get_defined_vars(),TRUE)?></pre>