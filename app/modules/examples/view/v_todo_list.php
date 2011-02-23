<h1>Examples: todo-list</h1>

<p>Todo-list of items from an array:</p>
<ul>
  <?php
  foreach($todos as $todo) {
    echo '  <li>'.$todo.'</li>'."\n";
  }
  ?>
</ul>