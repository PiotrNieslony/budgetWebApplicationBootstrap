<?php
session_start();

if(!isset($_SESSION['typedLogin'])) {
   header('Location: registration.php');
   exit();
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
    <script src="bootstrap/bootstrap.min.js"></script>
    <script src="jquery/jquery-3.3.1.min.js"></script>
    <script src="main.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="login-header">
    <header>
        <h1 id="logo"><i>$</i> Your<span>Budget</span></h1>
    </header>
</div>
<main>
    <div class="container-fluid">
        <form class="form-login" role="form" method="post">
            <h2>Potwierdzenie</h2>
            <BR>
            <p>Utworzyłeś konto: <b><?= $_SESSION['typedLogin']; unset($_SESSION['typedLogin'])?></b><br /> Teraz możesz się zalogować.</p>
            <a href="login.php" class="text-center"><b>Zaloguj się</b></a>

        </form>
    </div>
</main>

</body>
</html>
