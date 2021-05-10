<?php
    session_start();
    include_once('connexionDB.php');
    //Si l'utilisateur est connecté
    if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        //On récupère l'ID du commentaire dans la requête et on vérifie que ce soit bien un nombre
        $id = $_GET['id'];
        $regexNb = '/^[0-9]+$/i';
        if(!preg_match($regexNb,$id)) {
            echo '<script>window.location.href = "films.php";</script>';
            exit();
        } else {
            $request = $bdd->prepare('SELECT count(*) FROM Comments WHERE commentId=:id');
            $request->bindValue(':id',$id);
            $request->execute;
            //Si le commentaire existe -> on récupère l'ID du film qui est commenté, on supprime le commentaire et on redirige vers la page du film
            if($request == 1) {
                $getFilmId = $bdd->prepare('SELECT filmId FROM Commentaires WHERE commentId=:id');
                $getFilmId->bindValue(':id',$id);
                $getFilmId->execute();
                $filmId = $getFilmId->fetch();
                $deleteComment = $bdd->prepare('DELETE FROM Commentaires WHERE commentId=:id');
                $deleteComment->bindValue(':id',$id);
                $deleteComment->execute();
                echo '<script>window.location.href = "infosFilm.php?id=',$filmId[0],'";</script>';
            } else {
                echo '<script>window.location.href = "films.php";</script>';
                exit();
            }
        }
    }
?>