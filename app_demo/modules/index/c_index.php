<?

class index_controller extends base_controller
{
  public function before_action()
  {
    parent::before_action();
    $this->mainmenu = 'start';    
  }
  public function index()
  {
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