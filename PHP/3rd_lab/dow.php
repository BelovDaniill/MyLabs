<?php
$a = 0;
$b = 0;
$i = 0;

do {
    $a += 10;
    $b += 5;
    echo "Шаг $i: a = $a, b = $b<br>";
    $i++;
} while ($i <= 5);

echo "End of the loop: a = $a, b = $b<br>";