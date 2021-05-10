<?php session_start() ?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Liste des films du MCU</title>
	<meta charset="utf-8">
	<meta name='author' content='Louis Fitdevoie'>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="Ressources/style.css">
</head>
<body>
    <?php
        include_once('menu.php');
        include_once('connexionDB.php');
        echo "<script>document.getElementById('filmsMenu').setAttribute('class','selected');</script>";
    ?>
    <?php
        //Si l'utilisateur a les droits d'administration -> ajout d'un bouton qui permet d'ajouter un film
        if(isset($_SESSION['droits']) && $_SESSION['droits'] == 1) {
            echo '<a href="addFilm.php" id="addFilmBtn">Ajouter un film</a>';
        }

        //On récupère tous les films et on les affiche
        $req = $bdd->query('SELECT * FROM Films ORDER BY ordreSortie');

        echo '<div id="colonneFilms">';
        while($data = $req->fetch()) {
            echo '<div class="film">';
            echo '<img src="Ressources/img/films/',$data['imageLink'],'" class="filmImage" alt="Affiche du film ',$data['titre'],'">';
            echo '<div>';
            echo '<h2>',$data['ordreSortie'],') ',$data['titre'],'</h2>';
            echo '<a href="infosFilm.php?id=',$data["filmId"],'">&#x2794; Plus d\'informations</a>';
            echo '</div>';
            echo '</div>';
            echo '<div class="ligne"><div></div></div>';
        }
        echo '</div>';
    ?>    
</body>
</html>