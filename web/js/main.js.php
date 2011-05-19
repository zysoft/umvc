<?
/**
 * Project: umvc: A Mode View Controller framework
 *
 * @author David Brännvall, Jonatan Wallmander, HR North Sweden AB http://hrnorth.se, Copyright (C) 2011.
 * @see The GNU Public License (GPL)
 */
/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

define('UF_BASE',realpath(dirname(__FILE__).'/../..'));
require_once(UF_BASE.'/core/umvc.php');
uf_application::init();

header('Content-Type: text/javascript');

$js_file = UF_BASE.'/web/data/baker'.uf_application::app_name().'/'.uf_application::host().'/js/baked.js';
if(uf_application::get_config('always_bake') || !file_exists($js_file))
{
  uf_baker::bake('js');
}
echo @file_get_contents($js_file)."\n";

@include_once(UF_BASE.'/cache/baker'.uf_application::app_name().'/'.uf_application::host().'/js/baked.js.php');
?>
