<?php
class Budget {
  private $db = null;

  public function __construct($host, $user, $pass, $db){
    $this->db = $this->initDB($host, $user, $pass, $db);
  }

  private function initDB($host, $user, $pass, $db){
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
    $users = new Users ($this->db);
    return $users->login();
  }

  public function logout(){
    $users = new Users ($this->db);
    return $users->logout();
  }

  public function register(){
    $users = new Users ($this->db);
    return $users->register();
  }

  public function editUserData(){
    $users = new Users ($this->db);
    switch($_POST['operation']):
      case 'editUserData' :
        return $users->editUserData();
        break;
      case 'editUserPassword' :
        $users->changPassword();
        break;
    endswitch;
  }

  public function getLeggedUserEmail(){
    $users = new Users ($this->db);
    return $users->getEmailAdress();
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

  public function checkHowManySpentInCategoryAndLimit(){
    $expensesView = new ExpensesView($this->db);
    $expensesView->alertExceededCategoryLimit();
  }

  public function editExpense(){
    $expenses = new Expenses($this->db);
    return $expenses->edit();
  }

  public function deleteExpense(){
    $expenses = new Expenses($this->db);
    return $expenses->delete();
  }

  private function generateCategoryHtml($categoryArray, $where){
    foreach($categoryArray as $category){
      if($category[1] == $category[0]){
        echo "<div class=\"radio mainCategory\">
                <label><input type=\"radio\" name=\"categorys\" value=\"$category[0]\" />";
         echo "<span class='checkmark'></span>";
         if(count(array_keys(array_column($categoryArray,'parent_category_id' ),$category[1])) > 1)
         echo "<span class=\"glyphicon glyphicon-chevron-up\" ></span>";
         if($where == 'settings'){
          echo "<button class=\"btn btn-xs btn-danger delete\">
                    <span class=\"glyphicon glyphicon glyphicon glyphicon-trash\" aria-hidden=\"true\"></span>
                  </button>
                  <button class=\"btn btn-xs btn-primary edit\">
                    <span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>
                  </button>";
          }
        echo   "<p class='category-name'>$category[2]</p>";
        $subCategory = $this->generateSubCategoryHTML($categoryArray, $where, $category[0]);
        if(isset($category['limit_amount'])){
          $limitAmount = number_format($category['limit_amount'], 2, ',', ' ');
          $subcategoryAndMainCategorySumlimitAmount = number_format(($category['limit_amount'] + $subCategory['limiSum']), 2, ',', ' ');
          if($subCategory['limiSum'] <= 0){
            echo "Limit: <span class='categoryLimit'>".$subcategoryAndMainCategorySumlimitAmount."</span>";
          } else {
            echo "Limit: <span class='wholeCategoryLimit'>".$subcategoryAndMainCategorySumlimitAmount."</span>";
            echo " (<span class='categoryLimit'>".$limitAmount."</span>)";
          }

        }
        echo   "</label>
              </div>";
      if($where == 'addSubcategory') continue;
        echo $subCategory['html'];
      }
    }
  }

  private function generateSubCategoryHTML($categoryArray, $where, $parentCategory){
    $sumOfSubcategoryLimit = 0;
    $html = "";
    $html .= "<div class=\"subCategory\">";
    foreach($categoryArray as $subCategory){
        if($subCategory[1] == $parentCategory && $subCategory[0] != $subCategory[1]){
          $html .= "<div class=\"radio \">
                  <label><input type=\"radio\" name=\"categorys\" value=\"$subCategory[0]\" />
                  <span class='checkmark'></span>";
           if($where == 'settings'){
            $html .= "<button class=\"btn btn-xs btn-danger delete\">
                    <span class=\"glyphicon glyphicon glyphicon glyphicon-trash\" aria-hidden=\"true\"></span>
                  </button>
                  <button class=\"btn btn-xs btn-primary edit\">
                    <span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>
                  </button>";
             }
           $html .= "<p class='category-name'>$subCategory[2]</p>";
           if(isset($subCategory['limit_amount'])){
             $sumOfSubcategoryLimit += $subCategory['limit_amount'];
             $limitAmount = number_format($subCategory['limit_amount'], 2, ',', ' ');
             $html .= "Limit: <span class='categoryLimit'>".$limitAmount."</span>";
           }
           $html .=    "</label>
           </div>";
        }
      }
    $html .= "</div>";
    return array(
      'html' => $html,
      'limiSum' => $sumOfSubcategoryLimit
    );
  }

  public function modificationOfPaymentMethod(){
    $action = $_POST['operation'];
    $expenses = new Expenses($this->db);
    switch($action){
      case 'add':
        $expenses->addPaymentMethod();
        break;
      case 'edit':
        $expenses->editPaymentMethod();
        break;
      case 'delete':
        $expenses->deletePaymentMethod();
        break;
    }
  }

  public function showExpensPaymentMethod($where) {

    $expense = new Expenses($this->db);
    if($where =="settings"){
      $expense->showExpensPaymentMethodSetting();
    } elseif($where =="addExpense"){
      $expense->showExpensPaymentMethod();
    } elseif($where =="settingsModal"){
      $expense->showExpensPaymentMethodSettingModal();
    }
  }

  public function showExpensCategory($where) {
    $userId = $_SESSION['loggedUser']['id'];
    $categoryQuery = $this->db->query("SELECT id, parent_category_id ,name, limit_amount FROM expenses_category_assigned_to_users WHERE user_id = $userId");
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

  public function addIncomeSubcategory(){
    $incomes = new Incomes($this->db);
    return $incomes->addIncomeSubcategory();
  }

  public function addIncomeCategory(){
    $incomes = new Incomes($this->db);
    return $incomes->addIncomeCategory();
  }

  public function addExpenseSubcategory(){
    $expense = new Expenses($this->db);
    return $expense->addExpenseSubcategory();
  }

  public function addExpenseCategory(){
    $expense = new Expenses($this->db);
    return $expense->addExpenseCategory();
  }

  public function deleteCategory(){
    if($_POST['categoryType'] == "income"){
      $incomes = new Incomes($this->db);
      return $incomes->deleteCategory();
    } elseif ($_POST['categoryType'] == "expense") {
      $expense = new Expenses($this->db);
      return $expense->deleteCategory();
    }
  }

  public function loadCategory(){
    $where = "settings";
    if(isset($_POST['where'])) $where = "addSubcategory";
    if($_POST['categoryType'] == "income"){
      return $this->showIncomsCategory($where);
    } elseif ($_POST['categoryType'] == "expense") {
      return $this->showExpensCategory($where);
    }
  }

  public function loadPieceOfPage(){
    if(isset($_POST['sectionName'])){
      switch($_POST['sectionName']){
        case 'listOfPayentMethod':
          $this->showExpensPaymentMethod('settings');
          break;
        case 'listOfPayentMethodModal':
          $this->showExpensPaymentMethod('settingsModal');
          break;
      }
    }
  }

  public function editCategory(){
    if($_POST['categoryType'] == "income"){
      $incomes = new Incomes($this->db);
      $incomes->editCategory();
    } elseif ($_POST['categoryType'] == "expense") {
      $expense = new Expenses($this->db);
      $expense->editCategory();
    }
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

  public function deleteAllUserItems(){
    $users   = new Users ($this->db);
    $incomes = new Incomes($this->db);
    $expense = new Expenses($this->db);
    if($users->checkPassword($_POST['pass'])){
      $incomes->deleteAllUserItems();
      $expense->deleteAllUserItems();
      echo json_encode(array('ok'));
    } else {
      echo json_encode(array('e_wrong_pass' => "Błędne hasło."));
    }
  }

  public function deleteUserAccount(){
    $users   = new Users ($this->db);
    $incomes = new Incomes($this->db);
    $expense = new Expenses($this->db);
    if($users->checkPassword($_POST['pass'])){
      $incomes->deleteAllUserItems();
      $expense->deleteAllUserItems();
      $incomes->deleteAllUserCategory();
      $expense->deleteAllUserCategory();
      $expense->deleteAllUserPaymentMethod();
      $users->deleteUserAccount();
      echo json_encode(array('ok'));
    }else {
      echo json_encode(array('e_wrong_pass' => "Błędne hasło."));
    }
  }

    /**
     * @return Expenses
     */
    public function getExpenses()
    {
        return new Expenses($this->db);
    }

}
