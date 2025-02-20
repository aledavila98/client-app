<?php
function getReverbConnection()
{
    $host = $_ENV['REVERB_HOST'];
    $port = $_ENV['REVERB_PORT'];
    $dbname = $_ENV['REVERB_DBNAME'];
    $user = $_ENV['REVERB_USER'];
    $password = $_ENV['REVERB_PASSWORD'];
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}