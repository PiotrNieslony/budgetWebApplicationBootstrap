<?php
class User {
  public $id;
  public $name;
  public $pass;
  function __construct($name, $pass){
    $this->name = $name;
    $this->pass = $pass
  }
}
