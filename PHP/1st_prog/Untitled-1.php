<?php

$a = 0.1 + 0.2;
$b = 0.3;

var_dump($_SERVER['REQUEST_METHOD']);
var_dump(abs($a - $b) < PHP_FLOAT_EPSILON);
var_dump((string)$a);

// @ нуждна для проверки на равенство чисел с плавающей точкой, так как прямое сравнение может быть ненадежным из-за особенностей представления чисел в памяти.

$title = true;
$description = null;

echo $title ?: 'Default Title';
echo $description ?? 'Default Description';

$message = match (true) {
    true => 'The value is true',
    false => 'The value is false',
    default => 'The value is neither true nor false',
};
echo $message;
