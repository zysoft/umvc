<?

class indexController extends ufController
{
  public function index()
  {
    echo 'uu';
    $this->foo = 'bar';
    $this->caller()->mainmenu = '';
    $this->caller()->mupp = 'mupp';
    //$this->response()->attribute('template', 'blank');
    $this->value = 'index';
    $this->response()->attribute('mainmenu', 'home');
  }

  // local 404 error handler
  // to select local views for handling local errors
  // i.e. /index/foo
  public function error()
  {
    $this->value = 'error handler';
    return 'local_error_404';
  }
}

?>