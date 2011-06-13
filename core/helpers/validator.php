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

class uf_validator
{
  private $_form_id;
  private $_rules;
  private $_request;
  private $_response;
  private $_result;

  public function __construct($form_id, $request, $response)
  {
    $this->_form_id = $form_id;
    $this->_rules = array();
    $this->_request = $request;
    $this->_response = $response;
    $this->_result = array();
  }

  public function add_rule($name, $callback)
  {
    $name = uf_controller::str_to_controller($name);
    $this->_rules[$name] = array('callback' => $callback);
  }

  public function validate()
  {
    // Only validate on post
    if($this->_request->is_post()) {
      $this->_result = array();
      $result = TRUE;
      foreach($this->_request->parameters() as $key => $val)
      {
        $key = uf_controller::str_to_controller($key);
        if(array_key_exists($key, $this->_rules)) {
          $message = '';
          
          $callback = $this->_rules[$key]['callback'];
          
          $r = is_array($callback)
            ? call_user_func($callback, $val, $message)
            : $callback($val, $message);

          if(!$r)
          {
            $data = json_encode(array(
              'form_id' => $this->_form_id,
              'name' => $key,
              'message' => $message));
            $this->_response->javascript('$(function(){umvc.trigger("umvc.validator.error",'.$data.');});');
            $result = FALSE;
          }
          $this->_result[$key] = $r;
        }
      }

      if($result && count($this->_rules))
      {
        $data = json_encode(array('message' => 'success'));
        $this->_response->javascript('$(function(){umvc.trigger("umvc.validator.success",'.$data.');});');      
      }

      return $result;
    }
    return TRUE;
  }
  
  public function result($name)
  {
    return isset($this->_result[$name]) ? $this->_result[$name] : FALSE;
  }
}

/* EOF */