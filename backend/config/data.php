<?php

try{
$pdo=new PDO("mysql:host=localhost;dbname=nfc_business_card;port=3307","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $v){
    echo "Erreur: ".$v->getMessage();
}



?>