<?php if(!isset($budget)) die();?>
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
    <a href="rejestracja" class="text-center">Zarejestruj się</a>
  </div>
</form>
