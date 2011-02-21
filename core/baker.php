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
        if(substr($f,0,2) == 'r_')
        {
          $ext = substr(strrchr($f, '.'),1);
          $out[$ext][] = $fp;
        }
      }
    }
    return $out;
  }

  public static function bake($type)
  {
    if(!is_array(self::$_files))
    {
      self::$_files = self::_scan_dir_recursive(UF_BASE.'/app');
    }

    //echo 'baking '.$out_file."\n";
    $output = '';
    if(is_array(self::$_files[$type]))
    {
      foreach(self::$_files[$type] as $source_file) {
        //echo '  source file: '.substr(strrchr($source_file, '/'),1).'.'."\n";
        $output .= '(function(){'."\n".file_get_contents($source_file)."\n".'})();'."\n";
      }
    }
    else
    {
      //echo '  no ingredients found.'."\n";
    }
    file_put_contents(UF_BASE.'/cache/baked.'.$type,$output);

    return $output;
  }

  public static function bake_all() {
    self::bake('js');
    self::bake('css');
    self::bake('php');
  }
}

?>