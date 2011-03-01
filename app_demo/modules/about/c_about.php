<?

class about_controller extends base_controller
{
  public function index() 
  {
    $this->mainmenu = 'about';
    $this->caller()->meta_description = 'About UMVC';
    $this->caller()->meta_keywords = 'php, mvc, framework, web development';
  }
}

?>