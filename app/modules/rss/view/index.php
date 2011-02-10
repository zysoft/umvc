<?php echo '<?'; ?>xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <atom:link href="http://www.jobbexpressen.se/rss" rel="self" type="application/rss+xml" />
    <title>Jobbexpressen - Lediga jobb</title>
    <description></description>
    <link>http://www.jobbexpressen.se</link>
    <?php foreach($items as $item) { ?>
    <item>
     <title><?php echo $item['title']; ?></title>
     <description><?php echo $item['description']; ?></description>
     <link><?php echo $item['url']; ?></link>
     <guid><?php echo $item['url']; ?></guid>
    </item>
    <?php } ?>
  </channel>
</rss>