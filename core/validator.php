<?

class uf_validator
{
  private $_rules;

  public function __construct()
  {
    $this->_rules = array();
  }

  public function add_rule($field, $type, $rule)
  {
    $this->rules['field'] = array(
      'type' => $type,
      'rule'=> $rule);
  }

  public function test_rule($rule, $type, $data)
  {
  }
}

/* EOF */