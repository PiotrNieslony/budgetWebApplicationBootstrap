<?php if(!isset($budget)) die();?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Budget - Logowanie</title>

	<meta name="description" content="Opis w Google" />
	<meta name="keywords" content="słowa, kluczowe, wypisane, po, porzecinku" />

	<link rel="stylesheet" href="bootstrap/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script src="jquery/jquery-3.3.1.min.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
	<div class="login-header">
		<header>
			<h1 id="logo"><i>$</i> Your<span>Budget</span></h1>
		</header>
	</div>
	<main>
			<div class="container-fluid">
        <?php
            switch($action):
              case 'zaloguj' :
                include 'templates/login.php';
                break;
              case 'rejestracja' :
                include 'templates/registration.php';
                break;
              case 'potwierdzenie-rejestracji' :
                include 'templates/registrationConfirm.php';
                break;
            endswitch;
          ?>
			</div>
	</main>

</body>
</html>
