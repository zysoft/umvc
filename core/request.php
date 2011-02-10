<?
class ufRequest {
  private $_parameters;

  protected function parameters($parameters = NULL) {
    if($parameters !== NULL) {
      $this->_parameters = $parameters;
    } else {
      return $this->_parameters;
    }
  }

  public function parameter($name, $default_value = NULL) {
    return array_key_exists($name, $this->_parameters) ? $this->_parameters[$name] : $default_value;
  }

  public function controller() {
    return $this->parameter('_controller', 'index');
  }

  public function action() {
    return $this->parameter('_action', 'index');
  }
}

