<?

class about_controller extends uf_controller
{
  public function index() 
  {
    $this->caller()->meta_description = 'About UMVC';
    $this->caller()->meta_keywords = 'php, mvc, framework, web development';
    $this->caller()->mainmenu = 'about';
  }
}

?>