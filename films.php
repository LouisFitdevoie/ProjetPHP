<?php session_start() ?>
<html lang="fr">
<head>
	<title>Accueil</title>
	<meta charset="utf-8">
	<meta name='author' content='Louis Fitdevoie'>
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
        #colonneFilms {
            display: flex;
            flex-flow: column wrap;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }
        .film {
            display: flex;
            flex-flow: row nowrap;
            padding: 20px;
            align-items: center;
        }
        .film div {
            width: 100%;
            margin-left: 10px;
            text-align: center;
        }
        .film div a {
            text-decoration: none;
            color: #555555;
            padding-right: 15px;
            padding-left: 15px;
            padding-top: 10px;
            padding-bottom: 10px;
            border-radius: 20px;
            border: 2px solid #555555;
            background-color: white;
            transition: 500ms;
        }
        .film div a:hover, .film div a:focus {
            background-color: #333333;
            color: white;
            transition: 500ms;
        }
        .film div a:active {
            background-color: #222222;
        }
        .filmImage {
            max-width: 200px;
            height: auto;
            border: 5px solid #555555;
        }
        #addFilmBtn {
            width: 120px;
            text-align: center;
            text-decoration: none;
            color: #555555;
            padding-right: 15px;
            padding-left: 15px;
            padding-top: 10px;
            padding-bottom: 10px;
            border-radius: 20px;
            border: 2px solid #555555;
            background-color: white;
            margin-left: auto;
            margin-right: auto;
        }
        a#addFilmBtn:hover, a#addFilmBtn:focus {
            background-color: #333333;
            color: white;
            transition: 500ms;
        }
        a#addFilmBtn:active {
            background-color: #222222;
        }
        .ligne {
            width: 100%;
            height: 1px;
        }
        .ligne > div {
            height: 100%;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
            background-color: #555555;
        }
    </style>
</head>
<body>
    <?php
        include_once('menu.php');
        include_once('connexionDB.php');
        echo "<script>document.getElementById('filmsMenu').setAttribute('class','selected');</script>";
    ?>
    <?php
        if($_SESSION['droits'] == 1) {
            echo '<a href="addFilm.php" id="addFilmBtn">Ajouter un film</a>';
        }

        $req = $bdd->query('SELECT * FROM Films ORDER BY filmId');

        $nbFilms = 0;
        echo '<div id="colonneFilms">';
        while($data = $req->fetch()) {
            $nbFilms++;
            echo '<div class="film">';
            echo '<img src="Ressources/img/films/',$data['imageLink'],'" class="filmImage">';
            echo '<div>';
            echo '<h2>',$nbFilms,') ',$data['titre'],'</h2>';
            echo '<a href="infosFilm.php?id=',$data["filmId"],'">&#x2794 Plus d\'informations</a>';
            echo '</div>';
            echo '</div>';
            echo '<div class="ligne"><div></div></div>';
        }
        echo '</div>';
    ?>    
</body>
</html>