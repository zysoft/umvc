<?php

class uf_baker
{
  private static $_files;
 
  private static function _scan_dir_recursive($dir)
  {
    $a = scandir($dir);
    array_splice($a,0,2);
    $out = array();
    foreach($a as $f)
    {
      $fp = $dir.'/'.$f;
      if(is_dir($fp))
      {
        $sub = self::_scan_dir_recursive($fp);
        $out = array_merge_recursive($out,$sub);
      } 
      else
      {
        $is_recursive = substr($f,0,2) == 'r_';
        $is_route     = substr($f,0,8) == 'routing_';
        if($is_recursive || $is_route)
        {
          $ext = substr(strrchr($f, '.'),1);
          $out[$is_route ? 'routing' : $ext][] = $fp;
        }
      }
    }
    return $out;
  }
  
  private static function _scan_dir() {
    if(!is_array(self::$_files))
    {
      self::$_files = self::_scan_dir_recursive(UF_BASE.'/app');
      usort(
        self::$_files['routing'], 
        function($a, $b)
        {
          return strrchr($a, '/') >= strrchr($b, '/');
        }
      );
    }    
  }

  private static function _bake_js($files) {
    $output = '';
    if(is_array($files))
    {
      foreach($files as $file)
      {
        $data = file_get_contents($file);
        $output .= '(function(){'."\n".$data."\n".'})();'."\n";
      }
    }
    return $output;
  }

  private static function _bake_css($files) {
    $output = '';
    if(is_array($files))
    {
      foreach($files as $file)
      {
        $data = file_get_contents($file);
        $output .= $data."\n";
      }
    }
    return $output;
  }

  private static function _bake_routing($files) {
    $output = '<?php uf_application::_set_routing_function(function($uri) { ?>'."\n";
    if(is_array($files))
    {
      foreach($files as $file)
      {
        $data = file_get_contents($file);
        $output .= trim($data);
      }
    }
    $output .= "\n".'<?php }); ?>'."\n";
    $output = str_replace('?><?php','',$output);
    return $output;
  }

  private static function _bake_default($files) {
    $output = '';
    if(is_array($files))
    {
      foreach($files as $file)
      {
        $data = file_get_contents($file);
        $output .= $data."\n";
      }
    }
    return $output;
  }
  
  public static function bake($type)
  {
    self::_scan_dir();
    $output = '';
    switch($type) {
      case 'js':
        $output .= self::_bake_js(self::$_files[$type]);
        break;
      case 'css':
        $output .= self::_bake_css(self::$_files[$type]);
        break;
      case 'routing':
        $output .= self::_bake_routing(self::$_files[$type]);
        break;
      default:
        $output .= self::_bake_default(self::$_files[$type]);
    }
    file_put_contents(UF_BASE.'/cache/baked.'.($type == 'routing' ? 'routing.php' : $type),$output);
    return $output;
  }

  public static function bake_all() {
    self::bake('js');
    self::bake('css');
    self::bake('php');
    self::bake('routing');
  }
}

?>