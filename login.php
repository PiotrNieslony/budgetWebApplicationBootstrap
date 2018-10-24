<?php
    session_start();
    if(isset($_SESSION['loggedUser'])) header('Location:przegladaj-bilans');
    if(isset($_POST['inputLogin']) && isset($_POST['inputPassword'])){
        $login = $_POST['inputLogin'];
        $pass = $_POST['inputPassword'];
        require_once 'dbconnect.php';
        $query = $db->prepare('SELECT id, username, password FROM users WHERE username = :username');
        $query->bindValue(':username', $login);
        $query->execute();

        $user = $query->fetch();

        if((strtolower($login) == strtolower($user['username'])) && password_verify($pass, $user['password'])){
            $_SESSION['loggedUser']['id'] = $user['id'];
            $_SESSION['loggedUser']['username'] = $user['username'];
            header('Location:przegladaj-bilans');
        } else {
            $_SESSION['eWrongData'] = "Niepoprawny login lub hasło.";
        }
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Budget - Logowanie</title>

	<meta name="description" content="Opis w Google" />
	<meta name="keywords" content="słowa, kluczowe, wypisane, po, porzecinku" />

	<link rel="stylesheet" href="bootstrap/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script src="bootstrap/bootstrap.min.js"></script>
	<script src="jquery/jquery-3.3.1.min.js"></script>
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
					<h2>Logowanie</h2>
					<div class="form-group">
                        <?= (isset($_SESSION['eWrongData'])) ? "<br /><p class='alert alert-danger'>".$_SESSION['eWrongData']."</p>" : "";
                        unset($_SESSION['eWrongData']); ?>
						<input type="text" class="form-control" name="inputLogin" placeholder="login"
                               value="<?= (isset($_POST['inputLogin'])) ? $_POST['inputLogin'] : ""; ?>">
						<input type="password" class="form-control" name="inputPassword" placeholder="hasło"
                               value="<?= (isset($_POST['inputPassword'])) ? $_POST['inputPassword'] : ""; ?>">
						<input type="submit" class="form-control btn btn-default" value="Zaloguj się" />
	                    <a href="registration.php" class="text-center">Zarejestruj się</a>
					</div>
				</form>
			</div>
	</main>

</body>
</html>
