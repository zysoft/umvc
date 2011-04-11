<?

class uf_http_request
{
  private $_segments;
  private $_parameters;
  private $_uri;
  
  public function uri($uri = NULL)
  {
    if($uri !== NULL)
    {
      $this->_uri = $uri;
    }
    else
    {
      return $this->_uri;
    }
    
  }
  
  public function set_parameters($parameters = NULL)
  {
    if($parameters !== NULL)
    {
      $this->_parameters = $parameters;
    }
    else
    {
      return $this->_parameters;
    }
  }
  
  public function parameter($name,$default_value = NULL)
  {
    return array_key_exists($name,$this->_parameters) ? $this->_parameters[$name] : $default_value;
  }
  
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
    $this->set_parameters($input);
  }

  public function controller()
  {
    return isset($this->_segments[0]) && !empty($this->_segments[0]) ? $this->_segments[0] : $this->parameter('_controller','index');
  }

  public function action()
  {
    return isset($this->_segments[1]) ? $this->_segments[1] : $this->parameter('_action','index');
  }
}

?>