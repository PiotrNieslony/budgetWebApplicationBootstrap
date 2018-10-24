<?php
session_start();
require_once 'dbconnect.php';

if(!isset($_SESSION['loggedUser'])) header('Location:zaloguj');

if(isset($_POST['incomeAmount'])){
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
        $query = $db->prepare('INSERT INTO incomes VALUES (NULL, :user_id, :income_category_assigned_to_user_id, :amount, :date_of_income, :income_comment )');
        $query->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
        $query->bindValue(':income_category_assigned_to_user_id', $_POST['categorys'] , PDO::PARAM_INT);
        $query->bindValue(':amount', $_POST['incomeAmount'],  PDO::PARAM_INT);
        $query->bindValue(':date_of_income', $_POST['incomeDate'],  PDO::PARAM_STR);
        $query->bindValue(':income_comment', $_POST['incomeComment'],  PDO::PARAM_STR);
        $query->execute();
        $_SESSION['success'] = "Dodano przychód w wysokości: ".$_POST['incomeAmount'];
    }

}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Budget - Rejestracja</title>

	<meta name="description" content="Opis w Google" />
	<meta name="keywords" content="słowa, kluczowe, wypisane, po, porzecinku" />

	<link rel="stylesheet" href="bootstrap/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link rel="stylesheet" href="fontello/fontello.css" type="text/css" />
	<link rel="stylesheet" href="jquery/jquery-ui.min.css" type="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<div class="container-fluid">
		<div class="header">
			<header>
				<h1 id="logo"><i>$</i> Your<span>Budget</span></h1>
			</header>
		</div>
                <?= require_once 'sidebar.php' ?>
				<div class="content budget add-income">
					<main>
					<header>
						<h1>Dodaj przychód</h1>
					</header>
					<div>
						<form role="form .form-horizontal" method="post">
							<div class="row">
								<div class="col-md-8">
                                    <?= (isset($_SESSION['success'])) ? "<p class=\"alert alert-success\">".$_SESSION['success']."</p>" : "";
                                    unset($_SESSION['success']); ?>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<strong>Kwota</strong>
												<input name="incomeAmount" class="form-control" type="number" step="0.01"/>
                                                <?= (isset($_SESSION['e_incomeAmount'])) ? "<p class='alert alert-danger'>".$_SESSION['e_incomeAmount']."</p>" : "";
                                                unset($_SESSION['e_incomeAmount']); ?>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<strong>Data</strong>
												<input  name="incomeDate" class="date form-control" type="text" placeholder="RRRR-MM-DD" required
												pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
												title="Wpisz datę w formacie YYYY-MM-DD"/>
											</div>
										</div>
									</div>
									<div>
										<div class="form-group">
											<strong>Kategoria</strong>
                                            <?php
                                            $categoryQuery = $db->prepare('SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = :user_id');
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
                                            ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-8">
											<strong>Komentarz:</strong>
											<input name="incomeComment" type="text" class="form-control" />
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<button type="submit" class="btn btn-default btn-block ">Dodaj</button>
										</div>
										<div class="col-md-4">
											<button type="button" class="btn btn-warning btn-block ">Anuluj</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</main>
				</div>
	</div>
	<script src="jquery/jquery-3.3.1.min.js"></script>
		<script src="bootstrap/bootstrap.min.js"></script>
	<script src="jquery/jquery-ui.min.js"	></script>
	<script  src="https://www.gstatic.com/charts/loader.js"></script>
	<script src="main.js"	></script>
</body>
</html>
