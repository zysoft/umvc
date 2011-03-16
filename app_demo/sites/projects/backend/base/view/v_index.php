<?='<?'?>xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sv">
<head>
  <title>UMVC - administrator</title>	
  <meta name="description" content="UMVC" />
  <meta name="keywords" content="umvc, php, framework, mvc" />
  <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css.php" />
  <script src="/js/main.js.php" type="text/javascript" charset="utf-8"></script>
</head>
<body>
  <div id="center">
    <ul id="menu">
      <?php foreach($menu as $menu_item) { ?>
      <li<? if(@$mainmenu === $menu_item['id']) echo ' class="selected"'; ?>><a href="<?=$menu_item['uri']?>"><?=$menu_item['title']?></a></li>  
      <?php } ?>
    </ul>
    <div id="content">
      <?=$content?>
    </div>
    <div id="footer">Yoyo!</div>
  </div>
</body>
</html>