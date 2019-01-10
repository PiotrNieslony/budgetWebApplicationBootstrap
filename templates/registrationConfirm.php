<form class="form-login" role="form" method="post">
    <h2>Potwierdzenie</h2>
    <BR>
    <p>Utworzyłeś konto: <b><?= $_SESSION['typedLogin']; unset($_SESSION['typedLogin'])?></b><br /> Teraz możesz się zalogować.</p>
    <a href="zaloguj" class="text-center"><b>Zaloguj się</b></a>
</form>
