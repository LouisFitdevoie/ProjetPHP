<?php session_start() ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php
        include_once("connexionDB.php");
        //On récupère l'ID du film passé dans la requête pour afficher le titre du film en tant que titre de la page
        $id = $_GET['id'];
        $regexNb = '/^[0-9]+$/i';
        if(!preg_match($regexNb,$id)) {
            echo '<script>window.location.href = "films.php";</script>';
            exit();
        }
        $req = $bdd->prepare('SELECT * FROM Films WHERE filmId=:filmId');
        $req->bindValue(':filmId',$id);
        $req->execute();
        $data = $req->fetch();
        if(empty($data['titre'])) {
            echo '<script>window.location.href = "films.php";</script>';
            exit();
        }
    ?>
	<title><?php 
        echo $data["titre"];
    ?></title>
	<meta charset="utf-8">
	<meta name='author' content='Louis Fitdevoie'>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="Ressources/style.css">
</head>
<body>
    <?php
        include_once("menu.php");
    ?>
    <div id='infosFilmContent'>
    <?php
        echo '<table><tr><td><img src="Ressources/img/films/',$data['imageLink'],'" id="detailFilmImage"></td>';
        echo "<td><h1>",$data['titre'],"</h1>";
        echo '<div id="duree"><h2>Durée : </h2><p>',$data['duree'],'</p></div>';
        echo '<h2>Résumé :</h2><p>',$data['resume'],'</p>';
        $jourActuel = date('d');
        $moisActuel = date('m');
        $anneeActuelle = date('Y');
        $dateSortie = explode(' ',$data['dateDeSortie']);
        $jourSortie = $dateSortie[0];
        $listeMois = ['janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
        if($dateSortie[1] == $listeMois[0]) {
            $moisSortie = '01';
        } elseif($dateSortie[1] == $listeMois[1]) {
            $moisSortie = '02';
        } elseif($dateSortie[1] == $listeMois[2]) {
            $moisSortie = '03';
        } elseif($dateSortie[1] == $listeMois[3]) {
            $moisSortie = '04';
        } elseif($dateSortie[1] == $listeMois[4]) {
            $moisSortie = '05';
        } elseif($dateSortie[1] == $listeMois[5]) {
            $moisSortie = '06';
        } elseif($dateSortie[1] == $listeMois[6]) {
            $moisSortie = '07';
        } elseif($dateSortie[1] == $listeMois[7]) {
            $moisSortie = '08';
        } elseif($dateSortie[1] == $listeMois[8]) {
            $moisSortie = '09';
        } elseif($dateSortie[1] == $listeMois[9]) {
            $moisSortie = '10';
        } elseif($dateSortie[1] == $listeMois[10]) {
            $moisSortie = '11';
        } elseif($dateSortie[1] == $listeMois[11]) {
            $moisSortie = '12';
        }
        $anneeSortie = $dateSortie[2];
        //On vérifie si le film est déjà sorti ou pas encore
        if($anneeActuelle < $anneeSortie) {
            echo '<h2>Date de sortie :</h2><p>Le film n\'a pas encore été diffusé, la date de sortie prévue est le ',$data['dateDeSortie'],'</p>';
        } elseif($anneeActuelle == $anneeSortie) {
            if($moisActuel < $moisSortie) {
                echo '<h2>Date de sortie :</h2><p>Le film n\'a pas encore été diffusé, la date de sortie prévue est le ',$data['dateDeSortie'],'</p>';
            } elseif($moisActuel == $moisSortie) {
                if($jourActuel < $jourSortie) {
                    echo '<h2>Date de sortie :</h2><p>Le film n\'a pas encore été diffusé, la date de sortie prévue est le ',$data['dateDeSortie'],'</p>';
                } elseif($jourActuel == $jourSortie) {
                    echo '<div id="filmSorti"><h2>Date de sortie : </h2><p>',$data['dateDeSortie'],'</p></div>';
                } else {
                    echo '<div id="filmSorti"><h2>Date de sortie : </h2><p>',$data['dateDeSortie'],'</p></div>';
                }
            } else {
                echo '<div id="filmSorti"><h2>Date de sortie : </h2><p>',$data['dateDeSortie'],'</p></div>';
            }
        } else {
            echo '<div id="filmSorti"><h2>Date de sortie : </h2><p>',$data['dateDeSortie'],'</p></div>';
        }
        echo '<h2>Acteurs principaux :</h2><p>',$data['acteurs'],'</p>';
        echo '<div id="bandeAnnonce"><h2>Bande annonce : </h2>';
        echo '<a href="',$data['bandeAnnonce'],'" target="_blank" id="bandeAnnonce">Cliquez ici pour voir la bande annonce sur Youtube !</a></div>';
        if(empty($data['streaming'])) {
            echo '<p>Non disponible en streaming</p>';
        } else {
            echo '<p><a href="',$data['streamingLink'],'" target="_blank" id="streamingLink">Cliquez ici pour voir le film sur ',$data['streaming'],' !</a></p>';
        }
        if($_SESSION['droits'] == 1) {
            echo '<div id="adminBtn"><a href="modifierFilm.php?id=',$data['filmId'],'" id="modifFilmBtn">Modifier ce film</a><a href="supprimerFilm.php?id=',$data['filmId'],'" id="deleteFilmBtn">Supprimer ce film</a></div></td></tr></table>';
        } else {
            echo '</td></tr></table>';
        }
    ?>
    </div>
    <?php
        //On vérifie que l'utilisateur est connecté -> si oui on il peut laisser un commentaire
        if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
            echo '<div id="newComment">';
            echo '<h2>Ajouter un commentaire</h2>';
            echo '<div class="comment">';
                echo '<div class="commentContent">';
                    echo '<form name="addComment" action="addComment.php" id="addCommentForm" method="POST">';
                        echo '<textarea id="textareaComment" name="content" placeholder="Écrivez votre commentaire..."></textarea>';
                        echo '<div id="note">';
                            echo '<p>Quelle note souhaitez-vous mettre au film ? </p>';
                            echo ' <select name="note">';
                                echo '<option value="0">0★</option>';
                                echo '<option value="1">1★</option>';
                                echo '<option value="2">2★</option>';
                                echo '<option value="3">3★</option>';
                                echo '<option value="4">4★</option>';
                                echo '<option value="5" selected>5★</option>';
                            echo '</select>';
                        echo '</div>';
                        if(isset($_SESSION['erreurContent'])) {
                            if($_SESSION['erreurContent'] == true) {
                                echo "<p style='font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;'>Le commentaire ne doit pas commencer ou terminer par un ou des espaces !</p>";
                            }
                        }
                        echo '<input type="hidden" value="',$_SESSION["username"],'" name="username">';
                        echo '<input type="hidden" value="',$id,'" name="filmId">';
                        echo '<input type="submit" name="addComment" id="submitBtn" value="Commenter">';
                    echo '</form>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        }
    ?>
    
    <div id=comments>
        <h2>Commentaires du film</h2>
        <?php
            //On récupère tous les commentaires du film et on les affiche
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
                    //Si l'utilisateur est l'auteur du commentaire -> on affiche un bouton pour modifier son commentaire et un bouton pour le supprimer
                    if($auteur[0] == $_SESSION['username']) {
                        echo '<a href="modifierComment.php?id=',$commentsListFetched['commentId'],'" class="editComment">✏️ Modifier ce commentaire</a><a href="deleteComment.php?id=',$commentsListFetched['commentId'],'" class="removeComment">🗑 Supprimer ce commentaire</a>';
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