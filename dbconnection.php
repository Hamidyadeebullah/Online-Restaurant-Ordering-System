<?php

$host = 'mysql';
$user = 'adeeb';
$password = 'Adeeb1234';
$db = 'food';
$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    echo "connection error --> " . mysqli_connect_error();
}

?>