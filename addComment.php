<?php
    session_start();
    include_once('connexionDB.php');

    $erreur['filmId'] = false;
    $erreur['username'] = false;
    $erreur['note'] = false;
    $erreur['content'] = false;

    if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        if(isset($_POST['addComment'])) {
            if(!empty($_POST['filmId']) && !empty($_POST['username']) && !empty($_POST['content'])) {
                $regexNombre = '/^[0-9]*/i';
                if(preg_match($regexNombre,$_POST['filmId'])) {
                    echo 'film id ok';
                    $filmId = $_POST['filmId'];

                    $req = $bdd->prepare('SELECT count(*) FROM Films WHERE filmId=:filmId');
                    $req->bindValue(':filmId',$filmId);
                    $req->execute();
                    $data = $req->fetch();
                    if($data[0] != 1) {
                        echo 'film existe pas dans db';
                        $erreur['filmId'] = true;
                    }
                } else {
                    echo 'film id pas un nb';
                    $erreur['filmId'] = true;
                }
                
                $username = $_POST['username'];
                $req = $bdd->prepare('SELECT count(*) FROM Users WHERE username=:username');
                $req->bindValue(':username',$username);
                $req->execute();
                $data = $req->fetch();
                if($data[0] != 1) {
                    echo 'auteur existe pas dans db';
                    $erreur['username'] = true;
                } else {
                    echo 'username dans db';
                    $user = $bdd->prepare('SELECT * FROM Users WHERE username=:username');
                    $user->bindValue(':username',$username);
                    $user->execute();
                    $userFetched = $user->fetch();
                    $auteurId = $userFetched[0];
                }

                if($_POST['content'] != ' ') {
                    $content = $_POST['content'];
                } else {
                    $erreur['content'] = true;
                }

                $regexNote = '/^[0-5]$/i';
                if(preg_match($regexNote,$_POST['note'])) {
                    echo 'note ok';
                    $note = $_POST['note'];
                } else {
                    echo 'note pas ok';
                    $erreur['note'] = true;
                }
                $note = $_POST['note'];

                if(!$erreur['filmId'] && !$erreur['auteurId'] && !$erreur['content'] && !$erreur['note']) {
                    echo $auteurId;
                    $addComment = $bdd->prepare('INSERT INTO Commentaires VALUES (null,:auteurId,:filmId,:content,:note,CURRENT_TIMESTAMP)');
                    $addComment->bindValue(':auteurId',$auteurId);
                    $addComment->bindValue(':filmId',$filmId);
                    $addComment->bindValue(':content',$content);
                    $addComment->bindValue(':note',$note);
                    $addComment->execute();
                    $addComment->closeCursor();
                    
                    $location = 'Location: infosFilm.php?id='.$filmId;
                    header($location);
                }

            }
        } else {
            header('Location: films.php');
        }
    } else {
        if(isset($_POST['filmId']) && !empty($_POST['filmId'])) {
            $regexNombre = '/^[0-9]*/i';
            if(preg_match($regexNombre,$_POST['filmId'])) {
                $location = 'Location: infosFilm.php?id='.$filmId;
                header($location);
            } else {
                header('Location: films.php');
            }
        } else {
            header('Location: films.php');
        }
    }
?>