<?
class ufResponse {
  private $_attributes;
  private $_headers;
  private $_data;

  public function __construct() {
    $this->_attributes = array('template' => 'index');
    $this->_headers = array();
    $this->header('Content-Type', 'text/html');
    $this->_data = '';
  }

  public function attribute($name, $value = NULL) {
    if($value !== NULL) {
      $this->_attributes[$name] = $value;
    } else {
      return array_key_exists($name, $this->_attributes) ? $this->_attributes[$name] : $value;
    }
  }

  public function header($name, $value = NULL) {
    if($value !== NULL) {
      $this->_headers[$name] = $value;
    } else {
      return array_key_exists($name, $this->_headers) ? $this->_headers[$name] : $value;
    }
  }

  public function header404() {
    $this->header('#HTTP/', $_SERVER["SERVER_PROTOCOL"].' 404 Not Found'); // Special header for 404 errors
    $this->header('Status', '404 Not Found');
  }

  public function headers() {
    $headers = array();
    foreach($this->_headers as $name => $value) {
      if($name == '#HTTP/') {
        $headers[] = $value; // Special header for 404 errors
      } else {
        $headers[] = $name.': '.$value; // Normal header
      }
    }
    return $headers;
  }

  public function data($data = NULL) {
    if($data !== NULL) {
      $this->_data .= $data;
    } else {
      return $this->_data;
    }
  }
}
