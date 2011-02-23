<?php

class examples_controller extends uf_controller {
  // this action uses view: "index"
  public function index() {
    $this->caller()->mainmenu = 'examples';
    $this->foo = 'bar';
  }

  public function todo_list() {
    $this->caller()->mainmenu = 'examples';
    $this->todos = array(
      'Routing functions',
      'Clean urls',
      'Template engine');
  }

  // this action uses view: "debug"
  public function debug() {
    $this->caller()->mainmenu = 'examples';
    $this->foo = 'bar';
  }

  // this action has no view
  public function noview() {
    $this->caller()->mainmenu = 'examples';
    echo 'This text comes directly from the controller.';
    $this->foo = 'bar';
    return FALSE;
  }

  // this action uses view: "test"
  public function otherview() {
    $this->caller()->mainmenu = 'examples';
    $this->foo = 'bar';
    return 'debug';
  }
}

?>