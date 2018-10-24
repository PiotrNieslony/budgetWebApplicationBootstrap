<?php
session_start();
require_once 'dbconnect.php';

if(!isset($_SESSION['loggedUser'])) header('Location:zaloguj');

if(isset($_POST['expenseAmount'])){
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

    //category validation
    if(!isset($_POST['categorys'])){
        $validationCorrect = false;
        $_SESSION['e_categorys'] = "Wybierz kategorię";
    }

    if($validationCorrect){
        $query = $db->prepare('INSERT INTO expenses VALUES (NULL, :user_id, :expense_category_assigned_to_user_id, :payment_method_assigned_to_user_id, :amount, :date_of_expense, :expense_comment )');
        $query->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
        $query->bindValue(':expense_category_assigned_to_user_id', $_POST['categorys'] , PDO::PARAM_INT);
        $query->bindValue(':payment_method_assigned_to_user_id', $_POST['paymentType'], PDO::PARAM_INT);
        $query->bindValue(':amount', $_POST['expenseAmount'],  PDO::PARAM_INT);
        $query->bindValue(':date_of_expense', $_POST['expenseDate'],  PDO::PARAM_STR);
        $query->bindValue(':expense_comment', $_POST['expenseComment'],  PDO::PARAM_STR);
        $query->execute();
        $_SESSION['success'] = "Dodano wydatek ".$_POST['expenseAmount']." zł";
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
				<div class="content budget add-expense">
					<main>
					<header>
						<h1>Dodaj wydatek</h1>
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
												<input name="expenseAmount" class="form-control" type="number" step="0.01"/>
                                                <?= (isset($_SESSION['e_expenseAmount'])) ? "<p class='alert alert-danger'>".$_SESSION['e_expenseAmount']."</p>" : "";
                                                unset($_SESSION['e_expenseAmount']); ?>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<strong>Data</strong>
												<input name="expenseDate" class="date form-control" type="text" placeholder="RRRR-MM-DD" required
												pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
												title="Wpisz datę w formacie YYYY-MM-DD"/>
                                                <?= (isset($_SESSION['e_expenseDate'])) ? "<p class='alert alert-danger'>".$_SESSION['e_expenseDate']."</p>" : "";
                                                unset($_SESSION['e_expenseDate']); ?>
											</div>
										</div>
										<diV class="col-md-4">
											<div class="form-group">
												<strong>Sposób płatności</strong>
												<select name="paymentType" class="form-control">
                                                    <?php
                                                        $paymentMethodQuery = $db->prepare('SELECT id, name FROM payment_methods_assigned_to_users WHERE user_id = :user_id');
                                                        $paymentMethodQuery->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
                                                        $paymentMethodQuery->execute();
                                                        $paymentMethods = $paymentMethodQuery->fetchAll();
                                                        foreach($paymentMethods as $paymentMethod){
                                                            echo "<option value=\"$paymentMethod[0]\">$paymentMethod[1]</option>";
                                                        }
                                                    ?>
												</select>
											</div>
										</div>
									</div>
									<div>
										<div class="form-group">
											<strong>Kategoria</strong>
                                            <?php
                                                $categoryQuery = $db->prepare('SELECT id, name FROM expenses_category_assigned_to_users WHERE user_id = :user_id');
                                                $categoryQuery->bindValue(':user_id', $_SESSION['loggedUser']['id'], PDO::PARAM_INT);
                                                $categoryQuery->execute();
                                                $categorys = $categoryQuery->fetchAll();
                                                foreach($categorys as $category){
                                                    echo "<div class=\"radio\" id=\"expenseCategory\">
                                                            <label><input type=\"radio\" name=\"categorys\" value=\"$category[0]\" />$category[1]</label>
                                                        </div>";
                                                }
                                                echo (isset($_SESSION['e_categorys'])) ? "<p class='alert alert-danger'>".$_SESSION['e_categorys']."</p>" : "";
                                                unset($_SESSION['e_categorys']);
                                            ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<strong>Komentarz:</strong>
											<input name="expenseComment" type="text" class="form-control" />
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
