<?php

class ExpensesView{
  private $db = null;
  public function __construct($db){
    $this->db = $db;
  }

  private function generateColor($amountOfSpent, $limit){
    $percentOfLimit    = ($amountOfSpent*100)/$limit;
    $maxColorComponent = 220;

    if($percentOfLimit < 100){
      $colorComponent = round(($percentOfLimit * $maxColorComponent)/100);
      if($colorComponent > $maxColorComponent) $colorComponent = 0;
      return "rgb($colorComponent,$maxColorComponent,$colorComponent)";
    } else {
      $percentOfLimit -= 100;
      $colorComponent = round(($percentOfLimit * $maxColorComponent)/100);
      $colorComponent = $maxColorComponent - $colorComponent;
      if($colorComponent < 0) $colorComponent = 0;
      return "rgb($maxColorComponent,$colorComponent,$colorComponent)";
    }

  }

  public function alertExceededCategoryLimit(){
    $expenses = new Expenses($this->db);

    $passingValue = array();

    $passingValue['HowManySpentInCategory'] = $expenses->checkHowManySpentInCategory();
    $passingValue['HowManySpentInCategoryPlusTypedAmount'] = $passingValue['HowManySpentInCategory'] + $_POST['amount'];
    $passingValue['amountOfCategoryLimit']  = $expenses->getCategoryLimitManager();
    if(is_null($expenses->getAmountOfCategoryLimit($_POST['category']))){
      $passingValue['categoryName']         = $expenses->getMainCategoryName($_POST['category']);
    } else {
      $passingValue['categoryName']         = $expenses->getCategoryName($_POST['category']);
    }

    if(!is_null($passingValue['amountOfCategoryLimit'])){
      $passingValue['exceedingLimitValue'] = round($passingValue['amountOfCategoryLimit'] - $passingValue['HowManySpentInCategory'], 2) ;
      $passingValue['limitSet'] = true;
      $passingValue['info-color'] = $this->generateColor($passingValue['HowManySpentInCategory'], $passingValue['amountOfCategoryLimit']);
      if($passingValue['exceedingLimitValue'] < 0){
        $passingValue['exceededLimit'] = true;
        $passingValue['message'] = "Przekroczyłeś miesięczny limit wydatków o <strong>"
        .number_format(abs($passingValue['exceedingLimitValue']), 2, ',', ' ')."</strong> dla kategorii <strong>"
        .$passingValue['categoryName']."<strong>";
      } else {
        $passingValue['exceededLimit'] = false;
        $passingValue['message'] = "Możesz jeszcze wydać <strong>"
        .number_format(abs($passingValue['exceedingLimitValue']), 2, ',', ' ')."</strong> w kategorii <strong>"
        .$passingValue['categoryName']."<strong>";
      }
      $passingValue['exceedingLimitValue']    = number_format($passingValue['exceedingLimitValue'], 2, ',', ' ') ;
      $passingValue['HowManySpentInCategory'] = number_format(abs($passingValue['HowManySpentInCategory']), 2, ',', ' ');
      $passingValue['HowManySpentInCategoryPlusTypedAmount'] = number_format(abs($passingValue['HowManySpentInCategoryPlusTypedAmount']), 2, ',', ' ');
      $passingValue['amountOfCategoryLimit']  = number_format(abs($passingValue['amountOfCategoryLimit']), 2, ',', ' ');
    } else {
      $passingValue['message'] = "Nie zadeklarowano miesięcznego limitu dla kategorii <strong>".$passingValue['categoryName']."</strong>";
      $passingValue['limitSet'] = false;
    }
    echo json_encode($passingValue);
  }

} // End Class
