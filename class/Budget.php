<?
class Budget {
  function __construct($host, $user, $pass, $db)
  {
    $this->dbo = $this->initDB($host, $user, $pass, $db);
  }

  function initDB($host, $user, $pass, $db){
    try{
        $db = new PDO("mysql:host={$config['host']};dbname={$config['db']};charset = utf8",$config['user'], $config['pass'], [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (PDOException $error) {
        //echo $error->getMessage();
        exit('Database error');
    }
  }

  function getActualUser {
    if(isset($_SESSION['zalogowany'])){
      return $_SESSION['zalogowany'];
    }
    else{
      return null;
    }
  }

  function login(){

  }
  function logout(){
    session_destroy();
    header('Location:zaloguj');
  }

}
