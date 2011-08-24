<?php

define('UF_BASE',realpath(dirname(__FILE__).'/../..'));
require_once(UF_BASE.'/core/make.php');

function scan_namespace($dir)
{
  if(!is_dir($dir)) return array();

  $path = explode('/', $dir);
  
  $index = count($path);
  $files = Make::get_files_from_dir($dir);
  $files = Make::filter($files, 'filter_php_file');
  foreach($files as &$file)
  {
    $file = implode('/', array_slice($file, $index + 1));
  }
  $files = array_flip($files);
  foreach($files as $file => &$ids)
  {
    $filename = UF_BASE.'/'.$dir.'/'.$file;
    $code = file_get_contents($filename);
    $ids = array();
    if(preg_match_all('/_\(["\'](.*?)["\']\)/msi', $code, $matches))
    {
      $ids[] = $matches[1][0];
    }    
    if(count($ids) == 0) unset($files[$file]);
  }

  return array($dir => $files);
}

function scan_namespaces($dir)
{
  if(!is_dir($dir)) return array();
  $namespaces = array();
  $parent_dir = substr(strrchr($dir, '/'), 1);
  $folders = Make::get_folders_from_dir($dir);
  foreach($folders as &$folder)
  {
    $index = array_search($parent_dir, $folder); 
    $namespace = scan_namespace($dir.'/'.$folder[$index + 1]);
    $namespaces = array_merge($namespaces, $namespace);
  }
  return $namespaces;
}

function merge_namespaces($a, $b)
{
  foreach($a as $name => $afiles)
  {
    $b[$name] = isset($b[$name]) ? array_merge($b[$name], $afiles) : $afiles;
  }
  return $b;
}


function pretty_print_namespace($namespaces)
{
  $output = '';
  if(count($namespaces) == 0) return;
  foreach($namespaces as $name => $namespace)
  {
    $output .= 'namespace='.$name."\n";
    foreach($namespace as $file => $ids)
    {
      if(count($ids) > 0)
      {
        $output .=  "\n".'#'.$file."\n";
        foreach($ids as $id)
        {
          $output .=  $id.'='."\n";
        }        
      }
    }
    $output .=  "\n";      
  }
  return $output;
}

function namespaces_to_array($str)
{
  $namespace = '';
  $file = '';
  $a = array_map('trim', explode("\n", $str));
  $result = array();
  foreach($a as $l)
  {
    if($l != '')
    {
      if(($p = strpos($l, '=')) !== FALSE)
      {
        $keyval = explode('=', $l);
        if($keyval[0] == 'namespace')
        {
          $namespace = $keyval[1];
          if(!isset($result[$namespace]))
          {
            $result[$namespace] = array();
          }
        } else
        {
          $id = $keyval[0];
          if(!isset($result[$namespace][$file]))
          {
            $result[$namespace][$file] = array();
          }
          $result[$namespace][$file][] = $id;
        }
      } else
      if(strpos($l, '#') === 0)
      {
        $file = substr($l, 1);
      }
    }
  }
  return $result;
}
/*
*/
$lib = scan_namespace('app_demo/lib');
$modules = scan_namespaces('app_demo/modules');
$site_base = scan_namespaces('app_demo/sites/hosts/FALLBACK/base');
$site_modules = scan_namespaces('app_demo/sites/hosts/FALLBACK/modules');

$namespaces = merge_namespaces($lib, $modules);
$namespaces = merge_namespaces($namespaces, $site_modules);
$namespaces = merge_namespaces($namespaces, $site_base);

echo "-- input --\n";
print_r($namespaces);

echo "-- pretty --\n";
$pretty = pretty_print_namespace($namespaces);
print_r($pretty);

echo "-- output --\n";
$output = namespaces_to_array($pretty);
print_r($output);


/* EOF */