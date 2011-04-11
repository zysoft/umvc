<?
class uf_session
{
  static public function has($name)
  {
    return isset($_SESSION[$name]);
  }
  
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

  // Value for both saving, getting and checking existence of values in the session.
  // Try to only use strings.
  // How to use:
  //   uf_session::value('foo');       // return value or NULL if not set
  //   uf_session::value('foo',NULL);  // remove value from session (if found)
  //   uf_session::value('foo','bar'); // set value of foo to 'bar'
  //
  // NOTE(!):
  //   Since 0xFFFFFFFF is used as a key, use '4294967295' or (string)0xFFFFFFFF
  //   when setting this particular value into the session.
  static public function value($name, $value = 0xFFFFFFFF)
  {
    if ($value === 0xFFFFFFFF)
    {
      if (isset($_SESSION[$name]))
      {
        return $_SESSION[$name];
      } else
      {
        return NULL;
      }
    } else
    if ($value === NULL)
    {
      //
      if (isset($_SESSION[$name]))
      {
        unset($_SESSION[$name]);
      }
    } else
    {
      $_SESSION[$name] = $value;
    }
  }
}

session_start();
?>