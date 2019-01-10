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
	<script src="jquery/jquery-3.3.1.min.js"></script>
	<script src="bootstrap/bootstrap.min.js"></script>
	<script src="jquery/jquery-ui.min.js"	></script>
	<script  src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
	<div class="container-fluid">
		<div class="header">
			<header>
				<h1 id="logo"><i>$</i> Your<span>Budget</span></h1>
			</header>
		</div>
        <?php require_once 'sidebar.php' ?>
				<div class="content budget">
					<main>
						<?php
			          switch($action):
									case 'dodaj-przychod' :
										include 'templates/addIncome.php';
										$budget->addIncome();
										break;
			            case 'dodaj-wydatek':
			              include 'templates/addExpense.php';
										$budget->addExpense();
			              break;
			            case 'przegladaj-bilans':
										$balance = $budget->balance();
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
	<script src="js/main.js"	></script>
</body>
</html>
