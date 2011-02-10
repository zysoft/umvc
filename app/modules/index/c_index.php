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

  // local 404 error handler
  // to select local views for handling local errors
  // i.e. /index/foo
  public function error($caller)
  {
    $this->value = 'error handler';
    return 'local_error_404';
  }
}

?>