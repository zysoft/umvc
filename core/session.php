<?
class uf_session
{
  static public function get($name, $default_value = '')
  {
    return isset($_SESSION[$name]) ? $_SESSION[$name] : $default_value;
  }

  static public function set($name, $value = NULL)
  {
    if($value === NULL)
    {
      unset($_SESSION[$name]);
    }
    else
    {
      $_SESSION[$name] = $value;
    }
    return $value;
  }
}

session_start();
?>