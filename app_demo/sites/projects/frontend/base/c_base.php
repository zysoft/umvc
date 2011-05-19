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

/****************************************************************************/
/* This is the base controller all modules should inherit from.             */
/* Use it to set default behavior                                           */
/****************************************************************************/

class base_controller extends uf_controller
{
  public function before_action()
  {
    parent::before_action();
    $this->title = $this->language['base']['title'];
    $this->meta_description = 'UMVC frontend';
    $this->meta_keywords = 'php, mvc, framework, web development';
  }
}

/* EOF */