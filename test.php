<?php

$host = 'localhost';
$user = 'root';
$pass = 'SQL@123';
$db = 'card_module';



mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try{
    $connect = new mysqli('localhost', 'root', 'SQL@123', 'card_module');
    echo "Success! Connected via mysqli.";
}catch(mysqli_sql_exception $e){
    echo "Database Connection failed";
}
?>



