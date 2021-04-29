<?php session_start() ?>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Louis Fitdevoie">
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
			#container {
				margin-top: 25px;
				margin-left: auto;
				margin-right: auto;
				background-color: #FFFFFF10;
				border-radius: 15px;
				max-width: 650px;
			}
			#content {
				max-width: 600px;
				margin: 10px;
				padding: 0px;
			}
			#content a {
				display: inline-block;
				width: 50%;
				text-align: center;
				border-top: 1px solid rgb(99,99,99);
				padding-top: 10px;
				padding-bottom: 10px;
				text-decoration: none;
				color: white;
				transition: 0.5s;
			}
			a#editPassword {
				border-bottom-left-radius: 15px;
            	width: 50%;
			}
			#editPassword:hover, #editPassword:focus {
				background-color: rgb(190,0,0);
				transition: 0.5s;
			}
			a#editInfos {
				border-bottom-right-radius: 15px;
            	width: 50%;
			}
			#editInfos:hover, #editInfos:focus {
				background-color: rgb(190,0,0);
				transition: 0.5s;
			}
        </style>
    </head>
    <body>
        <?php
            include_once("connexionDB.php");
            include_once('menu.php');

            if(isset($_SESSION['username'])) {

                $req = $bdd->prepare('SELECT * FROM Users WHERE username=:username');
                $req->bindValue(':username',$_SESSION['username']);
                $req->execute();
                $data = $req->fetch();
                
                $username = $data['username'];
                $nom = $data['nom'];
                $prenom = $data['prenom'];
                $id = $data['userId'];


                echo "<script>document.getElementById('profilMenu').setAttribute('class','selected');</script>";
                echo '<div id="container"><div id="content">
                        <h3>Bienvenue sur ton profil ',$username,' !</h3>
                        <p>Sur cette page, tu peux voir tes informations personnelles et modifier ton mot de passe.</p>
                        <p>Nom d\'utilisateur : ',$username,'</p>
                        <p>Nom : ',$nom,'</p>
                        <p>Prénom : ',$prenom,'</p>
                        <a id="editPassword" href="modifierPassword.php?id=',$id,'">Modifier le mot de passe</a><a id="editInfos" href="">Modifier le nom et/ou le prénom</a>
                    </div>';
            } else {
                header('Location: index.php');
            }
        ?>
        </div>
    </body>
</html>