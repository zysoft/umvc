<?

class uf_http_request
{
  private $_segments;
  private $_parameters;
  private $_uri;
  private $_lang_tag;
  // state after routing:
  private $_controller;
  private $_action;
  private $_uri_parameters;
  // --------------------
  
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
  
  public function get_parameter_names()
  {
    reset($this->_parameters);
    $res = array();
    while (list($key,$val) = each($this->_parameters))
    {
      array_push($res,$key);
    }
    return $res;
  }

  public function set_parameter_name($old_name, $new_name)
  {
    if ($old_name == $new_name) return;
    
    $this->_parameters[$new_name] = $this->_parameters[$old_name];
    $this->_uri_parameters[$new_name] = $this->_uri_parameters[$old_name];

    unset($this->_parameters[$old_name]);
    unset($this->_uri_parameters[$old_name]);
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
    // TODO: parse out the language from the beginning of the string
    
    $uri = $_SERVER['REQUEST_URI'];

    // URI language detection, NO module name should be shorter than 5 chars
    // or at least have
    $uri_lang_len = NULL;
    
    // /uk/
    if (strlen($uri) > 4 && $uri[3] === '/') $uri_lang_len = 2;
    else
    // /en-us/
    if (strlen($uri) >= 7 && $uri[6] === '/') $uri_lang_len = 5;

    if (NULL !== $uri_lang_len) // we got ourselves a language
    {
      // validate the language against the language file
      $test_string = substr($uri,1,$uri_lang_len);
      
      $languages_file = UF_BASE.'/config/languages.php';
      $languages = 0;
      if(file_exists($languages_file)) 
      {
	$languages = include_once($languages_file);
      }
      if (is_array($languages))
      {
        foreach ($languages as $lang)
	{
	  if ($test_string === $lang)
	  {
	    uf_application::set_language($lang);
            $uri = substr($uri,$uri_lang_len+1);
	  }
	}
      }
    }

    $pos = strpos($uri,'?');
    if($pos !== FALSE)
    {
      $uri = substr($uri,0,$pos);
    }
    
    $uri_segments = explode('/',substr($uri,1));
    //array_shift($uri_segments);
    
    $always_bake = 1;// uf_application::get_config('always_bake');

    $pre_routing_file = UF_BASE.'/cache/baker'.uf_application::app_name().'/'.uf_application::host().'/routing/baked.pre.routing.php';
//echo $pre_routing_file;
    if($always_bake || !file_exists($pre_routing_file))
    {
      uf_baker::bake('pre_routing');
    }
    if(file_exists($pre_routing_file))
    {
      include_once($pre_routing_file);
    }

    // NORMAL ROUTING
    $routing_file = UF_BASE.'/cache/baker'.uf_application::app_name().'/'.uf_application::host().'/routing/baked.routing.php';
    if($always_bake || !file_exists($routing_file))
    {
      uf_baker::bake('routing');
    }
    if(file_exists($routing_file))
    {
      include_once($routing_file);
    }

    // POST ROUTING
    $post_routing_file = UF_BASE.'/cache/baker/'.uf_application::app_name().'/'.uf_application::host().'/routing/baked.post.routing.php';
    if($always_bake || !file_exists($post_routing_file))
    {
      uf_baker::bake('post_routing');
    }
    if(file_exists($post_routing_file))
    {
      include_once($post_routing_file);
    }

    // we can now assume that:
    //   - the routing has translated the uri to internal controller names
    // and the uri_segments contain:
    // - module
    // - action
    // - parameters (if any)
    $this->_controller = @$uri_segments[0];
    $this->_action     = @$uri_segments[1]; // might be empty
    
    // bake the uri string
    $this->uri(implode('/',$uri_segments));
    
    $this->_segments = $uri_segments;

    // generate parameter array from uri
    $parameters = array();
    for($i = 2; $i < count($this->_segments); $i += 2)
    {
      $parameters[$this->_segments[$i]] = @$this->_segments[$i + 1];
    }
    $this->_uri_parameters = $parameters;

    $input = array_merge($parameters, $_GET, $_POST);
  
    $this->set_parameters($input);
  }

  public function get_controller()
  {
    return $this->_controller;
  }

  public function get_action()
  {
    return $this->_action;
  }

  public function get_uri_segments()
  {
    return $this->_segments;
  }

  public function get_uri_parameters()
  {
    return $this->_uri_parameters;
  }

  public function is_post()
  {
    return count($_POST) > 0;
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