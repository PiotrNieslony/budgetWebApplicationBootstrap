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
                                        WHERE id = :expenseID');
        $query->bindValue(':expense_category_assigned_to_user_id', $_POST['categorys'] , PDO::PARAM_INT);
        $query->bindValue(':payment_method_assigned_to_user_id', $_POST['paymentType'], PDO::PARAM_INT);
        $query->bindValue(':amount', $_POST['expenseAmount'],  PDO::PARAM_INT);
        $query->bindValue(':date_of_expense', $_POST['expenseDate'],  PDO::PARAM_STR);
        $query->bindValue(':expense_comment', $_POST['expenseComment'],  PDO::PARAM_STR);
        $query->bindValue(':expenseID',  $_POST['expenseID'], PDO::PARAM_INT);
        $query->execute();
        $categoryId = $_POST['categorys'];
        $query2 = $this->db->query("SELECT name FROM expenses_category_assigned_to_users WHERE id = $categoryId");
        $row = $query2->fetch();
        $categoryName = $row[0];
        $_SESSION['success'] = "Dodano wydatek ".$_POST['expenseAmount']." zł do kategorii <b>".$categoryName."</b> ";
        $output = array('ok');
        echo json_encode($output);
    } else {
      echo json_encode($errors);
    }
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
}
