<?

class uf_validator
{
  private $_rules;
  private $_request;
  private $_response;
  private $_result;

  public function __construct($request, $response)
  {
    $this->_rules = array();
    $this->_request = $request;
    $this->_response = $response;
    $this->_result = array();
  }

  public function add_rule($name, $callback)
  {
    $name = uf_controller::str_to_controller($name);
    $this->_rules[$name] = array('callback' => $callback);
  }

  public function validate()
  {
    // Only validate on post
    if($this->_request->is_post()) {
      $this->_result = array();
      $result = TRUE;
      foreach($this->_request->parameters() as $key => $val)
      {
        $key = uf_controller::str_to_controller($key);
        if(array_key_exists($key, $this->_rules)) {
          $message = '';
          $r = $this->_rules[$key]['callback']($val, $message);
          if(!$r)
          {
            $data = json_encode(array('name' => $key, 'message' => $message));
            $this->_response->javascript('$(function(){umvc.trigger("umvc.validator.error",'.$data.');});');
            $result = FALSE;
          }
          $this->_result[$key] = $r;
        }
      }

      if($result && count($this->_rules))
      {
        $data = json_encode(array('message' => 'success'));
        $this->_response->javascript('$(function(){umvc.trigger("umvc.validator.success",'.$data.');});');      
      }

      return $result;
    }
    return TRUE;
  }
  
  public function result($name)
  {
    return isset($this->_result[$name]) ? $this->_result[$name] : FALSE;
  }
}

/* EOF */