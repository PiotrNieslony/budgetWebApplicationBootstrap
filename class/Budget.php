<?php
class Budget {
  private $db = null;

  public function __construct($host, $user, $pass, $db){
    $this->db = $this->initDB($host, $user, $pass, $db);
  }

  public function initDB($host, $user, $pass, $db){
    try{
        $db = new PDO("mysql:host={$host};dbname={$db};charset = utf8",$user, $pass, [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        return $db;
    } catch (PDOException $error) {
        echo $error->getMessage();
        exit('Database error');
    }
  }

  public function getActualUser() {
    if(isset($_SESSION['loggedUser'])){
      $_SESSION['loggedUser'];
    }
    else{
      return null;
    }
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
        require_once 'dbconnect.php';
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

    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

    if((filter_var($emailB, FILTER_VALIDATE_EMAIL)) == false || ($emailB != $email)){
        $validation = false;
        $_SESSION['e_email'] = "Podaj poprawny adres email.";
    } else {
        require_once 'dbconnect.php';
        $query = $this->db->prepare('SELECT id FROM users WHERE email = :email');
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount()){
            $validation = false;
            $_SESSION['e_email'] = "Ten adres  email został już użyty do rejestracji konta.";
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
        require_once 'dbconnect.php';
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

  public function showIncomsCategory() {
    $categoryQuery = $this->db->prepare('SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = :user_id');
    $categoryQuery->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
    $categoryQuery->execute();
    $categorys = $categoryQuery->fetchAll();
    foreach($categorys as $category){
        echo "<div class=\"radio\">
                    <label><input type=\"radio\" name=\"categorys\" value=\"$category[0]\" />$category[1]</label>
                </div>";
    }
    echo (isset($_SESSION['e_categorys'])) ? "<p class='alert alert-danger'>".$_SESSION['e_categorys']."</p>" : "";
    unset($_SESSION['e_categorys']);
  }

  public function addExpense(){
    $expenses = new Expenses($this->db);
    return $expenses->add();
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

  public function showExpensCategory() {
    $categoryQuery = $this->db->prepare('SELECT id, parent_category_id ,name FROM expenses_category_assigned_to_users WHERE user_id = :user_id');
    $categoryQuery->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
    $categoryQuery->execute();
    $categorys = $categoryQuery->fetchAll();

    foreach($categorys as $category){
      if($category[1] == null){
        echo "<div class=\"radio mainCategory\" id=\"expenseCategory\">
                <label><input type=\"radio\" name=\"categorys\" value=\"$category[0]\" />$category[2]</label>
            </div>
            <div class=\"subCategory\">";

        foreach($categorys as $subCategory)
            if($subCategory[1] == $category[0]){
              echo "<div class=\"radio\" id=\"expenseCategory\">
                      <label><input type=\"radio\" name=\"categorys\" value=\"$subCategory[0]\" />$subCategory[2]</label>
                  </div>";
            }
        echo "</div>";
      }
    }
    echo (isset($_SESSION['e_categorys'])) ? "<p class='alert alert-danger'>".$_SESSION['e_categorys']."</p>" : "";
    unset($_SESSION['e_categorys']);
  }

  public function balance() {
    $loggedUserId = $_SESSION['loggedUser']['id'];
    $firstDayOfMonth = new DateTime('first day of this month');
    $lastDayOfMonth = new DateTime('first day of this month');
    $today = new DateTime();
    if(isset($_POST['date-scope'])){
        if($_POST['date-scope'] == "current-month"){
            $balaceDateFrom = $firstDayOfMonth->format('Y-m-d');
            $balaceDateTo   = $today->format('Y-m-d');
            $_SESSION['current-month'] = true;
        } elseif ($_POST['date-scope'] == "previous-month"){
            $firstDayOfMonth->modify('first day of previous month');
            $lastDayOfMonth->modify('last day of previous month');
            $balaceDateFrom = $firstDayOfMonth->format('Y-m-d');
            $balaceDateTo   = $lastDayOfMonth->format('Y-m-d');
            $_SESSION['previous-month'] = true;
        }
    } elseif (isset($_POST['dateFrom'])){
        $balaceDateFrom = $_POST['dateFrom'];
        $balaceDateTo   = $_POST['dateTo'];
        $_SESSION['custom'] = true;
    } else {
        $balaceDateFrom = $firstDayOfMonth->format('Y-m-d');
        $balaceDateTo   = $today->format('Y-m-d');
    }

    $queryIncoms = $this->db->query("SELECT  icatu.name AS category, SUM(incomes.amount) AS amount
                    FROM incomes
                    INNER JOIN
                    incomes_category_assigned_to_users icatu
                    ON incomes.income_category_assigned_to_user_id = icatu.id
                    WHERE incomes.user_id = $loggedUserId
                    AND date_of_income >= '$balaceDateFrom'
                    AND date_of_income <= '$balaceDateTo'
                    GROUP BY incomes.income_category_assigned_to_user_id
                    ORDER BY SUM(incomes.amount) DESC;");
    $queryExpens = $this->db->query("SELECT  ecatu.name AS category, SUM(expenses.amount) AS amount
                    FROM expenses
                    INNER JOIN
                    expenses_category_assigned_to_users ecatu
                    ON expenses.expense_category_assigned_to_user_id = ecatu.id
                    WHERE expenses.user_id = $loggedUserId
                    AND date_of_expense >= '$balaceDateFrom'
                    AND date_of_expense <= '$balaceDateTo'
                    GROUP BY expenses.expense_category_assigned_to_user_id
                    ORDER BY SUM(expenses.amount) DESC;");
    $incomes = $queryIncoms->fetchAll();
    $expenses = $queryExpens->fetchAll();
    $_SESSION['selected-date-from'] = $balaceDateFrom;
    $_SESSION['selected-date-to'] = $balaceDateTo;
    return array($incomes, $expenses);
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
