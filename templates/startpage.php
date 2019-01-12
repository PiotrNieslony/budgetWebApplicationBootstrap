<?php if(!isset($budget)) die();?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>YourBudget</title>

	<meta name="description" content="Opis w Google" />
	<meta name="keywords" content="słowa, kluczowe, wypisane, po, porzecinku" />

	<link rel="stylesheet" href="bootstrap/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link rel="stylesheet" href="fontello/fontello.css" type="text/css" />
	<link rel="stylesheet" href="jquery/jquery-ui.min.css" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Titillium+Web:200,400,700&amp;subset=latin-ext" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="jquery/jquery-3.3.1.min.js"></script>
	<script src="bootstrap/bootstrap.min.js"></script>
	<script src="jquery/jquery-ui.min.js"	></script>
	<script  src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body class="startpage">
<div class="container-fluid">
	<div class="container">
		<div class="header">
			<header>
				<nav>
          <ul class="nav nav-pills pull-right">
            <li><a class="btn btn-default" href="rejestracja">Rejestracja</a></li>
            <li><a class="btn btn-primary active" href="zaloguj">Zaloguj się</a></li>
          </ul>
					<h1 id="logo"><a><i>$</i> Your<span>Budget</span></a></h1>
        </nav>
			</header>
		</div>
	</div>
	<main>
		<div class="row banner">
				<div class="banner-container">
					<div class="overlay">
						<h1>
							Zarządzaj<br> swoim<br> budżetem
						</h1>
					</div>
				</div>
		</div>
		<div class="row description">
			<div class="container">
				<div class="col-lg-12">
					<p class="lead">
						Aplikacja YourBudget wspomogą zarządzanie budżetem osobistym. Umożliwia wprowadzanie kosztów i przychodów przypisanych do kategorii. Użytkownicy mają możliwość przeglądaniu bilansu na czytelnych wykresach.
					</p>
				</div>
			</div>
		</div>
	</main>
	<div class="row footer">
		<div class="container">
			<p>&copy; Piotr Niesłony</p>
		</div>
	</div>
<script src="js/main.js"	></script>
</div>
</body>
</html>
