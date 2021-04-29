<?php
    $ip = 'localhost';
    $login = 'root';
    $mdp = 'root';
    $dbName = 'projetPHP';

    try {
        $bdd = new PDO('mysql:host='.$ip.';dbname='.$dbName,$login,$mdp);
    } catch(Exception $e) {
        die ($e->getMessage());
    }
?>