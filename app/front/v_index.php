<?='<?'?>xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sv">
<head>
  <title>HR North</title>	
  <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css.php" />
  <script src="/js/main.js.php" type="text/javascript" charset="utf-8"></script>
</head>
<body>
  <div id="center">
    <ul id="menu">
      <li<?php if(@$mainmenu === 'home') echo ' class="selected"'; ?>><a href="/">Home</a></li>
      <li<?php if(@$mainmenu === 'available_jobs') echo ' class="selected"'; ?>><a href="/available-jobs">Available jobs</a></li>
      <li<?php if(@$mainmenu === 'my_pages') echo ' class="selected"'; ?>><a href="/my-pages">My pages</a></li>
      <li<?php if(@$mainmenu === 'career_guide') echo ' class="selected"'; ?>><a href="/career-guide">Career guide</a></li>
    </ul>
    <?=$content;?>
  </div>
</body>
</html>