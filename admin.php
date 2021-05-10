<?php session_start() ?>
<!DOCTYPE html>
<html lang='fr'>
<head>        
    <meta charset='utf-8'>
    <meta name='author' content='Louis Fitdevoie'>
    <title>Panneau d'administration [Admin]</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="Ressources/style.css">
</head>
<body>
    <?php
        session_start();
        include_once('menu.php');
        echo "<script>document.getElementById('adminMenu').setAttribute('class','selected');</script>";
        include_once('connexionDB.php');
        //On vérifie si l'utilisateur est connecté
        if(isset($_SESSION['username'])) {
            //On vérifie si l'utilisateur à les droits d'administrateur
            if($_SESSION['droits'] == 1) {
                echo '<h1 id="adminH1">Panneau admin</h1>';
                $req=$bdd->query('SELECT * FROM Users');
                $nbUser = 0;
                echo "<div id='userTables'>";
                while($data = $req->fetch()) {
                    $nbUser++;
                    echo "<div class='userTable'>";
                    echo '<table class="user">';
                    echo '<tr><th colspan=2 class="adminTh">Utilisateur ',$nbUser,'</th></tr>';
                    echo '<tr><td>User ID :</td><td>',$data['userId'],'</td></tr><tr><td>Username :</td><td>',$data['username'],"</td></tr><tr><td>Nom :</td><td>",$data['nom'],"</td><tr><td>Prénom :</td><td>", $data['prenom'], '</td></tr><tr><td>Mot de passe :</td><td>', $data['password'],'</td></tr><tr><td>Droits :</td><td>', $data['droits'],"</td></tr></table>";
                    echo '<div class="buttonsDiv"><a href="modifierPassword.php?id=',$data['userId'],'" class="buttonLeft">Modifier le mot de passe</a><a href="supprimerUser.php?id=',$data['userId'],'" class="buttonRight">Supprimer cet utilisateur</a></div>';
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo '<script>window.location.href = "index.php";</script>';
            }
        } else {
            echo '<script>window.location.href = "index.php";</script>';
        }
    ?>
</body>
</html>