<?php
session_start();

spl_autoload_register('classLoader');
$config = require_once('config.php');

$budget = new Budget($config['host'],$config['user'], $config['pass'],$config['db']);

$action = 'start';
if($budget->parsePath()) $action = $budget->parsePath();
if(isset($_GET["action"]))$action =$_GET["action"];
if(!isset($_SESSION['loggedUser'])
&& $action != 'rejestracja'
&& $action != 'potwierdzenie-rejestracji'
&& $action != 'start') $action = 'zaloguj';
if(isset($_POST['inputLogin']) && isset($_POST['inputPassword'])) $action = 'zaloguj';

switch($action):
  case 'showMain';
    include 'templates/mainTemplate.php';
    break;
  case 'zaloguj';
    if($budget->login()) header("Location:przegladaj-bilans");
    else include 'templates/entryTemplate.php';
    break;
  case 'rejestracja';
    if($budget->register()) header("Location:potwierdzenie-rejestracji");
    else include 'templates/entryTemplate.php';
    break;
  case 'potwierdzenie-rejestracji';
    include 'templates/entryTemplate.php';
    break;
  case 'wyloguj';
    $budget->logout();
    header('Location:zaloguj');
    break;
  case 'start';
    include 'templates/startpage.php';
    break;
  case 'edit-income-modal';
    $budget->editIncome();
    break;
  case 'delete-income-modal';
    $budget->deleteIncome();
    break;
  case 'edit-expense-modal';
    $budget->editExpense();
    break;
  case 'delete-expense-modal';
    $budget->deleteExpense();
    break;
  case 'load-incomes';
    echo $budget->showIncomes();
    break;
  case 'load-expenses';
    echo $budget->showExpenses();
    break;
  case 'add-income-subcategory';
    $budget->addIncomeSubcategory();
    break;
  case 'add-income-category';
    $budget->addIncomeCategory();
    break;
  case 'add-expense-subcategory';
    $budget->addExpenseSubcategory();
    break;
  case 'add-expense-category';
    $budget->addExpenseCategory();
    break;
  default;
    include 'templates/mainTemplate.php';
 endswitch;

function classLoader($className){
  if(file_exists("class/$className.php")){
    require_once("class/$className.php");
  } else {
    throw new Exception("Brak pliku z definicją klasy.");
  }
}
