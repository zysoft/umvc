<?

class test_controller extends base_controller
{
  public function index() 
  {
    $this->load_plugin('translate');
    echo $this->_("knusboll1");
    return false;    
  }
}

?>