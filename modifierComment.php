<?php session_start() ?>
<html lang='fr'>
<head>
    <title>Modifier le commentaire</title>
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
    </style>
</head>
<body>
    <?php
        include_once("connexionDB.php");
        include_once("menu.php");
        if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {

            $erreur['note'] = false;
            $erreur['content'] = false;

            if(isset($_GET['id'])) {
                $id = $_GET['id'];
                $regexNb = '/^[0-9]+$/i';
                if(preg_match($regexNb,$id)) {
                    $verifCommentExiste = $bdd->prepare('SELECT count(*) FROM Commentaires WHERE commentId=:id');
                    $verifCommentExiste->bindValue(':id',$id);
                    $verifCommentExiste->execute();
                    $verifCommentExisteFetched = $verifCommentExiste->fetch();
                    if($verifCommentExisteFetched[0] == 1) {
                        $commentAvantModif = $bdd->prepare('SELECT * FROM Commentaires WHERE commentId=:id');
                        $commentAvantModif->bindValue(':id',$id);
                        $commentAvantModif->execute();
                        $valeursCommentAvantModif = $commentAvantModif->fetch();
                        $auteurId = $valeursCommentAvantModif['auteurId'];
    
                        $verifBonUser = $bdd->prepare('SELECT username From Users WHERE userId=:auteurId');
                        $verifBonUser->bindValue(':auteurId',$auteurId);
                        $verifBonUser->execute();
                        $auteur = $verifBonUser->fetch();
                        if($_SESSION['username'] != $auteur[0]) {
                            header('Location: films.php');
                            exit();
                        }
                    } else {
                        header('Location: films.php');
                        exit();
                    }
                } else {
                    header('Location: films.php');
                    exit();
                }
            } elseif(isset($_POST['modifComment'])) {
                if(isset($_POST['commentId']) && !empty($_POST['commentId'])) {
                    if(isset($_POST['content']) && !empty($_POST['content'])) {
                        if(isset($_POST['note']) && !empty($_POST['note'])) {
                            $id = $_POST['commentId'];
                            $regexNb = '/^[0-9]+$/i';
                            if(preg_match($regexNb,$id)) {
                                $verifCommentExiste = $bdd->prepare('SELECT count(*) FROM Commentaires WHERE commentId=:id');
                                $verifCommentExiste->bindValue(':id',$id);
                                $verifCommentExiste->execute();
                                $verifCommentExisteFetched = $verifCommentExiste->fetch();
                                if($verifCommentExisteFetched[0] == 1) {
                                    $regexNote = '/^[0-5]$/i';
                                    if(preg_match($regexNote,$_POST['note'])) {
                                        $note = $_POST['note'];
                                    } else {
                                        $erreur['note'] = true;
                                    }
        
                                    if($_POST['content'] != ' ') {
                                        $content = $_POST['content'];
                                    } else {
                                        $erreur['content'] = true;
                                    }
        
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
                                    header('Location: films.php');
                                    exit();
                                }
                            } else {
                                header('Location: films.php');
                                exit();
                            }
                        }
                    }
                } else {
                    header('Location: films.php');
                    exit();
                }
            } else {
                header('Location: films.php');
                exit();
            }
        } else {
            header('Location: films.php');
            exit();
        }
    ?>
    <div id="content">
        <div id="newComment">
            <h2>Modifier le commentaire</h2>
            <div class="comment">
                <div class="commentContent">
                    <form name="addComment" action="modifierComment.php" id="addCommentForm" method="POST">
                        <textarea name="content" placeholder="Écrivez votre commentaire..."><?= $valeursCommentAvantModif['content'] ?></textarea>
                        <div id="note">
                            <p>Quelle note souhaitez-vous mettre au film ? </p>
                            <select name="note">
                                <option value="0" <?php if($valeursCommentAvantModif['note'] == 0) { echo 'selected'; } ?>>0★</option>
                                <option value="1" <?php if($valeursCommentAvantModif['note'] == 1) { echo 'selected'; } ?>>1★</option>
                                <option value="2" <?php if($valeursCommentAvantModif['note'] == 2) { echo 'selected'; } ?>>2★</option>
                                <option value="3" <?php if($valeursCommentAvantModif['note'] == 3) { echo 'selected'; } ?>>3★</option>
                                <option value="4" <?php if($valeursCommentAvantModif['note'] == 4) { echo 'selected'; } ?>>4★</option>
                                <option value="5" <?php if($valeursCommentAvantModif['note'] == 5) { echo 'selected'; } ?>>5★</option>
                            </select>
                        </div>
                        <input type="hidden" value=<?php echo '"',$id,'"'; ?> name="commentId">
                        <input type="submit" name="modifComment" id="submitBtn" value="Modifier">
                        <?php
                            if($erreur['content']) {
                                echo "<p style='font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;'>Le commentaire est vide, veuillez écrire votre commentaire avant d'envoyer !</p>";
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