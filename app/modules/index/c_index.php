<?

class indexController extends ufController
{
  public function index(&$caller)
  {
    $this->foo = 'bar';
    $caller->mainmenu = '';
    $caller->mupp = 'mupp';
    //$this->response()->attribute('template', 'blank');
    $this->value = 'index';
    $this->response()->attribute('mainmenu', 'home');
  }

  // 404 error handler
  // remove this to get the default error404 handler
  /*public function error($caller)
  {
    $this->value = 'error handler';
    return 'index';
  }*/
}

?>