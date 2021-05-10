<?php session_start() ?>
<!DOCTYPE html>
<html lang='fr'>
<head>
    <title>Modifier le commentaire</title>
    <meta charset="utf-8">
    <meta name='author' content='Louis Fitdevoie'>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="Ressources/style.css">
</head>
<body>
    <?php
        include_once("connexionDB.php");
        include_once("menu.php");
        //On vérifie que l'utilisateur soit connecté
        if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {

            $erreur['note'] = false;
            $erreur['content'] = false;
            //On vérifie que l'ID de commentaire ait bien été passé dans la requête
            if(isset($_GET['id'])) {
                $id = $_GET['id'];
                $regexNb = '/^[0-9]+$/i';
                //On vérifie que l'ID soit bien un chiffre
                if(preg_match($regexNb,$id)) {
                    $verifCommentExiste = $bdd->prepare('SELECT count(*) FROM Commentaires WHERE commentId=:id');
                    $verifCommentExiste->bindValue(':id',$id);
                    $verifCommentExiste->execute();
                    $verifCommentExisteFetched = $verifCommentExiste->fetch();
                    //On vérifie si le commentaire associé à l'ID existe bien dans la BDD
                    if($verifCommentExisteFetched[0] == 1) {
                        //On récupère les données du commentaire pour les afficher
                        $commentAvantModif = $bdd->prepare('SELECT * FROM Commentaires WHERE commentId=:id');
                        $commentAvantModif->bindValue(':id',$id);
                        $commentAvantModif->execute();
                        $valeursCommentAvantModif = $commentAvantModif->fetch();
                        $auteurId = $valeursCommentAvantModif['auteurId'];
                        $note = $valeursCommentAvantModif['note'];
                        $content = $valeursCommentAvantModif['content'];
                        //On vérifie que l'utilisateur qui veut modifier le commentaire soit celui qui l'a écrit
                        $verifBonUser = $bdd->prepare('SELECT username From Users WHERE userId=:auteurId');
                        $verifBonUser->bindValue(':auteurId',$auteurId);
                        $verifBonUser->execute();
                        $auteur = $verifBonUser->fetch();
                        if($_SESSION['username'] != $auteur[0]) {
                            echo '<script>window.location.href = "films.php";</script>';
                            exit();
                        }
                    } else {
                        echo '<script>window.location.href = "films.php";</script>';
                        exit();
                    }
                } else {
                    echo '<script>window.location.href = "films.php";</script>';
                    exit();
                }
            //Si l'utilisateur a cliqué sur le bouton pour modifier le commentaire
            } elseif(isset($_POST['modifComment'])) {
                //On vérifie que tous les champs sont bien complétés
                if(isset($_POST['commentId']) && !empty($_POST['commentId'])) {
                    if(isset($_POST['content']) && !empty($_POST['content'])) {
                        if(isset($_POST['note']) && !empty($_POST['note'])) {
                            $id = $_POST['commentId'];
                            $regexNb = '/^[0-9]+$/i';
                            //On vérifie que l'ID du commentaire soit bien un nombre et qu'il existe dans la BDD
                            if(preg_match($regexNb,$id)) {
                                $verifCommentExiste = $bdd->prepare('SELECT count(*) FROM Commentaires WHERE commentId=:id');
                                $verifCommentExiste->bindValue(':id',$id);
                                $verifCommentExiste->execute();
                                $verifCommentExisteFetched = $verifCommentExiste->fetch();
                                
                                if($verifCommentExisteFetched[0] == 1) {
                                    $regexNote = '/^[0-5]$/i';
                                    //On vérifie que la note entrée soit bien entre 0 et 5
                                    if(preg_match($regexNote,$_POST['note'])) {
                                        $note = $_POST['note'];
                                    } else {
                                        $erreur['note'] = true;
                                    }
                                    //On vérifie que le contenu du commentaire ne commencent ou ne finissent pas par un ou plusieurs espaces
                                    $regexContent = '/^[^\s]+(\s+[^\s]+)*$/i';
                                    if(preg_match($regexContent,$_POST['content'])) {
                                        $content = $_POST['content'];
                                    } else {
                                        $erreur['content'] = true;
                                        $contentAncien = $bdd->prepare('SELECT content FROM Commentaires WHERE commentId=:id');
                                        $contentAncien->bindValue(':id',$id);
                                        $contentAncien->execute();
                                        $content = $contentAncien->fetch();
                                        $content = $content[0];
                                    }
                                    //S'il n'y a pas d'erreur -> enreigstrement dans la BDD
                                    if(!$erreur['note'] && !$erreur['content']) {
                                        $miseAJourComment = $bdd->prepare('UPDATE Commentaires SET content=:content,note=:note,date=CURRENT_TIMESTAMP WHERE commentId=:commentId');
                                        $miseAJourComment->bindValue(':content',$content);
                                        $miseAJourComment->bindValue(':note',$note);
                                        $miseAJourComment->bindValue(':commentId',$id);
                                        $miseAJourComment->execute();

                                        $getFilmId = $bdd->prepare('SELECT filmId FROM Commentaires WHERE commentId=:commentId');
                                        $getFilmId->bindValue(':commentId',$id);
                                        $getFilmId->execute();

                                        $filmId = $getFilmId->fetch();
                                        echo '<script>window.location.href = "infosFilm.php?id=',$filmId[0],'";</script>';
                                    exit();
                                    }
                                } else {
                                    echo '<script>window.location.href = "films.php";</script>';
                                    exit();
                                }
                            } else {
                                echo '<script>window.location.href = "films.php";</script>';
                                exit();
                            }
                        }
                    }
                } else {
                    echo '<script>window.location.href = "films.php";</script>';
                    exit();
                }
            } else {
                echo '<script>window.location.href = "films.php";</script>';
                exit();
            }
        } else {
            echo '<script>window.location.href = "films.php";</script>';
            exit();
        }
    ?>
    <div id="modifierCommentContent">
        <div id="newComment">
            <h2>Modifier le commentaire</h2>
            <div class="comment">
                <div class="commentContent">
                    <form name="addComment" action="modifierComment.php" id="addCommentForm" method="POST">
                        <textarea name="content" placeholder="Écrivez votre commentaire..."><?= $content ?></textarea>
                        <div id="note">
                            <p>Quelle note souhaitez-vous mettre au film ? </p>
                            <select name="note">
                                <option value="0" <?php if($note == 0) { echo 'selected'; } ?>>0★</option>
                                <option value="1" <?php if($note == 1) { echo 'selected'; } ?>>1★</option>
                                <option value="2" <?php if($note == 2) { echo 'selected'; } ?>>2★</option>
                                <option value="3" <?php if($note == 3) { echo 'selected'; } ?>>3★</option>
                                <option value="4" <?php if($note == 4) { echo 'selected'; } ?>>4★</option>
                                <option value="5" <?php if($note == 5) { echo 'selected'; } ?>>5★</option>
                            </select>
                        </div>
                        <input type="hidden" value=<?php echo '"',$id,'"'; ?> name="commentId">
                        <input type="submit" name="modifComment" id="submitBtn" value="Modifier">
                        <?php
                            if($erreur['content']) {
                                echo "<p style='font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;'>Le commentaire ne doit pas commencer ou finir par un ou plusieurs espaces !</p>";
                            } else {
                                if($erreur['note']) {
                                    echo "<p style='font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;'>La note n'est pas un nombre de 1 à 5, réessayez !</p>";
                                }
                            }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>