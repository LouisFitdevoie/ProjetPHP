<?php
    session_start();
    include_once("connexionDB.php");
    if(isset($_SESSION['username']) && isset($_SESSION['droits'])) {
        echo 'connecté<br>';
        if(isset($_POST['addMessage'])) {
            echo 'bouton<br>';
            $erreur['content'] = false;
            $erreur['auteurId'] = false;
            $erreur['destinataireId'] = false;

            $regexContent = '/^[^\s]+(\s+[^\s]+)*$/i';
            if(preg_match($regexContent,$_POST['content'])) {
                $content = $_POST['content'];
            } else {
                $erreur['content'] = true;
            }

            $auteur = $_SESSION['username'];
            $verifUser = $bdd->prepare('SELECT count(*) FROM Users WHERE username=:auteur');
            $verifUser->bindValue(':auteur',$auteur);
            $verifUser->execute();
            $userExist = $verifUser->fetch();
            if($userExist[0] == 1) {
                echo 'user existe<br>';
                $getUserId = $bdd->prepare('SELECT userId FROM Users WHERE username=:auteur');
                $getUserId->bindValue(':auteur',$auteur);
                $getUserId->execute();
                $userId = $getUserId->fetch();
                $userId = $userId[0];
                echo 'userId = ',$userId,'<br>';
            } else {
                $erreur['auteurId'] = false;
            }

            $regexNombre = '/^[0-9]*/i';
            if(preg_match($regexNombre,$_POST['destinataireId'])) {
                $verifDestinataire = $bdd->prepare('SELECT count(*) FROM Users WHERE userId=:destinataireId');
                $verifDestinataire->bindValue(':destinataireId',$_POST['destinataireId']);
                $verifDestinataire->execute();
                $destinataireOK = $verifDestinataire->fetch();
                if($destinataireOK[0] == 1) {
                    $destinataireId = $_POST['destinataireId'];
                } else {
                    $erreur['destinataireId'] = true;
                }
            } else {
                $erreur['destinataireId'] = true;
            }

            if(!$erreur['content'] && !$erreur['auteurId'] && !$erreur['destinataireId']) {
                echo 'pas erreur';
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