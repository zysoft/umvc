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

  public function form_validation()
  {
    function email($value, &$message) {
      $result = filter_var($value, FILTER_VALIDATE_EMAIL);
      if($result === FALSE)
      {
        $message = 'illegal email address';
        return FALSE;
      }
      return TRUE;
    }
    $this->validator()->add_rule('email', 'email');

    function password($value, &$message) {
      if($value != 'pw')
      {
        $message = 'illegal password';
        return FALSE;
      }
      return TRUE;
    }
    $this->validator()->add_rule('password', 'password');

    $this->validator()->validate();
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


  // parameters coming from the query string for the index action
  public function language_translate_param($in_parameter_name)
  {
    switch ($in_parameter_name)
    {
      case 'parameter1': return 'param1';
      case 'myparameter1': return 'param1';
      case 'parameter2': return 'param2';
      case 'antal-talare': return 'num-speakers';
    }
  }

  public function language()
  {
    // here we use the internal (english) name
    $this->num_speakers = $this->request()->parameter('num-speakers','');
    $this->foo = $this->request()->parameter('foo','');
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

  public function sub_views()
  {
  }

  public function javascript()
  {
    $this->response()->javascript('alert("Hello");');
  }
  
}

?>