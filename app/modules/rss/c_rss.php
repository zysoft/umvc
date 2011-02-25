<?

class rss_controller extends base_controller
{
  public function index()
  {
    $this->response()->attribute('template','blank');
    $this->response()->header('Content-type','text/xml');
    $this->items = array(
      array(
        'title' => 'Lorem ipsum',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        'url' => 'http://www.google.com/'
      )
    );
  }
}

?>