<?php if(!isset($budget)) die();?>
<?php ?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Budget</title>

	<meta name="description" content="Opis w Google" />
	<meta name="keywords" content="" />
	<?php include "templates/favicon.php" ?>
	<link rel="stylesheet" href="bootstrap/bootstrap.css?v=<?=filemtime('bootstrap/bootstrap.css')?>" type="text/css" />
	<link rel="stylesheet" href="fontello/fontello.css" type="text/css" />
	<link rel="stylesheet" href="jquery/jquery-ui.min.css" type="text/css" />
	<link rel="stylesheet" href="css/style.css?v=<?=filemtime('css/style.css')?>" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Titillium+Web:200,400,700&amp;subset=latin-ext" rel="stylesheet">
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
				<h1 id="logo"><a href="przegladaj-bilans"><i>$</i> Your<span>Budget</span></a></h1>
			</header>
		</div>
        <?php require_once 'sidebar.php' ?>
				<div class="content budget">
					<main>
						<?php
			          switch($action):
									case 'dodaj-przychod' :
									$budget->addIncome();
										include 'templates/addIncome.php';
										break;
			            case 'dodaj-wydatek':
										$budget->addExpense();
			              include 'templates/addExpense.php';
			              break;
			            case 'przegladaj-bilans':
			              include 'templates/balance.php';
			              break;
			            case 'ustawienia' :
			              include 'templates/settings.php';
			              break;
			            case 'test' :
			              include 'templates/test.php';
			              break;
			          endswitch;
			        ?>
				</main>
			</div>
	</div>
	<script src="js/main.js?v=<?=filemtime('js/main.js')?>"></script>
</body>
</html>
