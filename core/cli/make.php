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
  if(count($namespaces) == 0) return;
  foreach($namespaces as $name => $namespace)
  {
    echo 'namespace='.$name."\n";
    foreach($namespace as $file => $ids)
    {
      if(count($ids) > 0)
      {
        echo "\n".'#'.$name.'/'.$file."\n";
        foreach($ids as $id)
        {
          echo $id.'='."\n";
        }        
      }
    }
    echo "\n";      
  }  
}

$lib = scan_namespace('app_demo/lib');
$modules = scan_namespaces('app_demo/modules');
$site_base = scan_namespaces('app_demo/sites/hosts/FALLBACK/base');
$site_modules = scan_namespaces('app_demo/sites/hosts/FALLBACK/modules');

$namespaces = merge_namespaces($lib, $modules);
$namespaces = merge_namespaces($namespaces, $site_modules);
$namespaces = merge_namespaces($namespaces, $site_base);

pretty_print_namespace($namespaces);

/* EOF */