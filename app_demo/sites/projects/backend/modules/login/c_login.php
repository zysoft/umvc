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
    if($this->request()->parameter('login'))
    {
      if($this->request()->parameter('username') == 'admin' && $this->request()->parameter('password') == 'password')
      {
        uf_session::set('login', TRUE);
        $this->response()->redirect('/');
        return FALSE;
      } 
      return 'login_failed';
    }
    
    if(uf_session::get('login'))
    {
      return 'logout';
    }
  }
  
  public function logout()
  {
    uf_session::set('login', NULL);
    $this->response()->redirect('/');
  }

}

?>