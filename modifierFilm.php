<?php session_start() ?>
<html lang='fr'>
<head>
	<title>Modification de film [Admin]</title>      
    <meta charset='utf-8'>
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
        h1 {
            text-align: center;
            margin-bottom: 0px;
        }
        #modifFilmDiv {
            display: block;
            background-color: #FFFFFF10;
            max-width: 900px;
            margin-top: 25px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 150px;
            text-align: center;
            border-radius: 15px;
        }
        #modifFilmDiv form {
            margin-bottom: 0px;
        }
        #modifFilmDiv table {
            margin-top: 10px;
            margin-left: auto;
            margin-right: auto;
            padding: 10px;
            text-align: right;
        }
        #modifFilmDiv #submitBtn {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        table input {
            width: 100%;
        }
        #duree input {
            width: 47.15%;
        }
        #duree input:first-child {
            margin-right: 5px;
        }
        #duree input:last-child {
            margin-left: 5px;
        }
        textarea {
            resize: none;
            width: 100%;
            font-family: Calibri, Arial, sans-serif;
            font-size: 0.8em;
        }
        table input, #streamingSelect {
            text-align: center;
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
        .g-recaptcha > div {
            height: 20px;
            margin-left: auto;
            margin-right: auto;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php
        session_start();
        include_once('menu.php');
        include_once("connexionDB.php");

        $id = 0;

        if(isset($_SESSION['username'])) {
            if($_SESSION['droits'] == 1) {
                if(isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $regexNb = '/^[0-9]+$/i';
                    if(preg_match($regexNb,$id)) {
                        $dataAvantModif = $bdd->prepare('SELECT * FROM Films WHERE filmId=:filmId');
                        $dataAvantModif->bindValue(':filmId',$id);
                        $dataAvantModif->execute();
                        $dataAvantModifFetched = $dataAvantModif->fetch();
                    } else {
                        header('Location: films.php');
                        exit();
                    }
                } elseif(isset($_POST['modifFilm'])) {

                    $erreur['captcha'] = false;
                    $erreur['champsVides'] = false;
                    $erreur['formatDate'] = false;
                    $erreur['duree'] = false;
                    $erreur['bandeAnnonce'] = false;
                    $erreur['streaming'] = false;
                    $erreur['mauvaisLienStreaming'] = false;


                    if(!empty($_POST['g-recaptcha-response'])) {
                        $secret = '6LepcKUaAAAAAIkB52vCXMgQ5h4wHjqDG-i4d_mU';
                        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
                        $responseData = json_decode($verifyResponse);
                        if($responseData->success){
                            if(!empty($_POST['titre']) && !empty($_POST['resume']) && !empty($_POST['dateDeSortie']) && !empty($_POST['dureeHeure']) && !empty($_POST['dureeMin']) && !empty($_POST['acteurs']) && !empty($_POST['bandeAnnonce']) && !empty($_POST['id'])) {
                                $id = $_POST['id'];
                                $regexNb = '/^[0-9]+$/i';
                                if(preg_match($regexNb,$id)) {
                                    $dataAvantModif = $bdd->prepare('SELECT * FROM Films WHERE filmId=:filmId');
                                    $dataAvantModif->bindValue(':filmId',$id);
                                    $dataAvantModif->execute();
                                    $dataAvantModifFetched = $dataAvantModif->fetch();
                                }

                                $nouveauTitre = $_POST['titre'];
                                $nouveauResume = $_POST['resume'];

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

                                $nouveauxActeurs = $_POST['acteurs'];
                                $nouvelleBandeAnnonce = $_POST['bandeAnnonce'];

                                $regexYoutubeLong = '/^https:\/\/(www\.)?youtube\.com\/watch\?v=([\w-]+).*$/i';
                                $regexYoutubeCourt = '/^https:\/\/youtu\.be\/([\w-]+)$/i';
                                if(preg_match($regexYoutubeLong,$nouvelleBandeAnnonce) || preg_match($regexYoutubeCourt,$nouvelleBandeAnnonce)) {
                                    
                                } else {
                                    $erreur['bandeAnnonce'] = true;
                                    //echo 'erreur bande annonce';
                                }
                                
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

                                if(!$erreur['champsVides'] && !$erreur['formatDate'] && !$erreur['duree'] && !$erreur['bandeAnnonce'] && !$erreur['streaming'] && !$erreur['mauvaisLienStreaming']) {
                                    $miseAJourFilm = $bdd->prepare('UPDATE Films SET titre=:titre,resume=:resume,dateDeSortie=:dateDeSortie,duree=:duree,acteurs=:acteurs,bandeAnnonce=:bandeAnnonce,streaming=:streaming,streamingLink=:streamingLink WHERE filmId=:id');
                                    $miseAJourFilm->bindValue(':titre',$nouveauTitre);
                                    $miseAJourFilm->bindValue(':resume',$nouveauResume);
                                    $miseAJourFilm->bindValue(':dateDeSortie',$nouvelleDateDeSortie);
                                    $miseAJourFilm->bindValue(':duree',$nouvelleDuree);
                                    $miseAJourFilm->bindValue(':acteurs',$nouveauxActeurs);
                                    $miseAJourFilm->bindValue(':bandeAnnonce',$nouvelleBandeAnnonce);
                                    $miseAJourFilm->bindValue(':streaming',$streaming);
                                    $miseAJourFilm->bindValue(':streamingLink',$streamingLink);
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
    <h1>Modification du film <?php echo $dataAvantModifFetched['titre']; ?></h1>
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
                    <td id='duree'><input type='text' name='dureeHeure' value="<?php if(isset($_GET['id'])) { echo $dureeHeureAvantModif; } else { echo $_POST['dureeHeure']; } ?>">h<input type='text' name='dureeMin' value="<?php if(isset($_GET['id'])) { echo $dureeMinAvantModif; } else { echo $_POST['dureeMin']; } ?>"></td>
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
                    <td colspan=2><div class="g-recaptcha" data-sitekey="6LepcKUaAAAAAE6KaSKp1us-gaAIfBJEUeO2J1Zk"></div></td>
                </tr>
            </table>
            <?php
                if($erreur['captcha']) {
                    echo "<p style='font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;'>Veuillez valider le Captcha avant d'appuyer sur envoyer !</p>";
                } else {
                    if($erreur['champsVides']) {
                        echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Un ou plusieurs des champs sont vide(s), réessayez !</p>';
                    } else {
                        if($erreur['formatDate']) {
                            echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Le format de la date de sortie est incorrect, réessayez !</p>';
                        } else {
                            if($erreur['duree']) {
                                echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Le format de la durée est incorrect, réessayez !</p>';
                            } else {
                                if($erreur['bandeAnnonce']) {
                                    echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Le lien vers la bande annonce n\'est pas un lien Youtube, réessayez !</p>';
                                } else {
                                    if($erreur['streaming']) {
                                        echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">La plateforme de streaming choisie ne fait pas partie des choix, réessayez !</p>';
                                    } else {
                                        if($erreur['mauvaisLienStreaming']) {
                                            echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Le lien de streaming entré ne mène pas vers la plateforme de streaming choisie, réessayez !</p>';
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
