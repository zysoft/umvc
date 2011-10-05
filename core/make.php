<?php

class Make
{
  public static function filter_php_file($f)
  {
      return strtolower(strrchr(end($f), '.')) === '.php';
  }

  public static function filter_image_file($f)
  {
    return in_array(strtolower(strrchr(end($f), '.')), array('.gif', '.png', '.jpg', '.jpeg'));
  }

  public static function filter_js_file($f)
  {
    return strtolower(strrchr(end($f), '.')) === '.js';
  }

  public static function filter_css_file($f)
  {
    return strtolower(strrchr(end($f), '.')) === '.css';
  }
  
  public static function filter_lang_file($f)
  {
    return strtolower(strrchr(end($f), '.')) === '.lang';
  }

  public static function filter($file_list, $filter = NULL)
  {
    if($filter === NULL) return $file_list;

    if(method_exists(Make, $filter)) {
      $filter = array(Make, $filter);      
    }
    
    return array_filter($file_list, $filter);
  }
  
  public static function get_files_from_dir($dir = '')
  {
    // fix start and end slashes
    if(substr($dir, 0, 1) != '/') $dir = '/'.$dir;
    if(strrpos($dir, '/') + 1 == strlen($dir)) $dir = substr($dir, 0, -1);
    
    $output = array();
    $list = scandir(UF_BASE.$dir);
    foreach($list as $curr)
    {
      if(substr($curr, 0, 1) != '.')
      {
        if(is_dir(UF_BASE.$dir.'/'.$curr))
        {
          $temp = Make::get_files_from_dir($dir.'/'.$curr);
          $output = array_merge($temp, $output);
        } else
        {
          $output[] = explode('/', $dir.'/'.$curr);
          $output[count($output) - 1][0] = UF_BASE;
        }
      }
    }
    return $output;    
  }

  public static function get_folders_from_dir($dir = '')
  {
    // fix start and end slashes
    if(substr($dir, 0, 1) != '/') $dir = '/'.$dir;
    if(strrpos($dir, '/') + 1 == strlen($dir)) $dir = substr($dir, 0, -1);
    
    $output = array();
    $list = scandir(UF_BASE.$dir);
    foreach($list as $curr)
    {
      if(substr($curr, 0, 1) != '.')
      {
        if(is_dir(UF_BASE.$dir.'/'.$curr))
        {
          $output[] = explode('/', $dir.'/'.$curr);
          $output[count($output) - 1][0] = UF_BASE;
        }
      }
    }
    return $output;    
  }
  
  public static function merge($list_a, $list_b)
  {
    return array_merge($list_a, $list_b);
  }
}

/* EOF */