<?php if(!isset($budget)) die();?>
<div class="sidebar">
        <span class="switcher"><i class="icon-left-open"></i></span>
        <nav>
        <div class="main-menu">
          <?php
          echo "<p>Witaj <b>".$_SESSION['loggedUser']['username']."</b></p>";
          ?>
            <ul>
                <li><a href="dodaj-przychod">Dodaj przychód</a></li>
                <li><a href="dodaj-wydatek">Dodaj wydatek</a></li>
                <li><a href="przegladaj-bilans">Przeglądaj bilans</a></li>
                <li><a href="#">Ustawienia</a></li>
                <li><a href="wyloguj">Wyloguj</a></li>
            </ul>
        </div>
    </nav>
</div>
