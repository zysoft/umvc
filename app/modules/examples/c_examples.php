<?php

class examples_controller extends uf_controller {
  // This method is called before all actions
  protected function before_action() {
    $this->caller()->mainmenu = 'examples';    
  }

  // this action uses view: "index"
  public function index() {
    $this->foo = 'bar';
  }

  // this action uses view: "todo_list"
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

  // this action uses view: "routing"
  public function routing() {
  }

  // this action has no view
  public function noview() {
    echo 'This text comes directly from the controller.';
    $this->foo = 'bar';
    return FALSE;
  }

  // this action uses view: "debug"
  public function otherview() {
    $this->foo = 'bar';
    return 'debug';
  }
}

?>