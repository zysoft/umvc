<?php

class uf_baker
{
  private static function _scan_dir_recursive($dir) {
    $a = scandir($dir);
    array_splice($a,0,2);
    $out = array();
    foreach($a as $f) {
      $fp = $dir.'/'.$f;
      if(is_dir($fp)) {
        $sub = self::_scan_dir_recursive($fp);
        $out = array_merge_recursive($out,$sub);
      } else {
        if(substr($f,0,2) == 'r_') {
          $ext = substr(strrchr($f, '.'),1);
          $out[$ext][] = $fp;
        }
      }
    }
    return $out;
  }

  public static function build_cache() {
    $files = self::_scan_dir_recursive(UF_BASE.'/app');
    die(nl2br(print_r($files,1)));
  }
}

?>