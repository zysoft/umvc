<?

class index_controller extends base_controller
{
  public function index()
  {
    $this->mainmenu = 'start';
    $this->foo = 'bar';
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