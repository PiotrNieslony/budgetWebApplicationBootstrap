<?php
    session_start();

if(isset($_POST['inputLogin'])){
    //Validation
    $validation = true;

    //check login name
    $login = $_POST['inputLogin'];

    if((strlen($login)<3) || ((strlen($login)>20))) {
        $validation = false;
        $_SESSION['e_login'] = "Login musi posiadać od 3 do 20 znaków.";
    }

    if(ctype_alnum($login) == false){
        $validation = false;
        $_SESSION['e_login'] = "Nick może składać się tylko z liter i cyfr (bez polskich znaków).";
    }

    if (!isset($_SESSION['e_login'])){
        require_once 'dbconnect.php';
        $query = $db->prepare('SELECT id FROM users WHERE username = :username');
        $query->bindValue(':username', $login, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount()){
            $validation = false;
            $_SESSION['e_login'] = "Ten login jest już zajęty.";
        }

    }

    //check email
    $email = $_POST['inputEmail'];

    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

    if((filter_var($emailB, FILTER_VALIDATE_EMAIL)) == false || ($emailB != $email)){
        $validation = false;
        $_SESSION['e_email'] = "Podaj poprawny adres email.";
    } else {
        require_once 'dbconnect.php';
        $query = $db->prepare('SELECT id FROM users WHERE email = :email');
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount()){
            $validation = false;
            $_SESSION['e_email'] = "Ten adres  email został już użyty do rejestracji konta.";
        }
    }



    //Check the correctness of the password
    $pass1 = $_POST['inputPassword1'];
    $pass2 = $_POST['inputPassword2'];

    if((strlen($pass1)<8) || (strlen($pass1)>20)){
        $validation = false;
        $_SESSION['e_pass'] = "Haslo musi posiadać od 8 do 20 znaków.";
    }

    if($pass1 != $pass2){
        $validation = false;
        $_SESSION['e_pass'] = "Podane hasła nie są identyczne.";
    }

    //Check regulamin checkbox
    if(!isset($_POST['akceptTerms'])){
        $validation = false;
        $_SESSION['e_terms'] = "Potwierdź akceptację regulaminu.";
    }

    //Remember the entered data
    if($validation == false){
        $_SESSION['typedLogin'] = $login;
        $_SESSION['typedEmail'] = $email;
        $_SESSION['typedPass1'] = $pass1;
        $_SESSION['typedPass2'] = $pass2;
        $_SESSION['akceptTerms'] = $_POST['akceptTerms'];

    }

    if($validation) {
        require_once 'dbconnect.php';
        $pass_hash = password_hash($pass1, PASSWORD_DEFAULT);
        $query = $db->prepare('INSERT INTO users VALUES (NULL, :username, :password, :email)');
        $query->bindValue(':username', $login, PDO::PARAM_STR);
        $query->bindValue(':password', $pass_hash , PDO::PARAM_STR);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['typedLogin'] = $login;

        $addDefalutExpensesCategory = "INSERT INTO expenses_category_assigned_to_users(user_id, name)
                                SELECT users.id, ex.name
                                FROM users
                                INNER JOIN
                                expenses_category_default ex
                                where users.username = '$login';";

        $addDefalutIncomeCategory = "INSERT INTO incomes_category_assigned_to_users(user_id, name)
                                SELECT users.id, inco.name
                                FROM users
                                INNER JOIN
                                incomes_category_default inco
                                where users.username = '$login';";

        $addPaymentMethods = "INSERT INTO payment_methods_assigned_to_users(user_id, name)
                                SELECT users.id, pd.name
                                FROM users
                                INNER JOIN
                                payment_methods_default pd
                                where users.username = '$login';";

        $db->query($addDefalutExpensesCategory);
        $db->query($addDefalutIncomeCategory);
        $db->query($addPaymentMethods);
        header('Location: registration-confirm.php');
        exit();
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
            <h2>Rejestracja</h2>
            <br>
            <div class="form-group">
                        <input type="text" class="form-control" name="inputLogin" placeholder="login" value="<?= isset($_SESSION['typedLogin']) ? $_SESSION['typedLogin']: ""; unset($_SESSION['typedLogin']) ?>" required />
                        <?= isset($_SESSION['e_login']) ? "<p class='alert alert-danger'>".$_SESSION['e_login']."</p>": ""; unset($_SESSION['e_login'])?>
                        <input type="email" class="form-control" name="inputEmail" placeholder="email" value="<?= isset($_SESSION['typedEmail']) ? $_SESSION['typedEmail']: "";unset($_SESSION['typedEmail']) ?>" required/>
                        <?= isset($_SESSION['e_email']) ? "<p class='alert alert-danger'>".$_SESSION['e_email']."</p>": ""; unset($_SESSION['e_email'])?>
                        <input type="password" class="form-control" name="inputPassword1" placeholder="hasło" value="<?= isset($_SESSION['typedPass1']) ? $_SESSION['typedPass1']: ""; unset($_SESSION['typedPass1']) ?>" required/>
                        <?= isset($_SESSION['e_pass']) ? "<p class='alert alert-danger'>".$_SESSION['e_pass']."</p>": ""; unset($_SESSION['e_pass'])?>
                        <input type="password" class="form-control" name="inputPassword2" placeholder="powtórz hasło" value="<?= isset($_SESSION['typedPass2']) ? $_SESSION['typedPass2']: ""; unset($_SESSION['typedPass2']) ?>"required/>
                        <label><input type="checkbox" name="akceptTerms" <?=  isset($_SESSION['akceptTerms']) ? "checked": ""; unset($_SESSION['akceptTerms']) ?> required/> Akceptuję regulamin</label>
                        <?= isset($_SESSION['e_terms']) ? "<p class='alert alert-danger'>".$_SESSION['e_terms']."</p>": ""; unset($_SESSION['e_terms'])?>
                        <input type="submit" class="form-control btn btn-default" value="Zarejestuj się" />
                        <a href="login.php" class="text-center">Zaloguj się</a>
                    </div>
				</form>
			</div>
	</main>

</body>
</html>
