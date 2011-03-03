<?='<?'?>xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sv">
<head>
  <title><?=@$title?></title>	
  <meta name="description" content="<?=@$meta_description?>" />
  <meta name="keywords" content="<?=@$meta_keywords?>" />
  <link rel="alternate" type="application/rss+xml" title="" href="/rss" />
  <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css.php" />
  <script src="/js/main.js.php" type="text/javascript" charset="utf-8"></script>
</head>
<body>
  <div id="center">
    <div id="header"><a href="/"><img src="/images/umvc.gif" alt="UMVC php web framework" /></a></div>
    <ul id="menu">
      <li<? if(@$mainmenu === 'start') echo ' class="selected"'; ?>><a href="/"><?=$language['base']['menu']['start']?></a></li>
      <li<? if(@$mainmenu === 'examples') echo ' class="selected"'; ?>><a href="/examples"><?=$language['base']['menu']['examples']?></a></li>
      <li<? if(@$mainmenu === 'about') echo ' class="selected"'; ?>><a href="/about"><?=$language['base']['menu']['about']?></a></li>
      <li<? if(@$mainmenu === 'contact') echo ' class="selected"'; ?>><a href="/contact"><?=$language['base']['menu']['contact']?></a></li>
      <li<? if(@$mainmenu === 'language') echo ' class="selected"'; ?>><a href="/language"><?=$language['base']['menu']['language']?></a></li>
    </ul>
    <div id="content">
      <?=$content;?>
    </div>
    <div id="footer"><?print_r($_SESSION)?>
    Yoyo!</div>
  </div>
</body>
</html>