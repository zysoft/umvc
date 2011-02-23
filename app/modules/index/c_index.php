<?

class index_controller extends uf_controller
{
  public function index()
  {
    $this->caller()->mainmenu = 'start';
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