<?php session_start() ?>
<!DOCTYPE html>
<html lang='fr'>
<head>        
    <meta charset='utf-8'>
    <meta name='author' content='Louis Fitdevoie'>
    <title>Ajout de film [Admin]</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="Ressources/style.css">
</head>
<body>
    <?php
        session_start();
        include_once('menu.php');
        include_once('connexionDB.php');
        //Réinitialisation des erreurs
        $erreur['captcha'] = false;
        $erreur['filmExiste'] = false;
        $erreur['bandeAnnonce'] = false;
        $erreur['mauvaisLienStreaming'] = false;
        $erreur['imgUpload'] = false;
        $erreur['formatDate'] = false;
        $erreur['duree'] = false;
        $erreur['resume'] = false;
        $erreur['acteurs'] = false;
        $erreur['titre'] = false;
        $erreur['ordreSortie'] = false;
        //On vérifie si l'utilisateur est connecté
        if(isset($_SESSION['username'])) {
            //On vérifie si l'utilisateur a les droits d'admin
            if($_SESSION['droits'] == 1) {
                //On vérifie si l'utilisateur a bien cliqué sur envoyer
                if(isset($_POST['addFilm'])) {
                    //On vérifie si l'utilisateur a rempli le Captcha
                    if(!empty($_POST['g-recaptcha-response'])) {
                        $secret = '6LepcKUaAAAAAIkB52vCXMgQ5h4wHjqDG-i4d_mU';
                        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
                        $responseData = json_decode($verifyResponse);
                        if($responseData->success) {

                            //On vérifie si l'utilisateur a bien rempli les champs de formulaires nécessaires
                            if(!empty($_POST['titre']) && !empty($_POST['resume']) && !empty($_POST['dateDeSortie']) && !empty($_POST['dureeHeure']) && !empty($_POST['dureeMin']) && !empty($_POST['acteurs']) && !empty($_POST['bandeAnnonce']) && !empty($_POST['ordreSortie']) && !empty($_FILES['imageLink'])) {
                                $plateformesStreaming = ['','Disney+','Netflix'];
                                //On vérifie que le titre ne commence ou ne finisse pas par un ou plusieurs espaces
                                $regexTitre = '/^[^\s]+(\s+[^\s]+)*$/i';
                                if(preg_match($regexTitre,$_POST['titre'])) {
                                    $titre = $_POST['titre'];
                                } else {
                                    $erreur['titre'] = true;
                                }
                                $verifyFilmExiste = $bdd->prepare('SELECT * FROM Films WHERE titre=:titre');
                                $verifyFilmExiste->bindValue(':titre',$titre);
                                $verifyFilmExiste->execute();
                                $filmExiste = $verifyFilmExiste->fetch();
                                //On vérifie que le titre du film entré ne soit pas déjà dans la BDD
                                if($filmExiste) {
                                    $erreur['filmExiste'] = true;
                                }
                                $regexResume = '/^[^\s]+(\s+[^\s]+)*$/i';
                                if(preg_match($regexResume,$_POST['resume'])) {
                                    $resume = $_POST['resume'];
                                } else {
                                    $erreur['resume'] = true;
                                }
                                $dateDeSortie = $_POST['dateDeSortie'];
        
                                ////Vérifier si ce qui est entré est bien une date
                                $moisArray = [' janvier ',' février ',' mars ',' avril ',' mai ',' juin ',' juillet ',' août ',' septembre ',' octobre ',' novembre ',' décembre '];
                                $regexDate = '/^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/i';
                                if(preg_match($regexDate,$dateDeSortie)) {
                                    $date = explode('/',$dateDeSortie);
                                    if($date[0] > 31 || $date[0] < 1) {
                                        $erreur['formatDate'] = true;
                                    } else {
                                        $jour = $date[0];
                                    }
                                    if($date[1] > 12 || $date[1] < 1) {
                                        $erreur['formatDate'] = true;
                                    } else {
                                        $mois = $moisArray[$date[1] - 1];
                                    }
                                    if($date[2] < 2000) {
                                        $erreur['formatDate'] = true;
                                    } else {
                                        $annee = $date[2];
                                    }
                                    $dateDeSortie = $jour . $mois . $annee;
                                } else {
                                    $erreur['formatDate'] = true;
                                }
        
                                $dureeHeure = $_POST['dureeHeure'];
                                $dureeMin = $_POST['dureeMin'];
                                //Vérifier la durée
                                $regexHeure = '/^[0-9]$/i';
                                $regexMin = '/^[0-5][0-9]$/i';
                                if(preg_match($regexHeure,$dureeHeure) && preg_match($regexMin,$dureeMin)) {
                                    $duree = $dureeHeure.'h'.$dureeMin;
                                } else {
                                    $erreur['duree'] = true;
                                }

                                //On vérifie que les acteurs entrés ne commencent ou ne finissent pas par un ou plusieurs espaces
                                $regexActeurs = '/^[^\s]+(\s+[^\s]+)*$/i';
                                if(preg_match($regexActeurs,$_POST['acteurs'])) {
                                    $acteurs = $_POST['acteurs'];
                                } else {
                                    $erreur['acteurs'] = true;
                                }
                                $bandeAnnonce = $_POST['bandeAnnonce'];
        
                                //Vérifier si ce qui est entré est bien un lien youtube
                                $regexYoutubeLong = '/^https:\/\/(www\.)?youtube\.com\/watch\?v=([\w-]+).*$/i';
                                $regexYoutubeCourt = '/^https:\/\/youtu\.be\/([\w-]+)$/i';
                                if(preg_match($regexYoutubeLong,$bandeAnnonce) || preg_match($regexYoutubeCourt,$bandeAnnonce)) {
                                    
                                } else {
                                    $erreur['bandeAnnonce'] = true;
                                }
        
                                $streaming = $plateformesStreaming[$_POST['streaming']];
                                $streamingLink = $_POST['streamingLink'];
        
                                //Vérifier que le lien entré est bien un lien vers la plateforme de streaming sélectionnée
                                if($streaming == 'Disney+') {
                                    $regexDisneyPlus = '/^https:\/\/(www\.)?disneyplus\.com\/.*/i';
                                    if(!preg_match($regexDisneyPlus,$streamingLink)) {
                                        $erreur['mauvaisLienStreaming'] = true;
                                    }
                                } else if($streaming == 'Netflix') {
                                    $regexNetflix = '/^https:\/\/(www\.)?netflix\.com\/.*/i';
                                    if(!preg_match($regexNetflix,$streamingLink)) {
                                        $erreur['mauvaisLienStreaming'] = true;
                                    }
                                } else {
                                    $streamingLink = '';
                                }

                                //Vérifier que le numéro d'ordre de sortie est bien un nombre
                                $regexNb = '/^[0-9]+$/i';
                                if(preg_match($regexNb,$_POST['ordreSortie'])) {
                                    $verifOrdreSortieUtilise = $bdd->prepare('SELECT count(*) FROM Films WHERE ordreSortie=:ordreSortie');
                                    $verifOrdreSortieUtilise->bindValue(':ordreSortie',$_POST['ordreSortie']);
                                    $verifOrdreSortieUtilise->execute();
                                    $ordreSortieUtilise = $verifOrdreSortieUtilise->fetch();
                                    if($ordreSortieUtilise[0] == 0) {
                                        $ordreSortie = $_POST['ordreSortie'];
                                    } else {
                                        $erreur['ordreSortie'] = true;
                                    }
                                } else {
                                    $erreur['ordreSortie'] = true;
                                }
        
                                ////Vérifier le fichier entré pour la photo du film
                                $dossier = 'Ressources/img/films/';
                                $fichier = basename($_FILES['imageLink']['name']);
                                $taille_maxi = 500000;
                                $taille = filesize($_FILES['imageLink']['tmp_name']);
                                $extensions = array('.png', '.jpg', '.jpeg');
                                $extension = strrchr($_FILES['imageLink']['name'], '.');
                                //Si l'extension n'est pas dans le tableau 
                                if(!in_array($extension, $extensions)) {
                                    $erreur['imgUpload'] = true;
                                    echo 'erreur extension';
                                }
                                if($taille>$taille_maxi) {
                                    $erreur['imgUpload'] = true;
                                    echo 'erreur taille';
                                }
                                //S'il n'y a pas d'erreur, on upload
                                if(!$erreur['imgUpload']) {
                                    //Si la fonction renvoie TRUE, c'est que ça a fonctionné... 
                                    if(move_uploaded_file($_FILES['imageLink']['tmp_name'], $dossier.$fichier)) {
                                        $imageLink = $fichier;
                                    }
                                    //Sinon (la fonction renvoie FALSE). 
                                    else {
                                        $erreur['imgUpload'] = true;
                                        echo 'erreur copie';
                                    }
                                } else {
                                    $erreur['imgUpload'] = true;
                                }
        
                                ////Enregistrement dans la BDD
                                if(!$erreur['filmExiste'] && !$erreur['titre'] && !$erreur['resume'] && !$erreur['formatDate'] && !$erreur['duree'] && !$erreur['acteurs'] && !$erreur['bandeAnnonce'] && !$erreur['mauvaisLienStreaming'] && !$erreur['ordreSortie'] && !$erreur['imgUpload']) {
                                    $enregistrementFilm = $bdd->prepare('INSERT INTO Films VALUES (null,:titre,:resumes,:dateDeSortie,:duree,:acteurs,:bandeAnnonce,:streaming,:streamingLink,:imageLink,:ordreSortie)');
                                    $enregistrementFilm->bindValue(':titre',$titre);
                                    $enregistrementFilm->bindValue(':resumes',$resume);
                                    $enregistrementFilm->bindValue(':dateDeSortie',$dateDeSortie);
                                    $enregistrementFilm->bindValue(':duree',$duree);
                                    $enregistrementFilm->bindValue(':acteurs',$acteurs);
                                    $enregistrementFilm->bindValue(':bandeAnnonce',$bandeAnnonce);
                                    $enregistrementFilm->bindValue(':streaming',$streaming);
                                    $enregistrementFilm->bindValue(':streamingLink',$streamingLink);
                                    $enregistrementFilm->bindValue(':imageLink',$imageLink);
                                    $enregistrementFilm->bindValue(':ordreSortie',$ordreSortie);
                                    $enregistrementFilm->execute();
                                    $enregistrementFilm->closeCursor();
                                    echo '<script>window.location.href = "films.php";</script>';
                                    exit();
                                }
                            } else {
                                echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Veuillez compléter tous les champs avant de valider le formulaire !</p>';
                            }
                        }
                    } else {
                        $erreur['captcha'] = true;
                    }
                }

            } else {
                echo '<script>window.location.href = "index.php";</script>';
                exit();
            }
        } else {
            echo '<script>window.location.href = "index.php";</script>';
            exit();
        }
    ?>
    <h1 id="addFilmH1">Ajout de film</h1>
    <div id='addFilmDiv'>
        <form method="POST" action="addFilm.php" enctype='multipart/form-data'>
            <table>
                <tr>
                    <td>Titre : </td>
                    <td><input type='text' name='titre' required <?php if(isset($_POST['addFilm'])) { echo "value='",$_POST['titre'],"'"; } ?>></td>
                </tr>
                <tr>
                    <td>Résumé  : </td>
                    <td><textarea name='resume' required rows=5 ><?php if(isset($_POST['addFilm'])) { echo $_POST['resume']; } ?></textarea></td>
                </tr>
                <tr>
                    <td>Date de sortie  : </td>
                    <td><input type='text' name='dateDeSortie' required placeholder='JJ/MM/AAAA' <?php if(isset($_POST['addFilm'])) { echo "value='",$_POST['dateDeSortie'],"'"; } ?>></td>
                </tr>
                <tr>
                    <td>Durée  : </td>
                    <td id='duree'><div id='dureeDiv'><input type='text' name='dureeHeure' required <?php if(isset($_POST['addFilm'])) { echo "value='",$_POST['dureeHeure'],"'"; } ?>>h<input type='text' name='dureeMin' required <?php if(isset($_POST['addFilm'])) { echo "value='",$_POST['dureeMin'],"'"; } ?>></div></td>
                </tr>
                <tr>
                    <td>Acteurs principaux  : </td>
                    <td><input type='text' name='acteurs' required <?php if(isset($_POST['addFilm'])) { echo "value='",$_POST['acteurs'],"'"; } ?>></td>
                </tr>
                <tr>
                    <td>Lien vers la bande annonce sur Youtube  : </td>
                    <td><input type='text' name='bandeAnnonce' required placeholder='https://www.youtube.com/watch?v=rDCTb9Gp2qk' <?php if(isset($_POST['addFilm'])) { echo "value='",$_POST['bandeAnnonce'],"'"; } ?>></td>
                </tr>
                <tr>
                    <td>Sur quelle plateforme de streaming le film est-il disponible ?  : </td>
                    <td id='streamingSelect'>
                        <select name='streaming'>
                            <option value='0' <?php if(isset($_POST['addFilm'])) { if($_POST['streaming'] == 0) { echo 'selected';} } else { echo 'selected'; } ?>>Aucune</option>
                            <option value='1' <?php if(isset($_POST['addFilm'])) { if($_POST['streaming'] == 1) { echo 'selected';} } ?>>Disney+</option>
                            <option value='2' <?php if(isset($_POST['addFilm'])) { if($_POST['streaming'] == 2) { echo 'selected';} } ?>>Netflix</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Lien de streaming vers le film  : </td>
                    <td><input type='text' name='streamingLink' placeholder='https://www.disneyplus.com/fr-fr/movies/marvel-studios-iron-man/6aM2a8mZATiu' <?php if(isset($_POST['addFilm'])) { echo "value='",$_POST['streamingLink'],"'"; } ?>></td>
                </tr>
                <tr>
                    <td>Numéro d'ordre de sortie du film  : </td>
                    <td id='ordreSortie'>
                        <select name='ordreSortie'>
                            <?php
                                $getOrdreSortie = $bdd->query('SELECT ordreSortie FROM Films');
                                $arrayOrder = array();
                                while($ordreSortie = $getOrdreSortie->fetch()) {
                                    array_push($arrayOrder,$ordreSortie[0]);
                                }
                                sort($arrayOrder);
                                for($i = 1 ; $i <= end($arrayOrder) + 5 ; $i++) {
                                    if(!in_array($i , $arrayOrder)) {
                                        echo '<option value="',$i,'"';
                                        if(isset($_POST['ordreSortie'])) {
                                            if($_POST['ordreSortie'] == $i) {
                                                echo ' selected';
                                            }
                                        }
                                        echo '>',$i,'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Affiche du film (.jpeg, .jpg ou .png)  : </td>
                    <td><input type='hidden' name='MAX_FILE_SIZE' value='500000'><input type='file' name='imageLink' required></td>
                </tr>
                <tr>
                    <td colspan=2><div class="g-recaptcha" data-sitekey="6LepcKUaAAAAAE6KaSKp1us-gaAIfBJEUeO2J1Zk"></div></td>
                </tr>
            </table>
            <?php
                if($erreur['captcha']) {
                    echo "<p style='font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;'>Veuillez valider le Captcha avant d'appuyer sur envoyer !</p>";
                } else {
                    if($erreur['filmExiste']) {
                        echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Un film avec le titre entré existe déjà dans la base de données, réessayez !</p>';
                    } else {
                        if($erreur['titre']) {
                            echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Le titre ne doit pas commencer ni terminer par un ou plusieurs espaces !</p>';
                        } else {
                            if($erreur['resume']) {
                                echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Le résumé ne doit pas commencer ni terminer par un ou plusieurs espaces !</p>';
                            } else {
                                if($erreur['formatDate']) {
                                    echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">La date entrée n\'a pas le bon format, réessayez !</p>';
                                } else {
                                    if($erreur['duree']) {
                                        echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">La durée du film n\a pas le bon format, réessayez !</p>';
                                    } else {
                                        if($erreur['acteurs']) {
                                            echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Les acteurs ne doivent pas commencer ou terminer par un ou plusieurs espaces !</p>';
                                        } else {
                                            if($erreur['bandeAnnonce']) {
                                                echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Le lien Youtube est invalide, réessayez !</p>';
                                            } else {
                                                if($erreur['mauvaisLienStreaming']) {
                                                    echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Le lien de streaming ne mène pas vers la plateforme de streaming sélectionnée, réessayez !</p>';
                                                } else {
                                                    if($erreur['ordreSortie']) {
                                                        echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Le numéro d\'ordre de sortie n\'est pas un nombre correct, réessayez !</p>';
                                                    } else {
                                                        if($erreur['imgUpload']) {
                                                            echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">L\'image n\'a pas pu être uploadée, réessayez !</p>';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            ?>
            <input type='submit' name='addFilm' id='submitBtn' value="Ajouter le film">
        </form>
    </div>
    <script src='https://www.google.com/recaptcha/api.js' async defer ></script>
</body>
</html>