<?php
class MyDB extends PDO {
  public function getSingleValue($query){
    $qery = $this->query($query);
    $row = $qery->fetch();
    $result = $row[0];
    return $result;
  }
}
