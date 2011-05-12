<?

class uf_validator
{
  private $_rules;
  private $_request;
  private $_response;

  public function __construct($request, $response)
  {
    $this->_rules = array();
    $this->_request = $request;
    $this->_response = $response;
  }

  public function add_rule($field, $type, $rule)
  {
    $field = uf_controller::str_to_controller($field);
    $this->_rules[$field] = array(
      'type' => $type,
      'rule'=> $rule);
  }

  public function validate()
  {
    $result = TRUE;
    foreach($this->_request->parameters() as $key => $val)
    {
      $key = uf_controller::str_to_controller($key);
      if(array_key_exists($key, $this->_rules)) {
        if($val == '')
        {
          $this->_response->javascript('$(function(){alert("'.$key.'");});');
          $result = FALSE;
        }        
      }
    }
    return $result;
  }
}

/* EOF */