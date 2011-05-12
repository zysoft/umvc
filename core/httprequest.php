<?

class uf_http_request
{
  private $_segments;
  private $_parameters;
  private $_uri;
  private $_is_post;
  
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

  public function parameters()
  {
    return $this->_parameters;
  }
  
  public function __construct()
  {
    $uri = $_SERVER['REQUEST_URI'];

    $pre_routing_file = UF_BASE.'/cache/baker'.uf_application::config('app_dir').'/'.uf_application::host().'/routing/baked.pre.routing.php';
    if(uf_application::config('always_bake') || !file_exists($pre_routing_file))
    {
      uf_baker::bake('pre_routing');
    }
    if(file_exists($pre_routing_file))
    {
      $uri = include_once($pre_routing_file);
    }

    // NORMAL ROUTING
    $routing_file = UF_BASE.'/cache/baker'.uf_application::config('app_dir').'/'.uf_application::host().'/routing/baked.routing.php';
    if(uf_application::config('always_bake') || !file_exists($routing_file))
    {
      uf_baker::bake('routing');
    }
    if(file_exists($routing_file))
    {
      $uri = include_once($routing_file);
    }

    // POST ROUTING
    $post_routing_file = UF_BASE.'/cache/baker/'.uf_application::config('app_dir').'/'.uf_application::host().'/routing/baked.post.routing.php';
    if(uf_application::config('always_bake') || !file_exists($post_routing_file))
    {
      uf_baker::bake('post_routing');
    }
    if(file_exists($post_routing_file))
    {
      $uri = include_once($post_routing_file);      
    }

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
    $this->_is_post = count($_POST);
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
  
  public function is_post() {
    return $this->_is_post;
  }
}

?>