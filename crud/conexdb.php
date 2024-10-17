<?php

require_once "configdb.php";

try{
    $conexdb = new PDO(DB_DSN, DB_USER, DB_PWD);
    $conexdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $ex){
    echo "Error en la conexión: " . $ex->getMessage();
}