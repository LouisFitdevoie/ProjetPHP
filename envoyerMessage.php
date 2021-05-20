<?php
    session_start();
    include_once("connexionDB.php");
    //On vérifie que l'utilisateur soit connecté
    if(isset($_SESSION['username']) && isset($_SESSION['droits'])) {
        //echo 'connecté<br>';
        //On vérifie que l'utilisateur a bien cliqué sur le bouton pour envoyer le message
        if(isset($_POST['addMessage'])) {
            //echo 'bouton<br>';
            //On réinitialise les erreurs
            $erreur['content'] = false;
            $erreur['auteurId'] = false;
            $erreur['destinataireId'] = false;
            //On vérifie que le contenu du message ne commence ou ne finisse pas par un ou plusieurs espaces
            $regexContent = '/^[^\s]+(\s+[^\s]+)*$/i';
            if(preg_match($regexContent,$_POST['content'])) {
                //Si c'est correct -> on enregistre le contenu dans une variable
                $content = $_POST['content'];
            } else {
                //Sinon -> initialisation de l'erreur du contenu à TRUE
                $erreur['content'] = true;
            }
            //On vérifie que l'auteur existe bien dans la BDD
            $auteur = $_SESSION['username'];
            $verifUser = $bdd->prepare('SELECT count(*) FROM Users WHERE username=:auteur');
            $verifUser->bindValue(':auteur',$auteur);
            $verifUser->execute();
            $userExist = $verifUser->fetch();
            if($userExist[0] == 1) {
                //Si l'utilisateur existe -> on récupère son ID d'utilisateur
                //echo 'user existe<br>';
                $getUserId = $bdd->prepare('SELECT userId FROM Users WHERE username=:auteur');
                $getUserId->bindValue(':auteur',$auteur);
                $getUserId->execute();
                $userId = $getUserId->fetch();
                $userId = $userId[0];
                //echo 'userId = ',$userId,'<br>';
            } else {
                //Sinon -> initialisation de l'erreur d'auteur en TRUE
                $erreur['auteurId'] = true;
            }
            //On vérifie que l'ID du destinataire est bien un nombre
            $regexNombre = '/^[0-9]+/i';
            if(preg_match($regexNombre,$_POST['destinataireId'])) {
                //On vérifie que l'ID du destinataire existe dans la BDD
                $verifDestinataire = $bdd->prepare('SELECT count(*) FROM Users WHERE userId=:destinataireId');
                $verifDestinataire->bindValue(':destinataireId',$_POST['destinataireId']);
                $verifDestinataire->execute();
                $destinataireOK = $verifDestinataire->fetch();
                if($destinataireOK[0] == 1) {
                    //Si l'utilisateur existe -> on enregistre l'ID dans une variable
                    $destinataireId = $_POST['destinataireId'];
                } else {
                    $erreur['destinataireId'] = true;
                }
            } else {
                $erreur['destinataireId'] = true;
            }
            //S'il n'y a pas d'erreur -> on enregistre le message dans la BDD et on redirige l'utilisateur vers son profil
            if(!$erreur['content'] && !$erreur['auteurId'] && !$erreur['destinataireId']) {
                //echo 'pas erreur';
                $sendMessage = $bdd->prepare('INSERT INTO Messages VALUES (null,:content,CURRENT_TIMESTAMP,:auteurId,:destinataireId)');
                $sendMessage->bindValue(':content',$content);
                $sendMessage->bindValue(':auteurId',$userId);
                $sendMessage->bindValue(':destinataireId',$destinataireId);
                $sendMessage->execute();
                echo '<script>window.location.href = "profil.php";</script>';
                exit();
            }
            
        } else {
            echo '<script>window.location.href = "index.php";</script>';
        }
    } else {
        echo '<script>window.location.href = "index.php";</script>';
    }
?>