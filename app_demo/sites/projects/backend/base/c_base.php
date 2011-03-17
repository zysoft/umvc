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
    $this->mainmenu = 'start';
    $this->menu = array(
      array(
        'id' => 'start',
        'uri' => '/',
        'title' => 'Start'),        
      array(
        'id' => 'login',
        'uri' => '/login',
        'title' => 'Login'));
  }
}

?>