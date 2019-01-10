<?php
class Incomes {
  private $db = null;
  function __construct($db){
    $this->db = $db;
  }
  public function add(){
    if(!isset($_POST['incomeAmount'])) return false;
    $validationCorrect = true;
    //Amount validation
    if(!is_numeric($_POST['incomeAmount'])){
        $validationCorrect = false;
        $_SESSION['e_incomeAmount'] = "Wpisz poprawną kwotę (liczba)";
    }

    if($_POST['incomeAmount'] > 999999.99 || $_POST['incomeAmount'] < 0 ){
        $validationCorrect = false;
        $_SESSION['e_incomeAmount'] = "Wprowadź poprawną kwotę (od 0 dd 999 999.99)";
    }
    //date Validation
    $timezone = new DateTimeZone('Europe/Warsaw');
    $incomeDate = DateTime::createFromFormat('Y-m-d',$_POST['incomeDate'] ,$timezone);
    if(!checkdate($incomeDate->format('m'), $incomeDate->format('d'), $incomeDate->format('Y'))){
        $validationCorrect = false;
        $_SESSION['e_incomeDate'] = "Wprowadź poprawną datę w formiecie np. 2010-01-01";
    }
    $bottomRangOfDate = DateTime::createFromFormat('Y-m-d',"2001-01-01",$timezone);
    $upperRangOfDate =  new DateTime('NOW');
    if($incomeDate < $bottomRangOfDate && $incomeDate > $upperRangOfDate){
        $validationCorrect = false;
        $_SESSION['e_incomeDate'] = "Podaj datę w zakresie od 2010-01-01 do $upperRangOfDate->format('Y-m-d')";
    }

    //category validation
    if(!isset($_POST['categorys'])){
        $validationCorrect = false;
        $_SESSION['e_categorys'] = "Wybierz kategorię";
    }

    if($validationCorrect){
        $query = $this->db->prepare('INSERT INTO incomes VALUES (NULL, :user_id, :income_category_assigned_to_user_id, :amount, :date_of_income, :income_comment )');
        $query->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
        $query->bindValue(':income_category_assigned_to_user_id', $_POST['categorys'] , PDO::PARAM_INT);
        $query->bindValue(':amount', $_POST['incomeAmount'],  PDO::PARAM_INT);
        $query->bindValue(':date_of_income', $_POST['incomeDate'],  PDO::PARAM_STR);
        $query->bindValue(':income_comment', $_POST['incomeComment'],  PDO::PARAM_STR);
        $query->execute();
        $_SESSION['success'] = "Dodano przychód w wysokości: ".$_POST['incomeAmount'];
        return true;
    }
    return false;
  }
}
