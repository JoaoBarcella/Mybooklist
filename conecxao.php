<?php
$host = '127.0.0.1:3306';
$db = 'sistema';
$user = 'joao';
$pass = 'cyquer122';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
