<?

require_once(UF_BASE.'/core/umvc.php');

class uf_baker
{
  private static $_files;
 
  static function _sort_routes($a,$b)
  {
    return strrchr($a,'/') >= strrchr($b,'/');
  }
  
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
          $ext = substr(strrchr($f,'.'),1);
          $out[$is_route ? 'routing' : $ext][] = $fp;
        }
      }
    }
    return $out;
  }
  
  private static function _scan_dir()
  {
    if(!is_array(self::$_files))
    {
      self::$_files = self::_scan_dir_recursive(UF_BASE.uf_application::config('app_dir'));
      if(isset(self::$_files['routing']))
      {
        usort(self::$_files['routing'],array('uf_baker','_sort_routes'));        
      }
    }  
  }

  private static function _bake_js($files)
  {
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

  private static function _bake_css($files)
  {
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

  private static function _bake_routing($files,$prefix='')
  {
    $prefix2 = $prefix.($prefix != '' ? '_' : '');
    $output = '<? function uf_internal_'.$prefix2.'routing_function($uri) { ?>'."\n";
    if(is_array($files))
    {
      foreach($files as $file)
      {
        $f = substr(strrchr($file,'/'),1);
        if($prefix != '')
        {
          // Only prefixed files
          if(strpos($f, 'routing_'.$prefix.'_') === 0)
          {
            $data = file_get_contents($file);
            $output .= trim($data);                      
          }
        }
        else
        {
          // Only unprefixed files
          if(strpos($f,'routing_pre_') !== 0 && strpos($f,'routing_post_') !== 0)
          {
            $data = file_get_contents($file);
            $output .= trim($data);
          }
        }
      }
    }
    $output .= "\n".'<? return $uri; } ?>'."\n";
    $output = str_replace('?><?','',$output);
    return $output;
  }

  private static function _bake_default($files)
  {
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
    $info = explode('_',$type);
    if(count($info) > 1)
    {
      $prefix = $info[0];
      $type = $info[1];
    }
    else
    {
      $prefix = '';
      $type = $info[0];
    }
    
    self::_scan_dir();
    $output = '';

    if(isset(self::$_files[$type]))
    {
      switch($type)
      {
        case 'js':
          $output .= self::_bake_js(self::$_files[$type]);
          break;
        case 'css':
          $output .= self::_bake_css(self::$_files[$type]);
          break;
        case 'routing':
          $output .= self::_bake_routing(self::$_files[$type],$prefix);
          break;
        default:
          $output .= self::_bake_default(self::$_files[$type]);
      }      
    }
    file_put_contents(UF_BASE.'/cache/baked.'.($prefix!='' ? $prefix.'.' : '').($type == 'routing' ? 'routing.php' : $type),$output);
    return $output;
  }

  public static function bake_all()
  {
  
    self::bake('js');
    self::bake('css');
    self::bake('php');
    self::bake('pre_routing');
    self::bake('routing');
    self::bake('post_routing');
  }
}

if(uf_application::config('always_bake'))
{
  uf_baker::bake_all();  
}

?>