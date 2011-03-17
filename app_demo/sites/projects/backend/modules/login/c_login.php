<?

class login_controller extends base_controller
{
  public function before_action()
  {
    parent::before_action();
    $this->mainmenu = 'login';
  }

  public function index() 
  {
  }

  public function login() 
  {
  }
}

?>