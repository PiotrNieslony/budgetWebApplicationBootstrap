<?php
class Expenses {
  private $db = null;
  function __construct($db){
    $this->db = $db;
  }

  public function add(){
    if(!isset($_POST['expenseAmount'])) return false;
    $validationCorrect = true;
    //Amount validation
    if(!is_numeric($_POST['expenseAmount'])){
        $validationCorrect = false;
        $_SESSION['e_expenseAmount'] = "Wpisz poprawną kwotę (liczba)";
    }

    if($_POST['expenseAmount'] > 999999.99 || $_POST['expenseAmount'] < 0 ){
        $validationCorrect = false;
        $_SESSION['e_expenseAmount'] = "Wprowadź poprawną kwotę (od 0 dd 999 999.99)";
    }
    //date Validation
    $timezone = new DateTimeZone('Europe/Warsaw');
    $expenseDate = DateTime::createFromFormat('Y-m-d',$_POST['expenseDate'] ,$timezone);
    if($expenseDate){
      if(!checkdate($expenseDate->format('m'), $expenseDate->format('d'), $expenseDate->format('Y'))){
          $validationCorrect = false;
          $_SESSION['e_expenseDate'] = "Wprowadź poprawną datę w formiecie np. 2010-01-01";
      }
      $bottomRangOfDate = DateTime::createFromFormat('Y-m-d',"2001-01-01",$timezone);
      $upperRangOfDate =  new DateTime('NOW');
      if($expenseDate < $bottomRangOfDate && $expenseDate > $upperRangOfDate){
          $validationCorrect = false;
          $_SESSION['e_expenseDate'] = "Podaj datę w zakresie od 2010-01-01 do $upperRangOfDate->format('Y-m-d')";
      }
    } else {
      $_SESSION['e_expenseDate'] = "Wprowadź poprawną datę w formiecie np. 2010-01-01";
    }

    //category validation
    if(!isset($_POST['categorys'])){
        $validationCorrect = false;
        $_SESSION['e_categorys'] = "Wybierz kategorię";
    }

    if($validationCorrect){
        $query = $this->db->prepare('INSERT INTO expenses VALUES (NULL, :user_id, :expense_category_assigned_to_user_id, :payment_method_assigned_to_user_id, :amount, :date_of_expense, :expense_comment )');
        $query->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
        $query->bindValue(':expense_category_assigned_to_user_id', $_POST['categorys'] , PDO::PARAM_INT);
        $query->bindValue(':payment_method_assigned_to_user_id', $_POST['paymentType'], PDO::PARAM_INT);
        $query->bindValue(':amount', $_POST['expenseAmount'],  PDO::PARAM_INT);
        $query->bindValue(':date_of_expense', $_POST['expenseDate'],  PDO::PARAM_STR);
        $query->bindValue(':expense_comment', $_POST['expenseComment'],  PDO::PARAM_STR);
        $query->execute();
        $categoryId = $_POST['categorys'];
        $query2 = $this->db->query("SELECT name FROM expenses_category_assigned_to_users WHERE id = $categoryId");
        $row = $query2->fetch();
        $categoryName = $row[0];
        $_SESSION['success'] = "Dodano wydatek ".$_POST['expenseAmount']." zł do kategorii <b>".$categoryName."</b> ";
        return true;
    }
    return false;
  }

  public function edit(){
    if(!isset($_POST['expenseAmount'])) return false;
    $validationCorrect = true;
    $errors = array();
    //Amount validation
    if(!is_numeric($_POST['expenseAmount'])){
        $validationCorrect = false;
        $errors['e_expenseAmount'] = "Wpisz poprawną kwotę (liczba)";
    }

    if($_POST['expenseAmount'] > 999999.99 || $_POST['expenseAmount'] < 0 ){
        $validationCorrect = false;
        $errors['e_expenseAmount'] = "Wprowadź poprawną kwotę (od 0 dd 999 999.99)";
    }
    //date Validation
    $timezone = new DateTimeZone('Europe/Warsaw');
    $expenseDate = DateTime::createFromFormat('Y-m-d',$_POST['expenseDate'] ,$timezone);
    if($expenseDate){
      if(!checkdate($expenseDate->format('m'), $expenseDate->format('d'), $expenseDate->format('Y'))){
          $validationCorrect = false;
          $errors['e_expenseDate'] = "Wprowadź poprawną datę w formiecie np. 2010-01-01";
      }
      $bottomRangOfDate = DateTime::createFromFormat('Y-m-d',"2001-01-01",$timezone);
      $upperRangOfDate =  new DateTime('NOW');
      if($expenseDate < $bottomRangOfDate && $expenseDate > $upperRangOfDate){
          $validationCorrect = false;
          $errors['e_expenseDate'] = "Podaj datę w zakresie od 2010-01-01 do $upperRangOfDate->format('Y-m-d')";
      }
    } else {
      $validationCorrect = false;
      $errors['e_expenseDate'] = "Wprowadź poprawną datę w formiecie np. 2010-01-01";
    }

    //category validation
    if(!isset($_POST['categorys'])){
        $validationCorrect = false;
        $errors['e_categorys'] = "Wybierz kategorię";
    }

    if($validationCorrect){
        $query = $this->db->prepare('UPDATE expenses SET
                                        expense_category_assigned_to_user_id = :expense_category_assigned_to_user_id,
                                        payment_method_assigned_to_user_id = :payment_method_assigned_to_user_id,
                                        amount = :amount,
                                        date_of_expense= :date_of_expense,
                                        expense_comment = :expense_comment
                                        WHERE id = :expenseID AND user_id = :user_id
                                        ');
        $query->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
        $query->bindValue(':expense_category_assigned_to_user_id', $_POST['categorys'] , PDO::PARAM_INT);
        $query->bindValue(':payment_method_assigned_to_user_id', $_POST['paymentType'], PDO::PARAM_INT);
        $query->bindValue(':amount', $_POST['expenseAmount'],  PDO::PARAM_INT);
        $query->bindValue(':date_of_expense', $_POST['expenseDate'],  PDO::PARAM_STR);
        $query->bindValue(':expense_comment', $_POST['expenseComment'],  PDO::PARAM_STR);
        $query->bindValue(':expenseID',  $_POST['expenseID'], PDO::PARAM_INT);
        $query->execute();
        $output = array('ok');
        echo json_encode($output);
    } else {
      echo json_encode($errors);
    }
  }

  public function delete(){
    if(!isset($_POST['expenseID'])) return false;
    $errors = array();
    $query = $this->db->prepare('DELETE FROM expenses WHERE id  = :expenseID AND user_id = :user_id');
    $query->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
    $query->bindValue(':expenseID',  $_POST['expenseID'], PDO::PARAM_INT);
    $query->execute();
    $output = array('ok');
    echo json_encode($output);
  }

  public function sumExpenses($balaceDateFrom,$balaceDateTo){
    $loggedUserId = $_SESSION['loggedUser']['id'];
    $queryExpens = $this->db->query(
          "SELECT SUM(expenses.amount) AS sum
            FROM expenses
            WHERE expenses.user_id = $loggedUserId
            AND date_of_expense >= '$balaceDateFrom'
            AND date_of_expense <= '$balaceDateTo';");
    $expenses = $queryExpens->fetchAll();
    return $expenses[0]['sum'];
  }

  public function showExpenses($balaceDateFrom, $balaceDateTo) {
    $loggedUserId = $_SESSION['loggedUser']['id'];
    $queryExpens = $this->db->query(
                    "SELECT  epcatu.id AS id, epcatu.name AS category, SUM(expenses.amount) AS amount
                    FROM expenses
                    INNER JOIN expenses_category_assigned_to_users ecatu
                    ON expenses.expense_category_assigned_to_user_id = ecatu.id
                    INNER JOIN expenses_parent_category_assigned_to_users epcatu
                    ON ecatu.parent_category_id = epcatu.id
                    WHERE expenses.user_id = $loggedUserId
                    AND date_of_expense >= '$balaceDateFrom'
                    AND date_of_expense <= '$balaceDateTo'
                    GROUP BY ecatu.parent_category_id
                    ORDER BY SUM(expenses.amount) DESC;");
    $expensesArray = $queryExpens->fetchAll();
    $counter = 1;
    $expenses = new Expenses($this->db);
    $sum = $expenses->sumExpenses($balaceDateFrom,$balaceDateTo);
    $subCategory;
    foreach ($expensesArray as $expensRow) {
      echo "<tr id=\"$expensRow[0]\"><td>$counter</td>";
      $counter++;
      for ($i = 1; $i < 3; $i++) {
          echo "<td>";
          echo $expensRow[$i];
          echo "</td>";
      }
      echo "<td><button class=\"btn btn-xs btn-primary extend\"><span class=\"glyphicon glyphicon-chevron-down\" aria-hidden=\"true\"></span></button></td>";
      echo "</tr>";
      if($subCategory = $this->showSubCategory($expensRow[0],$balaceDateFrom, $balaceDateTo)){
        echo "<tr style='display:none'><td colspan=\"4\">";
        echo $subCategory;
        echo "</td></tr>";
      }
    }
    echo "<tr><td colspan=\"2\">Suma</td><th>$sum</th><td></td></tr>";
    $_SESSION['selected-date-from'] = $balaceDateFrom;
    $_SESSION['selected-date-to'] = $balaceDateTo;
    return $expensesArray;
  }

  private function validatePaymentMethodName($paymentMethodName){
    $validationResult = array(
      'validationCorrect' => true,
      'errors' => array(
        'e_paymentMethod'=>""
      )
    );
    if((strlen($paymentMethodName)<1) || ((strlen($paymentMethodName)>25))) {
      $validationResult['validationCorrect'] = false;
      $validationResult['errors']['e_paymentMethod'] = "Nazwa musi posiadać od 1 do 25 znaków.";
    }

    $query = $this->db->prepare('SELECT id FROM payment_methods_assigned_to_users
                                WHERE user_id = :user_id AND name = :payment_method_name ');
    $query->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
    $query->bindValue(':payment_method_name', $paymentMethodName,  PDO::PARAM_STR);
    $query->execute();

    if($query->rowCount() > 0){
      $validationResult['validationCorrect'] = false;
      $validationResult['errors']['e_paymentMethod'] = "Metoda płatności o takiej nazwie już istnieje";
    }
    return $validationResult;
  }

  public function addPaymentMethod(){
    $validationCorrect = true;
    $paymentMethodName = $_POST['paymentMethod'];
    $errors = array();
    if((strlen($paymentMethodName)<1) || ((strlen($paymentMethodName)>25))) {
      $validationCorrect = false;
      $errors['e_paymentMethod'] = "Nazwa musi posiadać od 1 do 25 znaków.";
    }

    $query = $this->db->prepare('SELECT name FROM payment_methods_assigned_to_users
                                WHERE user_id = :user_id AND name = :payment_method_name ');
    $query->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
    $query->bindValue(':payment_method_name', $paymentMethodName,  PDO::PARAM_STR);
    $query->execute();

    if($query->rowCount() > 0){
      $validationCorrect = false;
      $errors['e_paymentMethod'] = "Metoda płatności o takiej nazwie już istnieje";
    }


    //$sql = "INSERT INTO payment_methods_assigned_to_users VALUES(NULL, $userID, $paymentMethodName)";
    if($validationCorrect){
      $query = $this->db->prepare('INSERT INTO payment_methods_assigned_to_users VALUES(NULL, :user_id, :payment_method_name)');
      $query->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
      $query->bindValue(':payment_method_name', $paymentMethodName,  PDO::PARAM_STR);
      $query->execute();
      echo json_encode(array("ok"));
    } else {
      echo json_encode($errors);
    }
  }

  public function editPaymentMethod(){
    $paymentMethodID   = $_POST['paymentMethodID'];
    $paymentMethodName = $_POST['paymentName'];
    $validation = $this->validatePaymentMethodName($paymentMethodName);

    //$sql = "INSERT INTO payment_methods_assigned_to_users VALUES(NULL, $userID, $paymentMethodName)";
    if($validation['validationCorrect']){
      $query = $this->db->prepare('UPDATE payment_methods_assigned_to_users SET name = :payment_method_name
                                  WHERE user_id = :user_id AND id = :id');
      $query->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
      $query->bindValue(':id', $paymentMethodID, PDO::PARAM_INT);
      $query->bindValue(':payment_method_name', $paymentMethodName,  PDO::PARAM_STR);
      $query->execute();
      echo json_encode(array("ok"));
    } else {
      echo json_encode($validation['errors']);
    }
  }

  public function deletePaymentMethod(){
    $validationCorrect = true;
    $userID = $_SESSION['loggedUser']['id'];
    $paymentMethodID = $_POST['paymentMethodID'];
    $errors = array();
    if(isset( $_POST['selectedMethod'])){
      $paymentMethodSelectedID = $_POST['selectedMethod'];
    } else {
      $validationCorrect = false;
      $errors['e_paymentMetod'] = "Wybierz metodę płatności do której mają być przypisane wydatki.";
    }

    if($validationCorrect){
      $sqlDeletePaymentMetod = "DELETE FROM payment_methods_assigned_to_users WHERE id = $paymentMethodID AND user_id = $userID";
      $sqlChangPaymentMethodInExpenses = "UPDATE expenses
      SET payment_method_assigned_to_user_id = $paymentMethodSelectedID
      WHERE payment_method_assigned_to_user_id = $paymentMethodID AND user_id = $userID";
      $this->db->query($sqlDeletePaymentMetod);
      $this->db->query($sqlChangPaymentMethodInExpenses);
      echo json_encode(array("ok"));
    } else {
      echo json_encode($errors);
    }
  }

  private function getPaymentMethod(){
    $paymentMethodQuery = $this->db->prepare('SELECT id, name FROM payment_methods_assigned_to_users WHERE user_id = :user_id');
    $paymentMethodQuery->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
    $paymentMethodQuery->execute();
    return $paymentMethodQuery->fetchAll();
  }

  public function showExpensPaymentMethod() {
    $paymentMethods = $this->getPaymentMethod();
    foreach($paymentMethods as $paymentMethod){
      echo "<option value=\"$paymentMethod[0]\">$paymentMethod[1]</option>";
    }
  }

  public function showExpensPaymentMethodSetting() {
    $paymentMethods = $this->getPaymentMethod();
    foreach($paymentMethods as $paymentMethod){
      echo "<li class=\"list-group-item\" data-payment-method-id=\"$paymentMethod[0]\">$paymentMethod[1]
      <button class=\"btn btn-xs btn-danger delete\">
        <span class=\"glyphicon glyphicon glyphicon glyphicon-trash\" aria-hidden=\"true\"></span>
      </button>
      <button class=\"btn btn-xs btn-primary edit\">
        <span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span>
      </button>
      </li>";
    }
  }

  public function showExpensPaymentMethodSettingModal(){
    if(isset($_POST['paymentMethodID'])) $paymentMethodID = $_POST['paymentMethodID'];
    $paymentMethods = $this->getPaymentMethod();
    $key = array_search($paymentMethodID, array_column($paymentMethods, 'id'));
    unset($paymentMethods[$key]);
    $this->generateRadioList($paymentMethods, "payment-method");
  }

  private function generateRadioList($categoryArray, $radioListNaem){
    foreach($categoryArray as $category){
        echo "<div class=\"radio\">
                <label><input type=\"radio\" name=\"$radioListNaem\" value=\"$category[0]\" />$category[1]
                <span class='checkmark'></span>
                  </label>
              </div>";
      }
    }

  public function showSubCategory($parentCategoryId, $balaceDateFrom, $balaceDateTo){
    $loggedUserId = $_SESSION['loggedUser']['id'];
    $queryExpens = $this->db->query("SELECT  ecatu.id AS id , ecatu.name AS category, SUM(expenses.amount) AS amount
                                    FROM expenses
                                    INNER JOIN
                                    expenses_category_assigned_to_users ecatu
                                    ON expenses.expense_category_assigned_to_user_id = ecatu.id
                                    WHERE expenses.user_id = $loggedUserId
                                    AND ecatu.parent_category_id = '$parentCategoryId'
                                    AND date_of_expense >= '$balaceDateFrom'
                                    AND date_of_expense <= '$balaceDateTo'
                                    GROUP BY expenses.expense_category_assigned_to_user_id
                                    ORDER BY SUM(expenses.amount) DESC;");
    $rowco =$queryExpens->rowCount();
    if ($queryExpens->rowCount() == 0) return false;
    $expenses = $queryExpens->fetchAll();
    $counter = 1;
    $sum = 0;
    $subTable = "";
    $subTable .= "<table class=\"table table-striped table-bordered table-hover sub-category-table\">";
    $subTable .= "<thead><tr><th>l.p.</th><th>Pod kategoria</th><th>Wartość</th><th></th></thead>";
    $subTable .= "<tbody>";
    foreach ($expenses as $expensRow) {
      if($expensRow[0] == $parentCategoryId) $expensRow[1] = "inne";
      $subTable .=  "<tr id=\"$expensRow[0]\"><td>$counter</td>";
      $counter++;
      for ($i = 1; $i < 3; $i++) {
          $subTable .=  "<td>";
          $subTable .=  $expensRow[$i];
          $subTable .=  "</td>";
          $sum += $expensRow[2];
      }
      $subTable .= "<td><button class=\"btn btn-xs btn-info extend\"><span class=\"glyphicon glyphicon-chevron-down\" aria-hidden=\"true\"></span></button></td>";
      $subTable .=  "</tr>";
      if($subCategory = $this->showExpensesItemsAssignedToCategory($expensRow[0], $balaceDateFrom, $balaceDateTo)){
        $subTable .= "<tr style='display:none'><td colspan=\"4\">";
        $subTable .= $subCategory;
        $subTable .= "</td></tr>";

      }
    }
    $subTable .= "</tbody></table>";
    return $subTable;
  }

  public function showExpensesItemsAssignedToCategory($CategoryId, $balaceDateFrom, $balaceDateTo){
    $loggedUserId = $_SESSION['loggedUser']['id'];
    $queryExpens = $this->db->query("SELECT  e.id, e.date_of_expense, pmatu.name, e.amount, e.expense_comment
                    FROM expenses e
                    INNER JOIN
                    payment_methods_assigned_to_users pmatu
                    ON e.payment_method_assigned_to_user_id = pmatu.id
                    WHERE e.user_id = $loggedUserId
                    AND expense_category_assigned_to_user_id = '$CategoryId'
                    AND date_of_expense >= '$balaceDateFrom'
                    AND date_of_expense <= '$balaceDateTo'");
    $rowco =$queryExpens->rowCount();
    if ($queryExpens->rowCount() == 0) return false;
    $expenses = $queryExpens->fetchAll();
    $counter = 1;
    $subTable = "";
    $subTable .= "<table class=\"table table-bordered table-striped table-hover sub-category-table\">";
    $subTable .= "<thead><tr><th>l.p.</th><th>data</th><th>sposób płatności</th><th>kwota</th><th class=\"visible-sm visible-md visible-lg\">Komentarz</th><th></th></tr></thead>";
    $subTable .= "<tbody>";
    $counter = 0;
    foreach ($expenses as $expensRow) {
      $counter++;
      $subTable .=  "<tr id=\"$expensRow[0]\"><td>$counter</td>";
      for ($i = 1; $i < 5; $i++) {
          if($i == 4) $subTable .=  "<td class=\"visible-sm visible-md visible-lg\">";
          else $subTable .=  "<td>";
          $subTable .=  $expensRow[$i];
          $subTable .=  "</td>";
      }
      $subTable .= "<td>
      <button class=\"btn btn-xs btn-danger delete\"><span class=\"glyphicon glyphicon glyphicon glyphicon-trash
      \" aria-hidden=\"true\"></span></button>
            <button class=\"btn btn-xs btn-primary edit\"><span class=\"glyphicon glyphicon-pencil
            \" aria-hidden=\"true\"></span></button></td>";
      $subTable .=  "</tr>";
    }
    $subTable .= "</tbody></table>";
    return $subTable;
  }

  public function addExpenseSubcategory() {
    $validationCorrect = true;
    $categoryName = $_POST['categoryName'];
    $errors = array();
    if((strlen($categoryName)<1) || ((strlen($categoryName)>25))) {
      $validationCorrect = false;
      $errors['e_categoryName'] = "Nazwa musi posiadać od 1 do 25 znaków.";
    }

    if((!empty($_POST['categoryName'])) && (!empty($_POST['categorys']))){
      try{
        $query = $this->db->prepare('SELECT name FROM expenses_category_assigned_to_users
                                    WHERE
                                    user_id = :user_id AND
                                    parent_category_id  = :parent_category_id AND
                                    name = :category_name
                                    ');
        $query->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
        $query->bindValue(':parent_category_id', $_POST['categorys'] , PDO::PARAM_INT);
        $query->bindValue(':category_name', $_POST['categoryName'],  PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount() != 0) {
          $errors['e_categoryName'] = "Podkategoria o takiej nazwie już istnieje. ";
          $validationCorrect = false;
        }
      }
     catch(Exception $e){
        $errors['Błąd: '] = $e->getMessage();
        echo json_encode($errors);
      }
    }


    if(!isset($_POST['categorys'])){
      $validationCorrect = false;
      $errors['e_parentCategory'] = "Wybierz kategorię";
    }

    if($validationCorrect){
      try{
        $query = $this->db->prepare('INSERT INTO expenses_category_assigned_to_users
                                    VALUES(
                                      NULL,
                                      :user_id,
                                      :parent_category_id,
                                      :category_name
                                      )
                                    ');
        $query->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
        $query->bindValue(':parent_category_id', $_POST['categorys'] , PDO::PARAM_INT);
        $query->bindValue(':category_name', $_POST['categoryName'],  PDO::PARAM_STR);
        $query->execute();
        $output = array('ok');
        echo json_encode($output);
      }
     catch(Exception $e){
        $errors['e_categoryName'] = $e->getMessage();
        echo json_encode($errors);
      }
    } else {
      echo json_encode($errors);
    }


  }

  public function addExpenseCategory() {
    $validationCorrect = true;
    $categoryName = $_POST['categoryName'];
    $errors = array();
    if((strlen($categoryName)<1) || ((strlen($categoryName)>25))) {
      $validationCorrect = false;
      $errors['e_categoryName'] = "Nazwa musi posiadać od 1 do 25 znaków.";
    }

    try{
      $query = $this->db->prepare('SELECT name FROM expenses_category_assigned_to_users
                                  WHERE
                                  user_id = :user_id AND
                                  id  = parent_category_id AND
                                  name = :category_name
                                  ');
      $query->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
      $query->bindValue(':category_name', $_POST['categoryName'],  PDO::PARAM_STR);
      $query->execute();
      if($query->rowCount() != 0) {
        $errors['e_categoryName'] = "Kategoria o takiej nazwie już istnieje. ";
        $validationCorrect = false;
      }
    }
   catch(Exception $e){
      $errors['e_categoryName'] = $e->getMessage();
      echo json_encode($errors);
    }

    if($validationCorrect){
      try{
        $query = $this->db->prepare('INSERT INTO expenses_category_assigned_to_users
                                      SELECT (MAX(id)+1), :user_id , (MAX(id)+1), :category_name
                                      FROM expenses_category_assigned_to_users
                                    ');
        $query->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
        $query->bindValue(':category_name', $_POST['categoryName'],  PDO::PARAM_STR);
        $query->execute();
        $output = array('ok');
        echo json_encode($output);
      }
     catch(Exception $e){
        $errors['e_categoryName'] = $e->getMessage();
        echo json_encode($errors);
      }
    } else {
      echo json_encode($errors);
    }


  }

  public function deleteCategory(){
    $categoryID  = $_POST['categoryID'];
    $subCategory = $_POST['subCategory'];
    $userID      = $_SESSION['loggedUser']['id'];
    $errors       = array();
    if($subCategory){
      $sqlDeleteItems  =
              "DELETE FROM expenses
              WHERE expense_category_assigned_to_user_id  = $categoryID
              AND user_id = $userID";
      $sqlDeleteCategory =
              "DELETE FROM expenses_category_assigned_to_users
              WHERE id  = $categoryID AND user_id = $userID";
    } else {
      $sqlDeleteItems  =
              "DELETE e FROM expenses e
              INNER JOIN expenses_category_assigned_to_users exatu
              ON e.expense_category_assigned_to_user_id = exatu.id
              WHERE exatu.parent_category_id  = $categoryID
              AND e.user_id = $userID";
      $sqlDeleteCategory =
              "DELETE FROM expenses_category_assigned_to_users
              WHERE parent_category_id  = $categoryID AND user_id = $userID";
    }
    try{
      $this->db->query($sqlDeleteItems);
      $this->db->query($sqlDeleteCategory);
    } catch(Exception $e){
      $error['e_db'] = $e->getMessage();
      echo json_encode($errors);
    }
    $output = array('ok');
    echo json_encode($output);
  }

  public function editCategory(){
    $categoryID       = $_POST['categoryID'];
    $subCategory      = $_POST['subCategory'];
    $categoryName          = $_POST['categoryName'];
    if(isset($_POST['parentCategoryID']))
      $parentCategoryID = $_POST['parentCategoryID'];
    else
      $parentCategoryID = $categoryID;
    $userID           = $_SESSION['loggedUser']['id'];
    $errors           = array();
    if($subCategory){
      try{
        $query = $this->db->prepare(
              "UPDATE expenses_category_assigned_to_users
              SET
              parent_category_id = :parentCategoryID,
              name               = :categoryName
              WHERE id  = :category_id AND user_id = :user_id");
        $query->bindValue(':user_id', $userID, PDO::PARAM_INT);
        $query->bindValue(':category_id', $categoryID, PDO::PARAM_INT);
        $query->bindValue(':parentCategoryID', $parentCategoryID, PDO::PARAM_INT);
        $query->bindValue(':categoryName', $categoryName,  PDO::PARAM_STR);
        $query->execute();
        $output = array('ok');
        echo json_encode($output);
      } catch(Exception $e){
        $error['e_db'] = $e->getMessage();
        echo json_encode($errors);
      }
    } else {
      try{
        $query = $this->db->prepare(
              "UPDATE expenses_category_assigned_to_users
              SET
              name               = :categoryName
              WHERE id  = :category_id AND user_id = :user_id");
        $query->bindValue(':user_id', $userID, PDO::PARAM_INT);
        $query->bindValue(':category_id', $categoryID, PDO::PARAM_INT);
        $query->bindValue(':categoryName', $categoryName,  PDO::PARAM_STR);
        $query->execute();
        $output = array('ok');
        echo json_encode($output);
      } catch(Exception $e){
        $error['e_db'] = $e->getMessage();
        echo json_encode($errors);
      }
    }
  }
}
