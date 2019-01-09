<?php ?>
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
				<div class="content budget">
					<main>
						<?php
			          switch($action):
									case 'showAddIncome' :
										include 'templates/addIncome.php';
										break;
			            case 'showAddExpense' :
			              include 'templates/addExpense.php';
			              break;
			            case 'showBalance':
			              include 'templates/balance.php';
			              break;
			            case 'showSettigns' :
			              $news = $portal->getNews();
			              include 'templates/settings.php';
			              break;
			          endswitch;
			        ?>
				</main>
			</div>
	</div>
	<script src="jquery/jquery-3.3.1.min.js"></script>
		<script src="bootstrap/bootstrap.min.js"></script>
	<script src="jquery/jquery-ui.min.js"	></script>
	<script  src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        //incomes data
        var incomesArray = [['Category', 'Amount']];
        incomesArray.push(<?php foreach($incomes as $income){echo "[\"$income[0]\", $income[1]],";} ?>);

        console.log(incomesArray);
        //expenses data
        var expensesArray = [['Category', 'Amount']];
        expensesArray.push(<?php foreach($expenses as $expens){echo "[\"$expens[0]\", $expens[1]],";} ?>);

    </script>
	<script src="balance.js"	></script>
	<script src="main.js"	></script>
</body>
</html>
