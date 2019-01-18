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
    if($incomeDate){
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
    } else {
      $_SESSION['e_incomeDate'] = "Wprowadź poprawną datę w formiecie np. 2010-01-01";
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
        $categoryId = $_POST['categorys'];
        $query2 = $this->db->query("SELECT name FROM incomes_category_assigned_to_users WHERE id = $categoryId");
        $row = $query2->fetch();
        $categoryName = $row[0];
        $_SESSION['success'] = "Dodano przychód w wysokości ".$_POST['incomeAmount']." do kategorii <b>".$categoryName."</b> ";
        return true;
    }
    return false;
  }

  public function edit(){
    if(!isset($_POST['incomeAmount'])) return false;
    $validationCorrect = true;
    //Amount validation
    if(!is_numeric($_POST['incomeAmount'])){
        $validationCorrect = false;
        $errors['e_incomeAmount'] = "Wpisz poprawną kwotę (liczba)";
    }

    if($_POST['incomeAmount'] > 999999.99 || $_POST['incomeAmount'] < 0 ){
        $validationCorrect = false;
        $errors['e_incomeAmount'] = "Wprowadź poprawną kwotę (od 0 dd 999 999.99)";
    }
    //date Validation
    $timezone = new DateTimeZone('Europe/Warsaw');
    $incomeDate = DateTime::createFromFormat('Y-m-d',$_POST['incomeDate'] ,$timezone);
    if($incomeDate){
      if(!checkdate($incomeDate->format('m'), $incomeDate->format('d'), $incomeDate->format('Y'))){
          $validationCorrect = false;
          $errors['e_incomeDate'] = "Wprowadź poprawną datę w formiecie np. 2010-01-01";
      }
      $bottomRangOfDate = DateTime::createFromFormat('Y-m-d',"2001-01-01",$timezone);
      $upperRangOfDate =  new DateTime('NOW');
      if($incomeDate < $bottomRangOfDate && $incomeDate > $upperRangOfDate){
          $validationCorrect = false;
          $errors['e_incomeDate'] = "Podaj datę w zakresie od 2010-01-01 do $upperRangOfDate->format('Y-m-d')";
      }
    } else {
      $errors['e_incomeDate'] = "Wprowadź poprawną datę w formiecie np. 2010-01-01";
    }

    //category validation
    if(!isset($_POST['categorys'])){
        $validationCorrect = false;
        $errors['e_categorys'] = "Wybierz kategorię";
    }

    if($validationCorrect){
      try{
        $query = $this->db->prepare('UPDATE incomes
                                    SET
                                    income_category_assigned_to_user_id = :income_category_assigned_to_user_id,
                                    amount = :amount,
                                    date_of_income = :date_of_income,
                                    income_comment = :income_comment
                                    WHERE id =:incomeID AND user_id = :user_id
                                    ');
        $query->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
        $query->bindValue(':income_category_assigned_to_user_id', $_POST['categorys'] , PDO::PARAM_INT);
        $query->bindValue(':amount', $_POST['incomeAmount'],  PDO::PARAM_INT);
        $query->bindValue(':date_of_income', $_POST['incomeDate'],  PDO::PARAM_STR);
        $query->bindValue(':income_comment', $_POST['incomeComment'],  PDO::PARAM_STR);
        $query->bindValue(':incomeID',  $_POST['incomeID'], PDO::PARAM_INT);
        $query->execute();
        $output = array('ok');
        echo json_encode($output);
      }
            catch(Exception $e){
        echo 'Błąd: ' . $e->getMessage();
      }
    } else {
      echo json_encode($errors);
    }
  }

  public function delete(){
    if(!isset($_POST['incomeID'])) return false;
    $errors = array();
    $query = $this->db->prepare('DELETE FROM incomes WHERE id  = :incomeID AND user_id = :user_id');
    $query->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
    $query->bindValue(':incomeID',  $_POST['incomeID'], PDO::PARAM_INT);
    $query->execute();
    $output = array('ok');
    echo json_encode($output);
  }

  public function sumOfIncomes($balaceDateFrom,$balaceDateTo){
    $loggedUserId = $_SESSION['loggedUser']['id'];
    $queryExpens = $this->db->query(
          "SELECT SUM(incomes.amount) AS sum
            FROM incomes
            WHERE incomes.user_id = $loggedUserId
            AND date_of_income >= '$balaceDateFrom'
            AND date_of_income <= '$balaceDateTo';");
    $expenses = $queryExpens->fetchAll();
    return $expenses[0]['sum'];
  }

  public function showIncoms($balaceDateFrom, $balaceDateTo) {
    $queryIncoms = $this->db->query("SELECT ipcatu.id AS id, icatu.name AS category, SUM(incomes.amount) AS amount
                    FROM incomes
                    INNER JOIN incomes_category_assigned_to_users icatu
                    ON incomes.income_category_assigned_to_user_id = icatu.id
                    INNER JOIN incomes_parent_category_assigned_to_users ipcatu
                    ON icatu.parent_category_id = ipcatu.id
                    WHERE incomes.user_id = 38
                    AND date_of_income >= '$balaceDateFrom'
                    AND date_of_income <= '$balaceDateTo'
                    GROUP BY icatu.parent_category_id
                    ORDER BY SUM(incomes.amount) DESC;");
    $incomesArray = $queryIncoms->fetchAll();
    $counter = 1;
    $expenses = new Incomes($this->db);
    $sum = $expenses->sumOfIncomes($balaceDateFrom,$balaceDateTo);
    foreach ($incomesArray as $incomeRow) {
      echo "<tr id=\"$incomeRow[0]\"><td>$counter</td>";
      $counter++;
      for ($i = 1; $i < 3; $i++) {
          echo "<td>";
          echo $incomeRow[$i];
          echo "</td>";
      }
      echo "<td><button class=\"btn btn-xs btn-primary extend\"><span class=\"glyphicon glyphicon-chevron-down\" aria-hidden=\"true\"></span></button></td>";
      echo "</tr>";
      if($subCategory = $this->showSubCategory($incomeRow[0],$balaceDateFrom, $balaceDateTo)){
        echo "<tr style='display:none'><td colspan=\"4\">";
        echo $subCategory;
        echo "</td></tr>";
      }
    }
    echo "<tr><td colspan=\"2\">Suma</td><th>$sum</th><td></td></tr>";
    $_SESSION['selected-date-from'] = $balaceDateFrom;
    $_SESSION['selected-date-to'] = $balaceDateTo;
    return $incomesArray;
  }

  public function showSubCategory($parentCategoryId, $balaceDateFrom, $balaceDateTo){
    $loggedUserId = $_SESSION['loggedUser']['id'];
    $queryExpens = $this->db->query("SELECT  icatu.id AS id , icatu.name AS category, SUM(incomes.amount) AS amount
                                    FROM incomes
                                    INNER JOIN
                                    incomes_category_assigned_to_users icatu
                                    ON incomes.income_category_assigned_to_user_id = icatu.id
                                    WHERE incomes.user_id = $loggedUserId
                                    AND icatu.parent_category_id = '$parentCategoryId'
                                    AND date_of_income >= '$balaceDateFrom'
                                    AND date_of_income <= '$balaceDateTo'
                                    GROUP BY incomes.income_category_assigned_to_user_id
                                    ORDER BY SUM(incomes.amount) DESC;");
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
      if($subCategory = $this->showIncomesItemsAssignedToCategory($expensRow[0], $balaceDateFrom, $balaceDateTo)){
        $subTable .= "<tr style='display:none'><td colspan=\"4\">";
        $subTable .= $subCategory;
        $subTable .= "</td></tr>";

      }
    }
    $subTable .= "</tbody></table>";
    return $subTable;
  }

  public function showIncomesItemsAssignedToCategory($CategoryId, $balaceDateFrom, $balaceDateTo){
    $loggedUserId = $_SESSION['loggedUser']['id'];
    $queryExpens = $this->db->query("SELECT  i.id, i.date_of_income, i.amount, i.income_comment
                    FROM incomes i
                    WHERE i.user_id = $loggedUserId
                    AND income_category_assigned_to_user_id = '$CategoryId'
                    AND date_of_income >= '$balaceDateFrom'
                    AND date_of_income <= '$balaceDateTo'");
    $rowco =$queryExpens->rowCount();
    if ($queryExpens->rowCount() == 0) return false;
    $expenses = $queryExpens->fetchAll();
    $counter = 1;
    $subTable = "";
    $subTable .= "<table class=\"table table-bordered table-striped table-hover sub-category-table\">";
    $subTable .= "<thead><tr><th>l.p.</th><th>data</th><th>kwota</th><th class=\"visible-sm visible-md visible-lg\">Komentarz</th><th></th></tr></thead>";
    $subTable .= "<tbody>";
    $counter = 0;
    foreach ($expenses as $expensRow) {
      $counter++;
      $subTable .=  "<tr id=\"$expensRow[0]\"><td>$counter</td>";
      for ($i = 1; $i < 4; $i++) {
          if($i == 3) $subTable .=  "<td class=\"visible-sm visible-md visible-lg\">";
          else $subTable .=  "<td>";
          $subTable .=  $expensRow[$i];
          $subTable .=  "</td>";
      }
      $subTable .= "<td>
      <button class=\"btn btn-xs btn-danger delete\"><span class=\"glyphicon glyphicon glyphicon glyphicon-trash
      \" aria-hidden=\"true\"></span></button>
            <button class=\"btn btn-xs btn-primary edit\"><span class=\"glyphicon glyphicon glyphicon-edit
            \" aria-hidden=\"true\"></span></button></td>";
      $subTable .=  "</tr>";
    }
    $subTable .= "</tbody></table>";
    return $subTable;
  }
}
