<?='<?'?>xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sv">
<head>
  <title>HR North</title>	
  <link rel="stylesheet" type="text/css" media="screen" href="/css/main.php" />
</head>
<body>
  <div id="center">
    <ul id="menu">
      <li<?php if($mainmenu === 'home') echo ' class="selected"'; ?>><a href="/?_controller=home">Home</a></li>
      <li<?php if($mainmenu === 'available_jobs') echo ' class="selected"'; ?>><a href="/?_controller=available-jobs">Available jobs</a></li>
      <li<?php if($mainmenu === 'my_pages') echo ' class="selected"'; ?>><a href="/?_controller=my-pages">My pages</a></li>
      <li<?php if($mainmenu === 'career_guide') echo ' class="selected"'; ?>><a href="/?_controller=career-guide">Career guide</a></li>
    </ul>
    <div id="plate" class="rcol12"></div>
    <div id="content" class="rcol12"></div>
    <?=$content;?>
    </div>
  </div>
</body>
</html>