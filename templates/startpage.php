<?php if(!isset($budget)) die();?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>YourBudget</title>

	<meta name="description" content="Opis w Google" />
	<meta name="keywords" content="słowa, kluczowe, wypisane, po, porzecinku" />
	<?php include "templates/favicon.php" ?>
	<link rel="stylesheet" href="bootstrap/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="fontello/fontello.css" type="text/css" />
	<link rel="stylesheet" href="jquery/jquery-ui.min.css" type="text/css" />
	<link rel="stylesheet" href="css/style.css" type="text/css" />
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
			<div clas="row">
			<header>
				<div class="col-md-4 ">
					<h1 id="logo" class"navbar-brand"><a><i>$</i> Your<span>Budget</span></a></h1>
				</div>
				<div class="col-md-2 col-md-offset-4 col-xs-6">
					<a class="btn btn-default btn-block" href="rejestracja">Rejestracja</a>
				</div>
				<div class="col-md-2 col-xs-6">
					<a class="btn btn-primary btn-block" href="zaloguj">Zaloguj się</a>
				</div>
			</header>
		</div>
		</div>
	</div>
	<main>
		<div class="row banner">
				<div class="banner-container">
						<h1>
							Zapanuj<br> nad<br> budżetem
						</h1>
				</div>
		</div>
		<div class="row description">
			<div class="container">
				<h2 class="text-center">O aplikacji</h2>
				<div class="col-lg-12">
					<p class="lead">
						Aplikacja YourBudget służy do zarządzania budżetem osobistym. Umożliwia wprowadzanie wydatków i przychodów, oraz przypisywanie ich do kategorii tworzonych i modyfikowanych według własnych potrzeb.  Wprowadzone przez Ciebie kwoty będą przedstawione w tabeli oraz na czytelnych wykresach.   Dzięki temu będziesz w stanie na bieżąco śledzić stan swoich finansów.  Będziesz mógł łatwo określić, na co wydajesz najwięcej środków i zaplanować oszczędzanie.
				</div>
			</div>
		</div>
		<div class="row advantages">
			<div class="container">
				<h2 class="text-center">Najważniejsze cechy</h2>
				<div class="col-lg-6">
					<ul class="list-unstyled">
						<li>Możliwość dodawania wydatków i przychodów</li>
						<li>Możliwość edycji wprowadzonych wydatków</li>
						<li>Dodawanie kategorii dla przychodów i wydatków</li>
						<li>Edycja kategorii</li>
						<li>Podkategorie wydatków i przychodów</li>
						<li>Wybór sposobu płatności</li>
						<li>Wizualizacja bilansu za pomocą wykresów</li>
						<li>Bilans dla dowolnego przedziału czasu</li>
						<li>Wersja mobilna</li>
					</ul>
				</div>
				<div class="col-lg-6">
					<div class="img-crop">
						<a data-toggle="modal" data-target="#imageModal">
							<img src="img/screen-shot.png" class="img-responsive" alt="Zrzut ekranu aplikacji">
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row registert-now text-center">
			<div class="container">
				<div class="col-lg-12">
					<a href="rejestracja" class="btn btn-primary btn-lg"> Zarejestruj się teraz</a>
				</div>
			</div>
		</div>
		<?php include "modal-print-screen.php" ?>
	</main>
	<div class="row footer">
		<div class="container">
			<p>&copy; Piotr Niesłony</p>
		</div>
	</div>
</div>
</body>
</html>
