<?

class uf_http_request extends uf_request
{
  private $_segments;

  public function __construct()
  {
    $uri = uf_application::apply_routing($_SERVER['REQUEST_URI']);
    $this->uri($uri);
    $pos = strpos($uri,'?');
    if($pos !== FALSE)
    {
      $uri = substr($uri,0,$pos);
    }
    
    $this->_segments = explode('/',$uri);
    array_shift($this->_segments);

    $p = array();
    for($i = 2; $i < count($this->_segments); $i += 2)
    {
      $p[$this->_segments[$i]] = @$this->_segments[$i + 1];
    }

    $input = array_merge($p,$_GET,$_POST);
    $this->parameters($input);
  }

  public function controller()
  {
    return isset($this->_segments[0]) && !empty($this->_segments[0]) ? $this->_segments[0] : parent::controller();
  }

  public function action()
  {
    return isset($this->_segments[1]) ? $this->_segments[1] : parent::action();
  }
}

?>