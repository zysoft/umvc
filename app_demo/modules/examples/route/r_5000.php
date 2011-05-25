<?
  if($uri_segments[0] == 'routing-example-using-special-url')
  {
    $uri_segments[0] = 'examples';
    $uri_segments[1] = 'routing';
    return;
  }

  // input:
  //   /exempel/sprak/antal-talare/10000000
  // desired output:
  //   /examples/language/antal-talare/10000000
  
  if (@$uri_segments[0] == 'exempel')
  {
    // this is in a specific language
    uf_application::set_language('sv-se');
    $uri_segments[0] = 'examples';
    if (@$uri_segments[1] == 'att-gora-lista')
    {
      $uri_segments[1] = 'todo-list';
      // parameters are then handled in the controller.
    } else
    if (@$uri_segments[1] == 'sprak')
    {
      $uri_segments[1] = 'language';
      // parameters are then handled in the controller.
    }
  }

?>