<?

class about_controller extends base_controller
{
  public function index() 
  {
    $this->mainmenu = 'about';
    $this->meta_description = 'About UMVC backend';
    $this->meta_keywords = 'php, mvc, framework, web development';
  }
}

?>