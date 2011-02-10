<?php

class my_pagesController extends ufController {
  // this action uses view: "index"
  public function index() {
    $this->foo = 'bar';
  }

  public function todo_list() {
    $this->todos = array(
      'Routing functions',
      'Clean urls',
      'Template engine');
  }

  // this action uses view: "debug"
  public function debug() {
    $this->foo = 'bar';
  }

  // this action has no view
  public function noview() {
    echo 'This text comes directly from the controller.';
    $this->foo = 'bar';
    return FALSE;
  }

  // this action uses view: "test"
  public function otherview() {
    $this->foo = 'bar';
    return 'debug';
  }
}

?>