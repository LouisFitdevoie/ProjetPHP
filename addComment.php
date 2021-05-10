<?php
    session_start();
    include_once('connexionDB.php');
    //Réinitialisation des erreurs
    $erreur['filmId'] = false;
    $erreur['username'] = false;
    $erreur['note'] = false;
    $erreur['content'] = false;
    //On vérifie si l'utilisateur est connecté
    if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        //On vérifie si l'utilisateur a cliqué sur le bouton pour ajouter un commentaire
        if(isset($_POST['addComment'])) {
            //On vérifie si les champs filmId, username et content ne sont pas vides
            if(!empty($_POST['filmId']) && !empty($_POST['username']) && !empty($_POST['content'])) {
                $regexNombre = '/^[0-9]*/i';
                //On vérifie que le filmId soit bien un chiffre
                if(preg_match($regexNombre,$_POST['filmId'])) {
                    //Si oui -> on récupère l'ID du film et on vérifie qu'il existe bien dans la base de données
                    $filmId = $_POST['filmId'];

                    $req = $bdd->prepare('SELECT count(*) FROM Films WHERE filmId=:filmId');
                    $req->bindValue(':filmId',$filmId);
                    $req->execute();
                    $data = $req->fetch();
                    if($data[0] != 1) {
                        //Si le film n'est pas dans la DB -> on initialise l'erreur du filmId en true
                        $erreur['filmId'] = true;
                    }
                } else {
                    //Si le film n'est pas un chiffre -> on initialise l'erreur du filmId en true
                    $erreur['filmId'] = true;
                }

                $_SESSION['erreurContent'] = true;

                //On vérifie si l'ID d'utilisateur correspond bien à un utilisateur dans la DB
                $username = $_POST['username'];
                $req = $bdd->prepare('SELECT count(*) FROM Users WHERE username=:username');
                $req->bindValue(':username',$username);
                $req->execute();
                $data = $req->fetch();
                if($data[0] != 1) {
                    //Si pas dans la DB -> on initialise l'erreur de username en true
                    $erreur['username'] = true;
                } else {
                    //Si dans la DB -> on récupère l'username qui correspond à l'ID
                    $user = $bdd->prepare('SELECT * FROM Users WHERE username=:username');
                    $user->bindValue(':username',$username);
                    $user->execute();
                    $userFetched = $user->fetch();
                    $auteurId = $userFetched[0];
                }

                //On vérifie que le contenu ne commence ou ne fini pas par un ou plusieurs espaces (évite commentaires vides)
                $regexContent = '/^[^\s]+(\s+[^\s]+)*$/i';
                if(preg_match($regexContent,$_POST['content'])) {
                    $content = $_POST['content'];
                } else {
                    $erreur['content'] = true;
                    $_POST['erreurContent'] = true;
                    echo '<script>window.location.href = "infosFilm.php?id=',$filmId,'";</script>';
                    exit();
                }

                //On vérifie que la note soit bien entre 0 et 5
                $regexNote = '/^[0-5]$/i';
                if(preg_match($regexNote,$_POST['note'])) {
                    $note = $_POST['note'];
                } else {
                    $erreur['note'] = true;
                }

                //Si pas d'erreurs -> enregistrement dans la DB et redirection vers la page du film commenté
                if(!$erreur['filmId'] && !$erreur['auteurId'] && !$erreur['content'] && !$erreur['note']) {
                    $addComment = $bdd->prepare('INSERT INTO Commentaires VALUES (null,:auteurId,:filmId,:content,:note,CURRENT_TIMESTAMP)');
                    $addComment->bindValue(':auteurId',$auteurId);
                    $addComment->bindValue(':filmId',$filmId);
                    $addComment->bindValue(':content',$content);
                    $addComment->bindValue(':note',$note);
                    $addComment->execute();
                    $addComment->closeCursor();
                    
                    $_SESSION['erreurContent'] = '';
                    echo '<script>window.location.href = "infosFilm.php?id=',$filmId,'";</script>';
                    exit();
                }

            } else {
                if(isset($_POST['filmId']) && !empty($_POST['filmId'])) {
                    $regexNombre = '/^[0-9]*/i';
                    if(preg_match($regexNombre,$_POST['filmId'])) {
                        echo '<script>window.location.href = "',$location,'";</script>';
                    } else {
                        echo '<script>window.location.href = "films.php";</script>';
                        exit();
                    }
                } else {
                    echo '<script>window.location.href = "films.php";</script>';
                }
            }
        } else {
            echo '<script>window.location.href = "films.php";</script>';
        }
    } else {
        if(isset($_POST['filmId']) && !empty($_POST['filmId'])) {
            $regexNombre = '/^[0-9]*/i';
            if(preg_match($regexNombre,$_POST['filmId'])) {
                echo '<script>window.location.href = "',$location,'";</script>';
            } else {
                echo '<script>window.location.href = "films.php";</script>';
                exit();
            }
        } else {
            echo '<script>window.location.href = "films.php";</script>';
            exit();
        }
    }
?>