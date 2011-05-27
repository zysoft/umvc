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

class examples_controller extends base_controller
{
  // This method is called before all actions
  public function before_action()
  {
    parent::before_action();

    $this->mainmenu = 'examples';

    // very ugly hack
    if ($this->request()->get_action() == 'before-action')
    {
      ?>
        examples_controller->before_action trigs and takes over. It has run the following code:
        <br/>
        <ul>
          if ($this->request()->get_action() == 'before-action')
        </ul>
        it can also return an integer to load an error page.
        <br/><br/>
        It is now returning FALSE to tell the controller to break here - just like a regular action.
      <?
      return FALSE;
    }
  }

  // This action uses view: "index"
  public function index()
  {
    $this->foo = 'bar';
  }

  public function form_validation()
  {
    function email($value, &$message) {
      $result = filter_var($value, FILTER_VALIDATE_EMAIL);
      if($result === FALSE)
      {
        $message = 'illegal email address';
        return FALSE;
      }
      return TRUE;
    }
    $this->validator()->add_rule('email', 'email');

    function password($value, &$message) {
      if($value != 'pw')
      {
        $message = 'illegal password';
        return FALSE;
      }
      return TRUE;
    }
    $this->validator()->add_rule('password', 'password');

    $this->validator()->validate();
  }

  // this action uses view: "todo_list"
  public function todo_list()
  {
    $this->todos = array(
      'Buy coffee',
      'Watch TV',
      'Walk the dog',
      'Code PHP');
  }


  // parameters coming from the query string for the index action
  public function language_translate_param($in_parameter_name)
  {
    //var_dump(debug_backtrace());
    switch ($in_parameter_name)
    {
      case 'parameter1': return 'param1';
      case 'myparameter1': return 'param1';
      case 'parameter2': return 'param2';
      case 'antal-talare': return 'num-speakers';
    }
  }

  public function language()
  {
    // here we use the internal (english) name
    $this->num_speakers = $this->request()->parameter('num-speakers','');
    $this->foo = $this->request()->parameter('foo','');
  }

  // this action uses view: "debug"
  public function debug()
  {
    $this->response()->attribute('template','blank');
    $this->foo = 'bar';
  }

  // this action uses view: "routing"
  public function routing()
  {
  }


  // this action uses view: "routing"
  public function error()
  {
    return 5000;
  }

  // this action has no view
  public function no_view()
  {
    echo '<p>This text comes directly from the controller.</p>';
    return FALSE;
  }

  // this action uses view: "debug"
  public function other_view()
  {
    return 'debug';
  }

  public function sub_views()
  {
  }

  public function javascript()
  {
    $this->response()->javascript('alert("Hello");');
  }

  public function lightbox()
  {
    if($this->request()->has_parameter('show'))
    {
      $this->response()->attribute('template','blank');
      echo 
        '<h1>Hello</h1>'.
        '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>'.
        '<p><a href="#" onclick="$.fn.tightbox(\'close\');">Close</a></p>';
    } else
    {
      $this->response()->javascript('$(function(){$(\'.tightbox\').tightbox({height: 200});})');
      echo
        '<h1>Examples: Lightbox</h1>'.
        '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>'.
        '<p><a href="/examples/lightbox/show" class="tightbox">Test lightbox</a></p>';
    }
    return FALSE;
  }  
}

?>