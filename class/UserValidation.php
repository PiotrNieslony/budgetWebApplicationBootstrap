<?php
class UserValidation {
  private $validationCorrect = true;
  private $errors;
  private $db = null;

  public function __construct($db){
    $this->db = $db;
  }

  public function getValidationCorrect(){
    return $this->validationCorrect;
  }

  public function getErrors(){
    return $this->errors;
  }

  public function checkUsername($login){
      if((strlen($login)<3) || ((strlen($login)>20))) {
        $this->validationCorrect = false;
        $this->errors['e_login'] = "Login musi posiadać od 3 do 20 znaków.";
    }

    if(ctype_alnum($login) == false){
        $this->validationCorrect = false;
        $this->errors['e_login'] = "Nick może składać się tylko z liter i cyfr (bez polskich znaków).";
    }
  }

  public function checkIfUsernameExist ($login){
      $query = $this->db->prepare('SELECT id FROM users WHERE username = :username');
      $query->bindValue(':username', $login, PDO::PARAM_STR);
      $query->execute();
      if($query->rowCount()){
          $this->validationCorrect = false;
          $this->errors['e_login']  = "Ten login jest już zajęty.";
      }
  }

  public function checkEmail($email){
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

    if((filter_var($emailB, FILTER_VALIDATE_EMAIL)) == false || ($emailB != $email)){
        $this->validationCorrect = false;
        $this->errors['e_email'] = "Podaj poprawny adres email.";
    } else {
        $query = $this->db->prepare('SELECT id FROM users WHERE email = :email');
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount()){
            $this->validationCorrect = false;
            $this->errors['e_email'] = "Ten adres  email został już użyty do rejestracji konta.";
        }
    }
  }

  public function checkPassword($pass1, $pass2){
    if((strlen($pass1)<7) || (strlen($pass1)>20)){
        $this->validationCorrect = false;
        $this->errors['e_pass'] = "Haslo musi posiadać od 8 do 20 znaków.";
    }

    if($pass1 != $pass2){
        $this->validationCorrect = false;
        $this->errors['e_pass'] = "Podane hasła nie są identyczne.";
    }
  }

  public function regulaminAcceptCheck(){
    if(!isset($_POST['akceptTerms'])){
        $this->validationCorrect = false;
        $this->errors['e_terms'] = "Potwierdź akceptację regulaminu.";
    }
  }

  public function passTheSameAsCurrent($pass, $userID){
    $currentPassword = $this->db->getSingleValue("SELECT password FROM users WHERE id = $userID ");
    if(!password_verify($pass, $currentPassword)){
      $this->validationCorrect = false;
      $this->errors['e_wrong_pass'] = "Błędne hasło.";
    }
  }
}
