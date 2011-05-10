<?

class examples_controller extends base_controller
{
  // This method is called before all actions
  public function before_action()
  {
    parent::before_action();
    $this->caller()->mainmenu = 'examples';    
  }

  // This action uses view: "index"
  public function index()
  {
    $this->foo = 'bar';
  }

  public function on_post_form_validation()
  {
    $this->response()->javascript('umvc.add_validator("form", function(){alert("form validator")});');
    $this->response()->javascript('$(function(){umvc.validate("form");});');
  }
  
  public function form_validation()
  {
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
    $this->response()->attribute('template','blank');
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