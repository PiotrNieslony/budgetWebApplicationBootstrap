<?php
class Users {
  private $db = null;

  public function __construct($db){
    $this->db = $db;
  }

  public function getActualUserId() {
    if(isset($_SESSION['loggedUser'])){
      return $_SESSION['loggedUser']['id'];
    }
    else{
      return false;
    }
  }

  public function getLoggedUsername() {
    if(isset($_SESSION['loggedUser'])){
      return $_SESSION['loggedUser']['username'];
    }
    else{
      return false;
    }
  }

  public function getEmailAdress(){
    $userId = $this->getActualUserId();
    return $query = $this->db->getSingleValue("SELECT email FROM users WHERE id = $userId");
  }

  public function login(){
    if(isset($_SESSION['loggedUser']['id'])) return true;
    if(!isset($_POST['inputLogin'])) return false;
    $login = $_POST['inputLogin'];
    $pass = $_POST['inputPassword'];
    $query = $this->db->prepare('SELECT id, username, password FROM users WHERE username = :username');
    $query->bindValue(':username', $login);
    $query->execute();
    $user = $query->fetch();
    if((strtolower($login) == strtolower($user['username'])) && password_verify($pass, $user['password'])){
        $_SESSION['loggedUser']['id'] = $user['id'];
        $_SESSION['loggedUser']['username'] = $user['username'];
        return true;
    } else {
        $_SESSION['eWrongData'] = "Niepoprawny login lub hasło.";
        return false;
    }
  }

  public function logout(){
    if(isset($_SESSION['loggedUser'])) {
      unset($_SESSION['loggedUser']);
    }
  }

  private function copyCategoryFromDefault($catType, $userID){
    $defaultTableName  = $catType."_category_default";
    $categoryTableName = $catType."_category_assigned_to_users";

    $query = $this->db->query("SELECT * FROM $defaultTableName");
    $query->execute();
    $defaultCategorys = $query->fetchAll();
    foreach($defaultCategorys as $category){
      $categoryName  = $category['name'];

      if($catType == "expenses") $sql = "INSERT INTO $categoryTableName
                    VALUES (NULL, $userID , NULL, '$categoryName', NULL)";
      else $sql = "INSERT INTO $categoryTableName
                    VALUES (NULL, $userID , NULL, '$categoryName')";

      $this->db->query($sql);

      $lastID = $this->db->getSingleValue("SELECT MAX(id) FROM $categoryTableName
                                          WHERE user_id = $userID");

      if($category['parent_category_id'] == $category['id']){
        $id = $lastID;
      } else {
        $parentCategoryName = "";
        foreach($defaultCategorys as $category2){
          if($category2['id'] == $category['parent_category_id'] ){
            $parentCategoryName = $category2['name'];
            break;
          }
        }
        $id = $this->db->getSingleValue("SELECT id FROM $categoryTableName
                                        WHERE user_id = $userID
                                        AND name = '$parentCategoryName'
                                        AND parent_category_id = id");
      }
      $this->db->query("UPDATE $categoryTableName
                        SET parent_category_id = $id
                        WHERE id = $lastID ");
   }

  }

  public function register(){
    if(!isset($_POST['inputLogin'])) return false;

    $login = $_POST['inputLogin'];
    $email = $_POST['inputEmail'];
    $pass1 = $_POST['inputPassword1'];
    $pass2 = $_POST['inputPassword2'];

    $validation = new UserValidation ($this->db);

    $validation->checkUsername($login);

    if($validation->getValidationCorrect()){
      $validation->checkIfUsernameExist($login);
    }

    if($email != ''){
      $validation->checkEmail($email);
    }

    $validation->checkPassword($pass1, $pass2);

    $validation->regulaminAcceptCheck();

    //Remember the entered data
    if(!$validation->getValidationCorrect()){
        $_SESSION['typedLogin'] = $login;
        $_SESSION['typedEmail'] = $email;
        $_SESSION['typedPass1'] = $pass1;
        $_SESSION['typedPass2'] = $pass2;
        $_SESSION['akceptTerms'] = $_POST['akceptTerms'];
    }

    if($validation->getValidationCorrect()) {
        $_SESSION['typedLogin'] = $login;
        $pass_hash = password_hash($pass1, PASSWORD_DEFAULT);
        $query = $this->db->prepare('INSERT INTO users VALUES (NULL, :username, :password, :email)');
        $query->bindValue(':username', $login, PDO::PARAM_STR);
        $query->bindValue(':password', $pass_hash , PDO::PARAM_STR);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $userID = $this->db->getSingleValue("SELECT id FROM users WHERE username = '$login'");

        $this->copyCategoryFromDefault("expenses", $userID);
        $this->copyCategoryFromDefault("incomes", $userID);

        $addPaymentMethods = "INSERT INTO payment_methods_assigned_to_users(user_id, name)
                                SELECT users.id, pd.name
                                FROM users
                                INNER JOIN
                                payment_methods_default pd
                                where users.username = '$login';";

        $this->db->query($addPaymentMethods);
        header('Location: registration-confirm.php');
        return true;
    } else {
      $_SESSION['errors'] = $validation->getErrors();
      return false;
    }
  }

  public function editUserData(){
    $login  = $_POST['inputLogin'];
    $userID = $this->getActualUserId();
    $email  = $_POST['inputEmail'];

    $validation = new UserValidation ($this->db);

    if($login != $this->getLoggedUsername()){
      $validation->checkUsername($login);
      if($validation->getValidationCorrect()){
        $validation->checkIfUsernameExist($login);
      }
    }

    if($email != $this->getEmailAdress()){
      if($email != ''){
        $validation->checkEmail($email);
      }
    }

    if($validation->getValidationCorrect()) {
      $query = $this->db->prepare('UPDATE users
                                  SET username  = :username,
                                      email = :email
                                  WHERE id = :id');
      $query->bindValue(':id', $userID, PDO::PARAM_INT);
      $query->bindValue(':username', $login, PDO::PARAM_STR);
      $query->bindValue(':email', $email, PDO::PARAM_STR);
      $query->execute();
      $_SESSION['loggedUser']['username'] = $login;
      echo json_encode(array('ok'));
    } else {
      echo json_encode($validation->getErrors());
    }
  }

  public function changPassword(){
    $oldPass = $_POST['oldPass'];
    $pass1   = $_POST['pass1'];
    $pass2   = $_POST['pass2'];
    $userID  = $this->getActualUserId();

    $validation = new UserValidation ($this->db);

    $validation->passTheSameAsCurrent($oldPass, $userID);
    $validation->checkPassword($pass1,$pass2);
    $pass_hash = password_hash($pass1, PASSWORD_DEFAULT);
    if($validation->getValidationCorrect()) {
      $query = $this->db->prepare('UPDATE users
                                  SET password  = :pass
                                  WHERE id = :id');
      $query->bindValue(':id', $userID, PDO::PARAM_INT);
      $query->bindValue(':pass', $pass_hash, PDO::PARAM_STR);
      $query->execute();
      echo json_encode(array('ok'));
    } else {
      echo json_encode($validation->getErrors());
    }
  }

  public function checkPassword($pass){
    $userID = $this->getActualUserId();
    $validation = new UserValidation ($this->db);
    $validation->passTheSameAsCurrent($pass, $userID);
    if($validation->getValidationCorrect()) return true;
    else return false;
  }

  public function deleteUserAccount(){
    $userID = $this->getActualUserId();
    $sql = "DELETE FROM users WHERE id =  $userID";
    $this->db->query($sql);
    session_destroy();
  }
}
