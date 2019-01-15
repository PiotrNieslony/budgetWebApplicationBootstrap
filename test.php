<?php
$tablica = Array (
Array (
    'category' => 'Wyp?ata Kamili',
     0 => 'Wyp?ata Kamili',
     'amount'=> 33635.00,
     1 => 33635.00 ),
Array (
    'category' => 'Wyplata Piotr',
    0 => 'Wyplata Piotr',
    'amount' => 25427.00,
    1 => 25427.00 )
  );
  $columnsQuantity = sizeof($tablica[0])/2;
  echo "rozmiar tablicy = $columnsQuantity";
  foreach ($tablica as $incomRow) {
    echo "<br>";
    for ($i = 0; $i < $columnsQuantity; $i++) {
        echo "<br>";
        echo $incomRow[$i];
    }
  }
 ?>
