<?

class about_controller extends base_controller
{
  public function index() 
  {
    $this->caller()->mainmenu = 'about';
    $this->caller()->meta_description = 'About UMVC';
    $this->caller()->meta_keywords = 'php, mvc, framework, web development';
  }
}

?>