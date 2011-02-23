<?

class examples_controller extends uf_controller
{
  // This method is called before all actions
  protected function before_action()
  {
    $this->caller()->mainmenu = 'examples';    
  }

  // this action uses view: "index"
  public function index()
  {
    $this->foo = 'bar';
  }

  // this action uses view: "todo_list"
  public function todo_list()
  {
    $this->todos = array(
      'Buy coffee',
      'Watch TV',
      'Walk the dog',
      'Code PHP');
  }

  // this action uses view: "debug"
  public function debug()
  {
    $this->foo = 'bar';
  }

  // this action uses view: "routing"
  public function routing()
  {
  }

  // this action has no view
  public function no_view()
  {
    echo '<p>This text comes directly from the controller.</p>';
    return FALSE;
  }

  // this action uses view: "debug"
  public function other_view()
  {
    return 'debug';
  }
}

?>