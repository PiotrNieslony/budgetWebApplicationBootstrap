<?php if(!isset($budget)) die();?>
<div class="sidebar">
        <span class="switcher"><i class="icon-left-open"></i></span>
        <nav>
        <div class="main-menu">
          <?php
          echo "<p>Witaj <b>".$_SESSION['loggedUser']['username']."</b></p>";
          ?>
            <ul>
                <li><a href="dodaj-przychod">
                  <span class="glyphicon glyphicon-ice-lolly" aria-hidden="true"></span> Dodaj przychód
                  </a>
                </li>
                <li><a href="dodaj-wydatek">
                  <span class="glyphicon glyphicon-ice-lolly-tasted" aria-hidden="true"></span>Dodaj wydatek
                  </a>
                </li>
                <li><a href="przegladaj-bilans">
                  <span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Przeglądaj bilans
                  </a>
                </li>
                <li><a href="ustawienia">
                  <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Ustawienia
                  </a>
                </li>
                <li><a href="wyloguj">
                  <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Wyloguj
                </a>
              </li>
            </ul>
        </div>
    </nav>
</div>
