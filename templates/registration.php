<form class="form-login" role="form" method="post">
    <h2>Rejestracja</h2>
    <br>
    <div class="form-group">
                <input type="text" class="form-control" name="inputLogin" placeholder="login" value="<?= isset($_SESSION['typedLogin']) ? $_SESSION['typedLogin']: ""; unset($_SESSION['typedLogin']) ?>" required />
                <?= isset($_SESSION['e_login']) ? "<p class='alert alert-danger'>".$_SESSION['e_login']."</p>": ""; unset($_SESSION['e_login'])?>
                <input type="email" class="form-control" name="inputEmail" placeholder="email - nie jest wymagany" value="<?= isset($_SESSION['typedEmail']) ? $_SESSION['typedEmail']: "";unset($_SESSION['typedEmail']) ?>" required/>
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
