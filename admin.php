<?php session_start() ?>
<html lang='fr'>
<head>        
    <meta charset='utf-8'>
    <meta name='author' content='Louis Fitdevoie'>
    <title>Panneau d'administration [Admin]</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <style>
		body {
			background-color: rgb(15,15,15);
			color: white;
			font-family: Calibri, Arial, sans-serif;
			display: flex;
			flex-flow: column nowrap;
		}
		#logoSite {
			max-width: 280px;
			max-height: 150px;
			border: 5px solid #555555;
			border-radius: 22.5px;
			background-color: rgb(15,15,15);
		}
		#menu {
			display: flex;
			flex-flow: row nowrap;
			margin-left: auto;
			margin-right: auto;
		}
		nav {
			display: flex;
			flex-flow: row nowrap;
			align-items: center;
		}
		nav a {
			display: block;
			border: 2px solid #555555;
			background-color: #555555;
			text-decoration: none;
			color: white;
			padding: 15px;
			text-align: center;
		}
		@media screen and (max-width: 750px) {
			/* FAIRE LE MENU BURGER */
			nav {
				display: none !important;
			}
		}
		@media screen and (min-width: 750px) and (max-width: 900px) {
			nav a {
				font-size: 16px;
			}
		}
		@media screen and (min-width: 900px) {
			nav a {
				font-size: 24px;
			}
		}
		nav a:last-child {
			border-top-right-radius: 22.50px;
			border-bottom-right-radius: 22.50px;
		}
		nav a:hover, nav a:focus {
			background-color: #EEEEEE;
			border: 2px solid #353535;
			color: #555555;
			transition-duration: 500ms;
		}
		nav a:active {
			background-color: #BBBBBB;
		}
		nav a.selected {
			background-color: #E13930 !important;
			color: white !important;
			border: 2px solid #353535;
		}
        h1 {
            text-align: center;
        }
        th {
            vertical-align: middle;
        }
        div.userTable {
            margin-left: 25px;
            margin-bottom: 20px;
        }
        table.user td, table.user th {
            border: 1px solid white;
        }
        #userTables {
            display: flex;
            flex-flow: row wrap;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
            align-items: safe center;
        }
        .buttonsDiv a {
            display: inline-block;
            text-decoration: none;
            color: white;
            margin-top: 3px;
            padding: 2px;
            border: 2px solid white;
            border-radius: 10px;
            cursor: pointer;
            transition: 500ms;
        }
        .buttonsDiv {
            display: flex;
            flex-flow: row nowrap;
            justify-content: space-between;
        }
        .buttonsDiv a:hover, .buttonsDiv a:focus {
            background-color: white;
            color: black;
            transition: 500ms;
        }
    </style>
</head>
<body>
    <?php
        session_start();
        include_once('menu.php');
        echo "<script>document.getElementById('adminMenu').setAttribute('class','selected');</script>";
        include_once('connexionDB.php');
        
        if(isset($_SESSION['username'])) {
            if($_SESSION['droits'] == 1) {
                echo '<h1>Panneau admin</h1>';
                $req=$bdd->query('SELECT * FROM Users');
                $nbUser = 0;
                echo "<div id='userTables'>";
                while($data = $req->fetch()) {
                    $nbUser++;
                    echo "<div class='userTable'>";
                    echo '<table class="user">';
                    echo '<th colspan=2 >Utilisateur ',$nbUser,'</th>';
                    echo '<tr><td>User ID :</td><td>',$data['userId'],'</td></tr><tr><td>Username :</td><td>',$data['username'],"</td></tr><tr><td>Nom :</td><td>",$data['nom'],"</td><tr><td>Prénom :</td><td>", $data['prenom'], '</td></tr><tr><td>Mot de passe :</td><td>', $data['password'],'</td></tr><tr><td>Droits :</td><td>', $data['droits'],"</td></tr></table>";
                    echo '<div class="buttonsDiv"><a href="modifierPassword.php?id=',$data['userId'],'" class="buttonLeft">Modifier le mot de passe</a><a href="supprimerUser.php?id=',$data['userId'],'" class="buttonRight">Supprimer cet utilisateur</a></div>';
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo '<script>function Redirect() {  window.location="index.php"; }  document.write("Vous n\'avez pas les droits pour accéder à cette page ! Vous allez être redirigé vers la page d\'accueil dans 5 secondes."); setTimeout("Redirect()", 5000);</script>';
            }
        } else {
            echo '<script>function Redirect() {  window.location="index.php"; }  document.write("Vous n\'êtes pas connecté, vous allez être redirigé vers la page d\'accueil dans 5 secondes."); setTimeout("Redirect()", 5000);</script>';
        }
    ?>
</body>
</html>
