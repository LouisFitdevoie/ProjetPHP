<?php session_start() ?>
<html lang="fr">
<head>
    <?php
        include_once("connexionDB.php");
        $id = $_GET['id'];
        $regexNb = '/^[0-9]+$/i';
        if(!preg_match($regexNb,$id)) {
            header('Location: films.php');
            exit();
        }
        $req = $bdd->prepare('SELECT * FROM Films WHERE filmId=:filmId');
        $req->bindValue(':filmId',$id);
        $req->execute();
        $data = $req->fetch();
        if(empty($data['titre'])) {
            header('Location: films.php');
            exit();
        }
    ?>
	<title><?php 
        echo $data["titre"];
    ?></title>
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
        div#content {
            display: flex;
            flex-flow: column nowrap;
            max-width: 1000px;
            margin-left: auto;
            margin-top: 20px;
            margin-right: auto;
            text-align: justify;
            padding: 15px;
            border: 2px solid rgb(99,99,99);
            border-radius: 15px;
        }
        #detailFilmImage {
            max-width: 250px;
            height: auto;
            border: 3px solid rgb(99,99,99);
            margin-right: 20px;
        }
        #bandeAnnonce {
            color: red;
            text-decoration: none;
            cursor: pointer;
        }
        #streamingLink {
            color: red;
            text-decoration: none;
            cursor: pointer;
        }
        a {
            margin-left: auto;
            margin-right: auto;
        }
        #adminBtn {
            margin-top: 10px;
            display: flex;
            flex-flow: row nowrap;
        }
        a#modifFilmBtn {
            display: block;
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
            transition: 500ms;
        }
        a#modifFilmBtn:hover, a#modifFilmBtn:focus {
            background-color: #333333;
            color: white;
            transition: 500ms;
        }
        a#modifFilmBtn:active {
            background-color: #222222;
        }
        a#deleteFilmBtn {
            display: block;
            width: 150px;
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
            transition: 250ms;
        }
        a#deleteFilmBtn:hover, a#deleteFilmBtn:focus {
            background-color: #E13930;
            color: white;
            transition: 500ms;
        }
        a#deleteFilmBtn:active {
            background-color: #C91708;
        }
        div#newComment h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        #addCommentForm {
            margin: 5px;
            text-align: center;
        }
        textarea {
            font-family: Calibri, Arial, sans-serif;
            font-size: 0.9em;
            width: 100%;
            height: 100px;
        }
        div#note * {
            display: inline-block;
            margin-top: 5px;
            margin-bottom: 5px;
        }
        select {
            width: 50px;
        }
        #submitBtn {
            font-size: 0.8em;
            margin-top: 5px;
            margin-bottom: 5px;
            padding: 2px 15px;
            border: 1px solid rgb(15,15,15);
            border-radius: 5px;
            background-color: #EEEEEE;
            color: #555555;
            cursor: pointer;
            transition: 350ms;
        }
        #submitBtn:hover, #submitBtn:focus {
            background-color: #555555;
            color: #EEEEEE;
            transition: 350ms;
        }

        div#comments {
            display: flex;
            flex-flow: column wrap;
        }
        div#comments h2 {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .comment {
            background-color: rgb(33,33,33);
            width: 750px;
            border: 1px solid rgb(99,99,99);
            border-radius: 15px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 10px;
        }
        .container {
            margin: 0;
            width: 100%;
        }
        .auteur {
            text-align: center;
            border-bottom: 1px solid rgb(99,99,99);
            font-size: 1.1em
        }
        .auteur > div {
            height: 20px;
            padding-top: 5px;
            padding-bottom: 5px;
            font-style: italic;
        }
        .commentContent {
            padding: 10px;
        }
        .dateEtNote {
            border-top: 1px solid rgb(99,99,99);
            margin: 0;
        }
        .dateEtNote > * {
            display: inline-block;
            margin: 0;
            width: 50%;
            padding: 5px 0px;
            text-align: center;
        }
        .star {
            display: inline;
            color: white;
        }
        .star.yellow {
            color: red;
        }
        .editComment {
            text-decoration: none;
            color: white;
            border-top: 1px solid rgb(99,99,99);
            border-bottom-left-radius: 15px;
            transition: 0.5s;
        }
        .editComment:hover, .editComment:focus {
            background-color: rgb(190,0,0);
            transition: 0.5s;
        }
        .removeComment {
            text-decoration: none;
            color: white;
            border-top: 1px solid rgb(99,99,99);
            border-bottom-right-radius: 15px;
            transition: 0.5s;
        }
        .removeComment:hover, .removeComment:focus {
            background-color: rgb(190,0,0);
            transition: 0.5s;
        }
        #noComment {
            width: auto;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php
        include_once("menu.php");
    ?>
    <div id='content'>
    <?php
        echo '<table><tr><td><img src="Ressources/img/films/',$data['imageLink'],'" id="detailFilmImage"></td>';
        echo "<td><h1>",$data['titre'],"</h1>";
        echo 'Durée : ',$data['duree'],'<br>';
        echo 'Résumé :<br>',$data['resume'],'<br>';
        echo 'Date de sortie : ',$data['dateDeSortie'],'<br>';
        echo 'Acteurs principaux :<br>',$data['acteurs'],'<br>';
        echo 'Bande annonce : ';
        echo '<a href="',$data['bandeAnnonce'],'" target="_blank" id="bandeAnnonce">Cliquez ici pour voir la bande annonce sur Youtube !</a><br>';
        if(empty($data['streaming'])) {
            echo 'Non disponible en streaming';
        } else {
            echo '<a href="',$data['streamingLink'],'" target="_blank" id="streamingLink">Cliquez ici pour voir le film sur ',$data['streaming'],' !</a><br>';
        }
        if($_SESSION['droits'] == 1) {
            echo '<div id="adminBtn"><a href="modifierFilm.php?id=',$data['filmId'],'" id="modifFilmBtn">Modifier ce film</a><a href="supprimerFilm.php?id=',$data['filmId'],'" id="deleteFilmBtn">Supprimer ce film</a></div></td></tr></table>';
        } else {
            echo '</td></tr></table>';
        }
    ?>
    </div>
    <?php
        if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
            echo '<div id="newComment">
            <h2>Ajouter un commentaire</h2>
            <div class="comment">
                <div class="commentContent">
                    <form name="addComment" action="addComment.php" id="addCommentForm" method="POST">
                        <textarea name="content" placeholder="Écrivez votre commentaire..."></textarea>
                        <div id="note">
                            <p>Quelle note souhaitez-vous mettre au film ? </p>
                            <select name="note">
                                <option value="0">0★</option>
                                <option value="1">1★</option>
                                <option value="2">2★</option>
                                <option value="3">3★</option>
                                <option value="4">4★</option>
                                <option value="5" selected>5★</option>
                            </select>
                        </div>
                        <input type="hidden" value="',$_SESSION["username"],'" name="username">
                        <input type="hidden" value="',$id,'" name="filmId">
                        <input type="submit" name="addComment" id="submitBtn" value="Commenter">
                    </form>
                </div>
            </div>
        </div>';
        }
    ?>
    
    <div id=comments>
        <h2>Commentaires du film</h2>
        <?php
            $nbComments = $bdd->prepare('SELECT COUNT(*) FROM Commentaires WHERE filmId=:filmId');
            $nbComments->bindValue(':filmId',$id);
            $nbComments->execute();
            $nbCommentsFetched = $nbComments->fetch();
            if($nbCommentsFetched[0] > 0) {
                $commentsList = $bdd->prepare('SELECT * FROM Commentaires WHERE filmId=:filmId');
                $commentsList->bindValue(':filmId',$id);
                $commentsList->execute();
                while ($commentsListFetched = $commentsList->fetch()) {
                    echo '<div class="comment">';
                    $auteurName = $bdd->prepare('SELECT username FROM Users WHERE userId=:userId');
                    $auteurName->bindValue(':userId',$commentsListFetched['auteurId']);
                    $auteurName->execute();
                    $auteur = $auteurName->fetch();
                    echo '<div class="container"><div class="auteur"><div>',$auteur[0],'</div></div>';
                    echo '<div class="commentContent">',$commentsListFetched['content'],'</div>';
                    echo '<div class="dateEtNote"><div>Note : ';
                    for ($i = 1 ; $i <= $commentsListFetched['note'] ; $i++) { 
                        echo '<p class="star yellow">★</p>' ;
                    }
                    for ($i = 1 ; $i <= 5-$commentsListFetched['note'] ; $i++) {
                        echo '<p class="star">★</p>' ;
                    }
                    echo '</div><p>Date : ',$commentsListFetched['date'],'</p>';
                    if($auteur[0] == $_SESSION['username']) {
                        echo '<a href="" class="editComment">✏️ Modifier ce commentaire</a><a href="deleteComment.php?id=',$commentsListFetched['commentId'],'" class="removeComment">🗑 Supprimer ce commentaire</a>';
                    }
                    echo '</div></div></div>';
                }
            } else {
                echo '<div id="noComment">Il n\'y a pas encore de commentaires pour ce film !</div>';
            }
        ?>
    </div>
</body>
</html>