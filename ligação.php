<?php

$host = 'localhost';

$user = 'root';

$password = 'Admin';

$dbname = 'mydb';


$conn = new mysqli ( $host, $user, $password, $dbname);

if ($conn -> connect_error) {
    die( "falha na conexão:") . ($conn-> connect_error);

echo "Conexão bem-sucedida!";

}




