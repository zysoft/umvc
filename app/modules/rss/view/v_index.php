<?='<?'?>xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <atom:link href="http://<?=$_SERVER['SERVER_NAME'].$uf_controller->request()->uri()?>" rel="self" type="application/rss+xml" />
    <title>Jobbexpressen - Lediga jobb</title>
    <description></description>
    <link>http://www.jobbexpressen.se</link>
    <? foreach($items as $item) { ?>
    <item>
     <title><?= $item['title']?></title>
     <description><?=$item['description']?></description>
     <link><?=$item['url']?></link>
     <guid><?=$item['url']?></guid>
    </item>
    <? } ?>
  </channel>
</rss>