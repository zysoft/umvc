<h1><?=$language['base']['menu']['examples']?>: todo-list</h1>

<p>Todo-list of items from an array:</p>
<ul>
  <?
  foreach($todos as $todo)
  {
    echo '  <li>'.$todo.'</li>'."\n";
  }
  ?>
</ul>