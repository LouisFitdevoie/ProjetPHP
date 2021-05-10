<?php session_start() ?>
<!DOCTYPE html>
<html lang='fr'>
<head>
	<title>Modification de film [Admin]</title>      
    <meta charset='utf-8'>
    <meta name='author' content='Louis Fitdevoie'>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="Ressources/style.css">
</head>
<body>
    <?php
        session_start();
        include_once('menu.php');
        include_once("connexionDB.php");

        $id = 0;
        //On vérifie que l'utilisateur soit connecté et qu'il a les droits d'administration
        if(isset($_SESSION['username'])) {
            if($_SESSION['droits'] == 1) {
                //On vérifie que l'ID du film est bien passé dans la requête
                if(isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $regexNb = '/^[0-9]+$/i';
                    //On vérifie que l'ID soit bien un nombre et qu'un film correspondant à cet ID existe bien dans la BDD
                    if(preg_match($regexNb,$id)) {
                        $verifFilmExist = $bdd->prepare('SELECT count(*) FROM Films WHERE filmId=:filmId');
                        $verifFilmExist->bindValue(':filmId',$id);
                        $verifFilmExist->execute();
                        $filmExist = $verifFilmExist->fetch();

                        if($filmExist[0] == 1) {
                            //Si le film existe -> on récupère les données du film pour les afficher
                            $dataAvantModif = $bdd->prepare('SELECT * FROM Films WHERE filmId=:filmId');
                            $dataAvantModif->bindValue(':filmId',$id);
                            $dataAvantModif->execute();
                            $dataAvantModifFetched = $dataAvantModif->fetch();
                        } else {
                            echo '<script>window.location.href = "films.php";</script>';
                            exit();
                        }

                    } else {
                        echo '<script>window.location.href = "films.php";</script>';
                        exit();
                    }
                //Si l'utilisateur à cliqué sur le bouton pour modifier le film
                } elseif(isset($_POST['modifFilm'])) {

                    $erreur['captcha'] = false;
                    $erreur['filmExiste'] = false;
                    $erreur['bandeAnnonce'] = false;
                    $erreur['mauvaisLienStreaming'] = false;
                    $erreur['formatDate'] = false;
                    $erreur['duree'] = false;
                    $erreur['resume'] = false;
                    $erreur['acteurs'] = false;
                    $erreur['titre'] = false;
                    $erreur['ordreSortie'] = false;

                    //On vérifie qu'il ait bien rempli le Captcha
                    if(!empty($_POST['g-recaptcha-response'])) {
                        $secret = '6LepcKUaAAAAAIkB52vCXMgQ5h4wHjqDG-i4d_mU';
                        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
                        $responseData = json_decode($verifyResponse);
                        if($responseData->success){
                            //On vérifie que tous les champs ont été complétés
                            if(!empty($_POST['titre']) && !empty($_POST['resume']) && !empty($_POST['dateDeSortie']) && !empty($_POST['dureeHeure']) && !empty($_POST['dureeMin']) && !empty($_POST['acteurs']) && !empty($_POST['bandeAnnonce']) && !empty($_POST['id'])) {
                                $id = $_POST['id'];
                                $regexNb = '/^[0-9]+$/i';
                                //On vérifie que l'ID du film soit bien un nombre et que le film existe dans la BDD
                                if(preg_match($regexNb,$id)) {
                                    $dataAvantModif = $bdd->prepare('SELECT * FROM Films WHERE filmId=:filmId');
                                    $dataAvantModif->bindValue(':filmId',$id);
                                    $dataAvantModif->execute();
                                    $dataAvantModifFetched = $dataAvantModif->fetch();
                                }
                                //On vérifie que le titre ne commence ou ne finisse pas par un ou plusieurs espaces
                                $regexTitre = '/^[^\s]+(\s+[^\s]+)*$/i';
                                if(preg_match($regexTitre,$_POST['titre'])) {
                                    $nouveauTitre = $_POST['titre'];
                                } else {
                                    $erreur['titre'] = true;
                                }
                                //On vérifie que le résumé ne commence ou ne finisse pas par un ou plusieurs espaces
                                $regexResume = '/^[^\s]+(\s+[^\s]+)*$/i';
                                if(preg_match($regexResume,$_POST['resume'])) {
                                    $nouveauResume = $_POST['resume'];
                                } else {
                                    $erreur['resume'] = true;
                                }
                                //On met en forme la date de sortie du film
                                $nouvelleDateDeSortie = $_POST['dateDeSortie'];
                                $moisArray = [' janvier ',' février ',' mars ',' avril ',' mai ',' juin ',' juillet ',' août ',' septembre ',' octobre ',' novembre ',' décembre '];
                                $regexDate = '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/i';
                                if(preg_match($regexDate,$nouvelleDateDeSortie)) {
                                    $date = explode('/',$nouvelleDateDeSortie);
                                    if($date[0] > 31 || $date[0] < 1) {
                                        $erreur['formatDate'] = true;
                                        //echo 'erreur jour';
                                    } else {
                                        $jour = $date[0];
                                    }
                                    if($date[1] > 12 || $date[1] < 1) {
                                        $erreur['formatDate'] = true;
                                        //echo 'erreur mois';
                                    } else {
                                        $mois = $moisArray[$date[1] - 1];
                                    }
                                    if($date[2] < 2000) {
                                        $erreur['formatDate'] = true;
                                        //echo 'erreur annee';
                                    } else {
                                        $annee = $date[2];
                                    }
                                    $nouvelleDateDeSortie = $jour . $mois . $annee;
                                } else {
                                    $erreur['formatDate'] = true;
                                    //echo 'erreur date';
                                }
                                //On vérifie que la durée soit au bon format
                                $nouvelleDureeHeure = $_POST['dureeHeure'];
                                $nouvelleDureeMin = $_POST['dureeMin'];
                                
                                $regexHeure = '/^[0-9]$/i';
                                $regexMin = '/^[0-5][0-9]$/i';
                                if(preg_match($regexHeure,$nouvelleDureeHeure) && preg_match($regexMin,$nouvelleDureeMin)) {
                                    $nouvelleDuree = $nouvelleDureeHeure.'h'.$nouvelleDureeMin;
                                } else {
                                    $erreur['duree'] = true;
                                    //echo 'erreur duree';
                                }
                                //On vérifie que le champ "acteurs" ne commence ou ne finisse pas par un ou plusieurs espaces
                                $regexActeurs = '/^[^\s]+(\s+[^\s]+)*$/i';
                                if(preg_match($regexActeurs,$_POST['acteurs'])) {
                                    $nouveauxActeurs = $_POST['acteurs'];
                                } else {
                                    $erreur['acteurs'] = true;
                                }
                                $nouvelleBandeAnnonce = $_POST['bandeAnnonce'];
                                //On vérifie que la bande annonce soit au bon format
                                $regexYoutubeLong = '/^https:\/\/(www\.)?youtube\.com\/watch\?v=([\w-]+).*$/i';
                                $regexYoutubeCourt = '/^https:\/\/youtu\.be\/([\w-]+)$/i';
                                if(preg_match($regexYoutubeLong,$nouvelleBandeAnnonce) || preg_match($regexYoutubeCourt,$nouvelleBandeAnnonce)) {
                                    
                                } else {
                                    $erreur['bandeAnnonce'] = true;
                                    //echo 'erreur bande annonce';
                                }
                                //On vérifie que le lien de streaming corresponde bien à la plateforme de streaming choisie
                                if($_POST['streaming'] == 0) {
                                    $streaming = '';
                                    $streamingLink = '';
                                } elseif($_POST['streaming'] == 1) {
                                    $streaming = 'Disney+';
                                    $regexDisneyPlus = '/^https:\/\/(www\.)?disneyplus\.com\/.*/i';
                                    if(!preg_match($regexDisneyPlus,$_POST['streamingLink'])) {
                                        $erreur['mauvaisLienStreaming'] = true;
                                        //echo 'erreur disney+';
                                    } else {
                                        $streamingLink = $_POST['streamingLink'];
                                    }
                                } elseif($_POST['streaming'] == 2) {
                                    $streaming = 'Netflix';
                                    $regexNetflix = '/^https:\/\/(www\.)?netflix\.com\/.*/i';
                                    if(!preg_match($regexNetflix,$_POST['streamingLink'])) {
                                        $erreur['mauvaisLienStreaming'] = true;
                                        //echo 'erreur netflix';
                                    } else {
                                        $streamingLink = $_POST['streamingLink'];
                                    }
                                } else {
                                    $erreur['streaming'] = true;
                                    //echo 'erreur streaming';
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
                                //S'il n'y a pas d'erreur -> enregistrement dans la BDD et redirection vers le bon film
                                if(!$erreur['champsVides'] && !$erreur['titre'] && !$erreur['resume'] && !$erreur['acteurs'] && !$erreur['formatDate'] && !$erreur['duree'] && !$erreur['bandeAnnonce'] && !$erreur['streaming'] && !$erreur['mauvaisLienStreaming'] && !$erreur['ordreSortie']) {
                                    $miseAJourFilm = $bdd->prepare('UPDATE Films SET titre=:titre,resume=:resume,dateDeSortie=:dateDeSortie,duree=:duree,acteurs=:acteurs,bandeAnnonce=:bandeAnnonce,streaming=:streaming,streamingLink=:streamingLink,ordreSortie=:ordreSortie WHERE filmId=:id');
                                    $miseAJourFilm->bindValue(':titre',$nouveauTitre);
                                    $miseAJourFilm->bindValue(':resume',$nouveauResume);
                                    $miseAJourFilm->bindValue(':dateDeSortie',$nouvelleDateDeSortie);
                                    $miseAJourFilm->bindValue(':duree',$nouvelleDuree);
                                    $miseAJourFilm->bindValue(':acteurs',$nouveauxActeurs);
                                    $miseAJourFilm->bindValue(':bandeAnnonce',$nouvelleBandeAnnonce);
                                    $miseAJourFilm->bindValue(':streaming',$streaming);
                                    $miseAJourFilm->bindValue(':streamingLink',$streamingLink);
                                    $miseAJourFilm->bindValue(':ordreSortie',$ordreSortie);
                                    $miseAJourFilm->bindValue(':id',$id);
                                    $miseAJourFilm->execute();
                                    
                                    echo '<script>window.location.href = "infosFilm.php?id=',$id,'";</script>';
                                    exit();
                                }

                            } else {
                                $erreur['champsVides'] = true;
                                //echo 'erreur champs vides';
                            }
                        } else {
                            $erreur['captcha'] = true;
                        }
                    } else {
                        $erreur['captcha'] = true;
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
    <h1 id="modifFilmH1">Modification du film <?php echo $dataAvantModifFetched['titre']; ?></h1>
    <div id='modifFilmDiv'>
        <form method="POST" action="modifierFilm.php" enctype='multipart/form-data'>
            <table>
                <tr>
                    <td>Titre : </td>
                    <td><input type='text' name='titre' value="<?php if(isset($_GET['id'])) { echo $dataAvantModifFetched['titre']; } else { echo $_POST['titre']; } ?>"></td>
                </tr>
                <tr>
                    <td>Résumé : </td>
                    <td><textarea name='resume' rows=5><?php if(isset($_GET['id'])) { echo $dataAvantModifFetched['resume']; } else { echo $_POST['resume']; } ?></textarea></td>
                </tr>
                <tr>
                    <td>Date de sortie : </td>
                    <td><input type='text' name='dateDeSortie' value="<?php 
                        if(isset($_GET['id'])) {
                            $dateAvantModif = explode(' ',$dataAvantModifFetched['dateDeSortie']); 
                            $jourAvantModif = $dateAvantModif[0];
                            $listeMois = ['janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
                            if($dateAvantModif[1] == $listeMois[0]) {
                                $moisAvantModif = '01';
                            } elseif($dateAvantModif[1] == $listeMois[1]) {
                                $moisAvantModif = '02';
                            } elseif($dateAvantModif[1] == $listeMois[2]) {
                                $moisAvantModif = '03';
                            } elseif($dateAvantModif[1] == $listeMois[3]) {
                                $moisAvantModif = '04';
                            } elseif($dateAvantModif[1] == $listeMois[4]) {
                                $moisAvantModif = '05';
                            } elseif($dateAvantModif[1] == $listeMois[5]) {
                                $moisAvantModif = '06';
                            } elseif($dateAvantModif[1] == $listeMois[6]) {
                                $moisAvantModif = '07';
                            } elseif($dateAvantModif[1] == $listeMois[7]) {
                                $moisAvantModif = '08';
                            } elseif($dateAvantModif[1] == $listeMois[8]) {
                                $moisAvantModif = '09';
                            } elseif($dateAvantModif[1] == $listeMois[9]) {
                                $moisAvantModif = '10';
                            } elseif($dateAvantModif[1] == $listeMois[10]) {
                                $moisAvantModif = '11';
                            } elseif($dateAvantModif[1] == $listeMois[11]) {
                                $moisAvantModif = '12';
                            }
                            $anneeAvantModif = $dateAvantModif[2];
                            $date = strval($jourAvantModif).'/'.strval($moisAvantModif).'/'.strval($anneeAvantModif);
                            $dateFormatee = str_replace(' ','',$date);
                            echo $dateFormatee;
                        } else { 
                            echo $_POST['dateDeSortie']; 
                        } 
                    ?>"></td>
                </tr>
                <tr>
                    <td>Durée : </td>
                    <?php
                        $dureeExploded = explode('h',$dataAvantModifFetched['duree']);
                        $dureeHeureAvantModif = $dureeExploded[0];
                        $dureeMinAvantModif = $dureeExploded[1];
                    ?>
                    <td id='duree'><div id='dureeDiv'><input type='text' name='dureeHeure' value="<?php if(isset($_GET['id'])) { echo $dureeHeureAvantModif; } else { echo $_POST['dureeHeure']; } ?>">h<input type='text' name='dureeMin' value="<?php if(isset($_GET['id'])) { echo $dureeMinAvantModif; } else { echo $_POST['dureeMin']; } ?>"></div></td>
                </tr>
                <tr>
                    <td>Acteurs principaux : </td>
                    <td><input type='text' name='acteurs' value="<?php if(isset($_GET['id'])) { echo $dataAvantModifFetched['acteurs']; } else { echo $_POST['acteurs']; } ?>"></td>
                </tr>
                <tr>
                    <td>Lien vers la bande annonce sur Youtube : </td>
                    <td><input type='text' name='bandeAnnonce' value="<?php if(isset($_GET['id'])) { echo $dataAvantModifFetched['bandeAnnonce']; } else { echo $_POST['bandeAnnonce']; } ?>"></td>
                </tr>
                <tr>
                    <td>Sur quelle plateforme de streaming le film est-il disponible ? : </td>
                    <td id='streamingSelect'>
                        <select name='streaming'>
                            <option value='0' <?php if($dataAvantModifFetched['streaming'] == '') { echo 'selected'; } ?>>Aucune</option>
                            <option value='1' <?php if($dataAvantModifFetched['streaming'] == 'Disney+') { echo 'selected'; } ?>>Disney+</option>
                            <option value='2' <?php if($dataAvantModifFetched['streaming'] == 'Netflix') { echo 'selected'; } ?>>Netflix</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Lien de streaming vers le film : </td>
                    <td><input type='text' name='streamingLink' value="<?php if(isset($_GET['id'])) { echo $dataAvantModifFetched['streamingLink']; } else { echo $_POST['streamingLink']; } ?>"></td>
                </tr>
                <tr>
                    <td>Numéro d'ordre de sortie du film : </td>
                    <td id='ordreSortie'>
                        <select name='ordreSortie'>
                            <?php
                                $getOrdreSortie = $bdd->query('SELECT ordreSortie FROM Films');
                                $arrayOrder = array();
                                while($ordreSortie = $getOrdreSortie->fetch()) {
                                    array_push($arrayOrder,$ordreSortie[0]);
                                }
                                sort($arrayOrder);
                                for($i = 1 ; $i <= end($arrayOrder) + 4 ; $i++) {
                                    if(!in_array($i , $arrayOrder)) {
                                        echo '<option value="',$i,'"';
                                        if(isset($_POST['ordreSortie'])) {
                                            if($_POST['ordreSortie'] == $i) {
                                                echo ' selected';
                                            }
                                        }
                                        echo '>',$i,'</option>';
                                    } elseif($i == $dataAvantModifFetched['ordreSortie']) {
                                        echo '<option value="',$dataAvantModifFetched['ordreSortie'],'"';
                                        if(isset($_POST['ordreSortie'])) {
                                            if($_POST['ordreSortie'] == $dataAvantModifFetched['ordreSortie']) {
                                                echo ' selected';
                                            }
                                        } else {
                                            echo ' selected';
                                        }
                                        echo '>',$dataAvantModifFetched['ordreSortie'],'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </td>
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
            <input type='hidden' name='id' value="<?php echo $dataAvantModifFetched['filmId']; ?>">
            <input type='submit' name='modifFilm' id='submitBtn' value='Enregistrer'>
        </form>
    </div>
    <script src='https://www.google.com/recaptcha/api.js' async defer ></script>
</body>
</html>