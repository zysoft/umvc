


<h2>Test case (backwards) for forwards parameter translation:</h2>
<a href="/exempel/sprak/antal-talare/10000000?foo=bar">Click here to load a fully language-dependent URL, module and action translateion taken care of in the routing and in the per-action parameter translation method in the examples controller</a>
<br/><br/><br/><br/><br/>

<? if ($num_speakers != '') { ?>
<h2>Test case results for parameter translation</h2>
The expected output is: <font style="color:red">"Swedish is spoken by 10000000 people and foo is equal to bar!"</font><br />
A combination of routing (see app_demo/modules/examples/route/r_5000.php) and forwards parameter translation (see c_examples.php, the method language_translate_param() )<br />
<h1>Swedish is spoken by <?=$num_speakers?> people and foo is equal to <?=$foo?>!</h1>
<hr />
<hr />
<? } ?>

Here follows a few tests of the various methods to find various translated parts of a URL, translated into a specific language:<br />

<h2>Module translation (backwards - from code to locale)</h2>
<b>Used when constructing a href/link to another module</b><br />
Controller "examples" in swedish: <?=uf_controller::view_lang_get_module_name('examples','sv-se')?><br />
<ul>
  <li>the call was: uf_controller::view_lang_get_module_name('examples','sv-se')
  <li>defined in app_demo/modules/examples/am_examples.php - - "am" is short for "alias module"
</ul>
<br />
<hr />
<br />
<h2>Action translation (backwards - from code to locale)</h2>
Controller "examples", action "language" in swedish: <?=uf_controller::view_lang_get_action_name('language','examples','sv-se')?><br />
<ul>
  <li>the call was: uf_controller::view_lang_get_action_name('language','examples','sv-se')
  <li>note that the language file does not use åäö, since it's designed for a URL, thus should be no need for running slow conversion search / replace functions on this string; like "seo_cleanup"
  <li>defined in app_demo/modules/examples/aa_examples.php - - "aa" is short for "alias action"
</ul>

<br />
<hr />
<br />
<h2>Parameter translation (backwards - from code to locale)</h2>
Controller "examples", action "language", parameter "num-speakers" in swedish: <?=uf_controller::view_lang_get_parameter_name('num-speakers','language','examples','sv-se')?><br />
<ul>
  <li>the call was: uf_controller::view_lang_get_parameter_name('num-speakers','language','examples','sv-se')
  <li>note that the language file does not use åäö, since it's designed for a URL, thus should be no need for running slow conversion search / replace functions on this string; like "seo_cleanup")
  <li>defined in app_demo/modules/examples/aa_examples.php - - "ap" is short for "alias parameter"
</ul>
<br/>
<hr />
<br/>
Controller "examples", action "language", parameter "muubar" in swedish: <?=uf_controller::view_lang_get_parameter_name('muubar','language','examples','sv-se')?><br />
<ul>
<li>the call was: uf_controller::view_lang_get_parameter_name('muubar','language','examples','sv-se')
<li>note that this string is not available in the language file, so it just returns the input value
<li>note that the language file does not use åäö, since it's designed for a URL, thus should be no need for running slow conversion search / replace functions on this string; like "seo_cleanup")
<li>defined in app_demo/modules/examples/aa_examples.php - - "ap" is short for "alias parameter"
</ul>

<br/>
<hr />
<br/>

