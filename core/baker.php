<?php

class uf_baker
{
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

  protected static function bake_file($out_file, $source_files)
  {
    //echo 'baking '.$out_file."\n";
    $output = '';
    if(is_array($source_files))
    {
      foreach($source_files as $source_file) {
        //echo '  source file: '.substr(strrchr($source_file, '/'),1).'.'."\n";
        $output .= '(function(){'."\n".file_get_contents($source_file)."\n".'})();'."\n";
      }
    }
    else
    {
      //echo '  no ingredients found.'."\n";
    }
    file_put_contents(UF_BASE.'/cache/'.$out_file,$output);
  }
  
  public static function bake()
  {
    $files = self::_scan_dir_recursive(UF_BASE.'/app');
    self::bake_file('baked.js',$files['js']);
    self::bake_file('baked.css',$files['css']);
  }
}

?>