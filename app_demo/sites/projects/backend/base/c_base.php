<?

/****************************************************************************/
/* This is the base controller all modules should inherit from.             */
/* Use it to set default behavior                                           */
/****************************************************************************/

class base_controller extends uf_controller
{
  public function before_action()
  {
    parent::before_action();
    $this->menu = array(
      array(
        'id' => 'index',
        'uri' => '/',
        'title' => 'Start'),        
      array(
        'id' => 'login',
        'uri' => '/login',
        'title' => 'Login'));
  }
  
  public function login()
  {    
    echo $this->request()->parameter('username').'/'.$this->request()->parameter('password');
    return FALSE;
  }
}
?>