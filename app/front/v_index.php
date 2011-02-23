<?='<?'?>xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sv">
<head>
  <title>HR North</title>	
  <link rel="alternate" type="application/rss+xml" title="" href="/rss" />
  <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css.php" />
  <script src="/js/main.js.php" type="text/javascript" charset="utf-8"></script>
</head>
<body>
  <div id="center">
    <div id="header"><a href="/"><img src="/images/umvc.gif" alt="UMVC php web framework" /></a></div>
    <ul id="menu">
      <li<? if(@$mainmenu === 'start') echo ' class="selected"'; ?>><a href="/">Start</a></li>
      <li<? if(@$mainmenu === 'examples') echo ' class="selected"'; ?>><a href="/examples">Examples</a></li>
      <li<? if(@$mainmenu === 'aout') echo ' class="selected"'; ?>><a href="/about">About</a></li>
      <li<? if(@$mainmenu === 'contact') echo ' class="selected"'; ?>><a href="/contact">Contact</a></li>
    </ul>
    <div id="content">
      <?=$content;?>
    </div>
    <div id="footer">Yoyo!</div>
  </div>
</body>
</html>