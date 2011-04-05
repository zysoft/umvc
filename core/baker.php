<?

require_once(UF_BASE.'/core/umvc.php');

class uf_baker
{
  private static $_files;
 
  static function _sort_files($a,$b)
  {
    $a = strrchr($a,'/');
    $ap = strpos($a,'_');
    $cp = strpos($a,'_',$ap + 1);
    if($cp === FALSE) $cp = strpos($a,'.',$ap + 1);
    $c = substr($a,$ap + 1,$cp - $ap - 1);

    $b = strrchr($b,'/');
    $bp = strpos($b,'_');
    $dp = strpos($b,'_',$bp + 1);
    if($dp === FALSE) $dp = strpos($b,'.',$bp + 1);
    $d = substr($b,$bp + 1,$dp - $bp - 1);

    if(is_int($c) || is_int($d))
    {
      return strrchr($a,'/') >= strrchr($b,'/');
    }
    else
    {
      return $c >= $d;
    }
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
        $is_recursive = substr($f,0,2) == 'b_';
        $is_route     = substr($f,0,2) == 'r_';
        if($is_recursive || $is_route)
        {
          $ext = substr(strrchr($f,'.'),1);

          // Get the right ext for php files (routing files excluded)
          $is_dynamic = $ext == 'php';
          if($is_dynamic)
          {
            // skip last .php and extract file type, ie file.js.php will return js
            $ext = substr(strrchr(substr($f,0,strpos($f,'.php')),'.'),1);
          }
          
          $out[$is_dynamic ? 'dynamic' : 'static'][$is_route ? 'routing' : $ext][] = $fp;
        }
      }
    }
    return $out;
  }
  
  private static function _scan_dir()
  {
    if(!is_array(self::$_files))
    {
      $modules = self::_scan_dir_recursive(uf_application::app_dir().'/modules');
      $hosts   = self::_scan_dir_recursive(uf_application::app_sites_host_dir());
      self::$_files = array_merge_recursive($modules,$hosts);

      if(isset(self::$_files['static']))
      {
        foreach(self::$_files['static'] as &$type)
        {
          usort($type,array('uf_baker','_sort_files'));
        }        
      }
      if(isset(self::$_files['dynamic']))
      {
        foreach(self::$_files['dynamic'] as &$type)
        {
          usort($type,array('uf_baker','_sort_files'));
        }
      }
    }
  }

  private static function _bake_routing($files,$prefix='')
  {
    $prefix2 = $prefix.($prefix != '' ? '_' : '');
    $output = '<? function uf_internal_'.$prefix2.'routing_function(&$uri) { ?>';
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
    /*$output = str_replace('?><?','',$output);*/
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

    for($i = 0; $i < 2; $i++)
    {
      $place = $i == 0 ? 'static' : 'dynamic';
      $output = '';
      if(isset(self::$_files[$place][$type]))
      {
        switch($type)
        {
          case 'routing':
            $output .= self::_bake_routing(self::$_files[$place][$type],$prefix);
            break;
          default:
            $output .= self::_bake_default(self::$_files[$place][$type]);
        }      
      }
      $bake_base = UF_BASE.'/'.($place == 'dynamic' ? 'cache' : 'web/data');

      $host = uf_application::host();
      $dir = $bake_base.'/baker'.uf_application::config('app_dir').'/'.$host.'/'.$type;

      if(/*test !($type == 'routing' && $place == 'static') &&*/ !is_dir($dir))
      {
        mkdir($dir,0777,TRUE);
      }
      if($output != '')
      {
        file_put_contents($dir.'/baked.'.($prefix!='' ? $prefix.'.' : '').$type.($place == 'dynamic' ? '.php' : ''),$output);
      }
    }
  }

  public static function bake_all()
  {
    self::bake('js');
    self::bake('css');
    self::bake('pre_routing');
    self::bake('routing');
    self::bake('post_routing');
  }
}

?>