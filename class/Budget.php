<?php
class Budget {
  private $db = null;

  public function __construct($host, $user, $pass, $db){
    $this->db = $this->initDB($host, $user, $pass, $db);
  }

  public function initDB($host, $user, $pass, $db){
    try{
        $db = new MyDB("mysql:host={$host};dbname={$db};charset = utf8",$user, $pass, [
            MyDB::ATTR_EMULATE_PREPARES => true,
            MyDB::ATTR_ERRMODE => MyDB::ERRMODE_EXCEPTION]);
        return $db;
    } catch (PDOException $error) {
        echo $error->getMessage();
        exit('Database error');
    }
  }

  public function getActualUserId() {
    if(isset($_SESSION['loggedUser'])){
      return $_SESSION['loggedUser']['id'];
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

  public function register(){
    if(!isset($_POST['inputLogin'])) return false;
    //Validation
    $validation = true;

    //check login name
    $login = $_POST['inputLogin'];

    if((strlen($login)<3) || ((strlen($login)>20))) {
        $validation = false;
        $_SESSION['e_login'] = "Login musi posiadać od 3 do 20 znaków.";
    }

    if(ctype_alnum($login) == false){
        $validation = false;
        $_SESSION['e_login'] = "Nick może składać się tylko z liter i cyfr (bez polskich znaków).";
    }

    if (!isset($_SESSION['e_login'])){
        $query = $this->db->prepare('SELECT id FROM users WHERE username = :username');
        $query->bindValue(':username', $login, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount()){
            $validation = false;
            $_SESSION['e_login'] = "Ten login jest już zajęty.";
        }
    }

    //check email
    $email = $_POST['inputEmail'];
    if($email != ''){
      $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

      if((filter_var($emailB, FILTER_VALIDATE_EMAIL)) == false || ($emailB != $email)){
          $validation = false;
          $_SESSION['e_email'] = "Podaj poprawny adres email.";
      } else {
          $query = $this->db->prepare('SELECT id FROM users WHERE email = :email');
          $query->bindValue(':email', $email, PDO::PARAM_STR);
          $query->execute();
          if($query->rowCount()){
              $validation = false;
              $_SESSION['e_email'] = "Ten adres  email został już użyty do rejestracji konta.";
          }
      }
    }

    //Check the correctness of the password
    $pass1 = $_POST['inputPassword1'];
    $pass2 = $_POST['inputPassword2'];

    if((strlen($pass1)<8) || (strlen($pass1)>20)){
        $validation = false;
        $_SESSION['e_pass'] = "Haslo musi posiadać od 8 do 20 znaków.";
    }

    if($pass1 != $pass2){
        $validation = false;
        $_SESSION['e_pass'] = "Podane hasła nie są identyczne.";
    }

    //Check regulamin checkbox
    if(!isset($_POST['akceptTerms'])){
        $validation = false;
        $_SESSION['e_terms'] = "Potwierdź akceptację regulaminu.";
    }

    //Remember the entered data
    if($validation == false){
        $_SESSION['typedLogin'] = $login;
        $_SESSION['typedEmail'] = $email;
        $_SESSION['typedPass1'] = $pass1;
        $_SESSION['typedPass2'] = $pass2;
        $_SESSION['akceptTerms'] = $_POST['akceptTerms'];

    }

    if($validation) {
        $pass_hash = password_hash($pass1, PASSWORD_DEFAULT);
        $query = $this->db->prepare('INSERT INTO users VALUES (NULL, :username, :password, :email)');
        $query->bindValue(':username', $login, PDO::PARAM_STR);
        $query->bindValue(':password', $pass_hash , PDO::PARAM_STR);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['typedLogin'] = $login;

        $addDefalutExpensesCategory = "INSERT INTO expenses_category_assigned_to_users(user_id, name)
                                SELECT users.id, ex.name
                                FROM users
                                INNER JOIN
                                expenses_category_default ex
                                where users.username = '$login';";

        $addDefalutIncomeCategory = "INSERT INTO incomes_category_assigned_to_users(user_id, name)
                                SELECT users.id, inco.name
                                FROM users
                                INNER JOIN
                                incomes_category_default inco
                                where users.username = '$login';";

        $addPaymentMethods = "INSERT INTO payment_methods_assigned_to_users(user_id, name)
                                SELECT users.id, pd.name
                                FROM users
                                INNER JOIN
                                payment_methods_default pd
                                where users.username = '$login';";

        $this->db->query($addDefalutExpensesCategory);
        $this->db->query($addDefalutIncomeCategory);
        $this->db->query($addPaymentMethods);
        header('Location: registration-confirm.php');
        return true;
    }
    return false;
  }

  public function addIncome(){
    $incomes = new Incomes($this->db);
    return $incomes->add();
  }

  public function editIncome(){
    $incomes = new Incomes($this->db);
    return $incomes->edit();
  }

  public function deleteIncome(){
    $incomes = new Incomes($this->db);
    return $incomes->delete();
  }

  public function showIncomsCategory($where) {
    $categoryQuery = $this->db->prepare('SELECT id, parent_category_id, name FROM incomes_category_assigned_to_users WHERE user_id = :user_id');
    $categoryQuery->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
    $categoryQuery->execute();
    $categorys = $categoryQuery->fetchAll();
    $this->generateCategoryHtml($categorys, $where);
    echo (isset($_SESSION['e_categorys'])) ? "<p class='alert alert-danger'>".$_SESSION['e_categorys']."</p>" : "";
    unset($_SESSION['e_categorys']);
  }

  public function addExpense(){
    $expenses = new Expenses($this->db);
    return $expenses->add();
  }

  public function editExpense(){
    $expenses = new Expenses($this->db);
    return $expenses->edit();
  }

  public function deleteExpense(){
    $expenses = new Expenses($this->db);
    return $expenses->delete();
  }

  function generateCategoryHtml($categoryArray, $where){
    foreach($categoryArray as $category){
      if($category[1] == $category[0]){
        echo "<div class=\"radio mainCategory\" id=\"expenseCategory\">
                <label><input type=\"radio\" name=\"categorys\" value=\"$category[0]\" />$category[2]
                <span class='checkmark'></span>";
        if($where == 'settings')
          echo "<button class=\"btn btn-xs btn-danger delete\">
                  <span class=\"glyphicon glyphicon glyphicon glyphicon-trash\" aria-hidden=\"true\"></span>
                </button>
                <button class=\"btn btn-xs btn-primary edit\">
                  <span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>
                </button>";
        echo    "</label>
              </div>
            <div class=\"subCategory\">";
        if($where == 'addCategory') continue;
        foreach($categoryArray as $subCategory)
            if($subCategory[1] == $category[0] && $subCategory[0] != $subCategory[1]){
              echo "<div class=\"radio\" id=\"expenseCategory\">
                      <label><input type=\"radio\" name=\"categorys\" value=\"$subCategory[0]\" />$subCategory[2]
                      <span class='checkmark'></span>";
              if($where == 'settings')
                echo "<button class=\"btn btn-xs btn-danger delete\">
                        <span class=\"glyphicon glyphicon glyphicon glyphicon-trash\" aria-hidden=\"true\"></span>
                      </button>
                      <button class=\"btn btn-xs btn-primary edit\">
                        <span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>
                      </button>";
              echo    "</label>
                   </div>";
            }
        echo "</div>";
      }
    }
  }

  public function showExpensPaymentMethod() {
    $paymentMethodQuery = $this->db->prepare('SELECT id, name FROM payment_methods_assigned_to_users WHERE user_id = :user_id');
    $paymentMethodQuery->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
    $paymentMethodQuery->execute();
    $paymentMethods = $paymentMethodQuery->fetchAll();
    foreach($paymentMethods as $paymentMethod){
      echo "<option value=\"$paymentMethod[0]\">$paymentMethod[1]</option>";
    }
  }

  public function showExpensCategory($where) {
    $categoryQuery = $this->db->prepare('SELECT id, parent_category_id ,name FROM expenses_category_assigned_to_users WHERE user_id = :user_id');
    $categoryQuery->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
    $categoryQuery->execute();
    $categorys = $categoryQuery->fetchAll();
    $this->generateCategoryHtml($categorys, $where);
    echo (isset($_SESSION['e_categorys'])) ? "<p class='alert alert-danger'>".$_SESSION['e_categorys']."</p>" : "";
    unset($_SESSION['e_categorys']);
  }

  public function obtainBalanceDate(){
    $firstDayOfMonth = new DateTime('first day of this month');
    $lastDayOfMonth = new DateTime('first day of this month');
    $today = new DateTime();
    if(isset($_POST['date-scope'])){
        if($_POST['date-scope'] == "current-month"){
            $balaceDateFrom = $firstDayOfMonth->format('Y-m-d');
            $balaceDateTo   = $today->format('Y-m-d');
            $_SESSION['selected-date'] = 'current-month';
        } elseif ($_POST['date-scope'] == "previous-month"){
            $firstDayOfMonth->modify('first day of previous month');
            $lastDayOfMonth->modify('last day of previous month');
            $balaceDateFrom = $firstDayOfMonth->format('Y-m-d');
            $balaceDateTo   = $lastDayOfMonth->format('Y-m-d');
            $_SESSION['selected-date'] ='previous-month';
        }
    } elseif (isset($_POST['dateFrom'])){
        $balaceDateFrom = $_POST['dateFrom'];
        $balaceDateTo   = $_POST['dateTo'];
        $_SESSION['selected-date'] = 'custom';
    } else {
        $balaceDateFrom = $firstDayOfMonth->format('Y-m-d');
        $balaceDateTo   = $today->format('Y-m-d');
    }
    return array($balaceDateFrom, $balaceDateTo, );
  }

  public function setBalanceDateToSession(){
    $balancePeriodTime = $this->obtainBalanceDate();
    $_SESSION['selected-date-from'] = $balancePeriodTime[0];
    $_SESSION['selected-date-to'] = $balancePeriodTime[1];
  }

  public function showIncomes(){
    $incomes = new Incomes($this->db);
    $balanceDate = $this->obtainBalanceDate();
    return $incomes->showIncoms($balanceDate[0],$balanceDate[1]);
  }

  public function showExpenses(){
    $expenses = new Expenses($this->db);
    $balanceDate = $this->obtainBalanceDate();
    return $expenses->showExpenses($balanceDate[0],$balanceDate[1]);
  }



  public function parsePath(){
    if (isset($_SERVER['REQUEST_URI'])) {
      $request_path = explode('?', $_SERVER['REQUEST_URI']);
      $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/');
      $pathCallUtf8 = substr(urldecode($request_path[0]), strlen($base) + 1);
      $pathCall = utf8_decode($pathCallUtf8);
      if ($pathCall == basename($_SERVER['PHP_SELF'])) {
        $pathCall = false;
      }
    }
    return $pathCall;
  }
}
