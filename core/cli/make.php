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
    $file = array_slice($file, $index + 1);
  }

  foreach($files as &$file)
  {
    $filename = UF_BASE.'/'.$dir.'/'.implode('/', $file);
    $code = file_get_contents($filename);
    $file = array('filename' => implode('/', $file), 'ids' => array());
    if(preg_match_all('/_\(["\'](.*?)["\']\)/msi', $code, $matches))
    {
      $file['ids'][] = $matches[1][0];
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

function pretty_print($namespaces)
{
  if(count($namespaces) == 0) return;
  foreach($namespaces as $name => $namespace)
  {
    $files =& $namespace;
    echo 'namespace='.$name."\n";
    foreach($files as $file)
    {
      $ids =& $file['ids'];
      if(count($ids) > 0)
      {
        echo "\n".'#'.$file['filename']."\n";
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
$namespaces = array_merge($lib, $modules, $site_modules, $site_base);
pretty_print($namespaces);

/* EOF */