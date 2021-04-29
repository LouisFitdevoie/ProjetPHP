<?php session_start() ?>
<html lang='fr'>
<head>
	<title>Supprimer le film [Admin]</title>      
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
        #suppFilmDiv {
            display: block;
            background-color: #FFFFFF10;
            max-width: 900px;
            margin-top: 10px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            border-radius: 15px;
        }
        #suppFilmDiv form {
            margin-bottom: 0px;
            padding: 10px;
        }
        #submitBtn {
            font-size: 0.8em;
            margin-top: 10px;
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
                        $film = $bdd->prepare('SELECT * FROM Films WHERE filmId=:filmId');
                        $film->bindValue(':filmId',$id);
                        $film->execute();
                        $filmFetched = $film->fetch();
                    } else {
                        header('Location: films.php');
                        exit();
                    }
                } elseif(isset($_POST['suppFilm'])) {

                    $erreur['captcha'] = false;

                    if(!empty($_POST['g-recaptcha-response'])) {
                        $secret = '6LepcKUaAAAAAIkB52vCXMgQ5h4wHjqDG-i4d_mU';
                        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
                        $responseData = json_decode($verifyResponse);
                        if($responseData->success){
                            if(isset($_POST['id']) && !empty($_POST['id'])) {
                                if($_POST['confirmation'] == 1) {
                                    $delete = $bdd->prepare('DELETE FROM Films WHERE filmId=:id');
                                    $delete->bindValue(':id',$_POST['id']);
                                    $delete->execute();
                                    header('Location: films.php');
                                    exit();
                                } else {
                                    $header = 'Location: infosFilm.php?id='.$_POST['id'];
                                    header($header);
                                    exit();
                                }
                            } else {
                                header('Location: films.php');
                                exit();
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
    <h1>Suppression du film <?php echo $dataAvantModifFetched['titre']; ?></h1>
    <div id='suppFilmDiv'>
        <form method="POST" action="supprimerFilm.php" enctype='multipart/form-data'>
            <h4>Êtes-vous sûr de vouloir supprimer le film <i><?php echo $filmFetched['titre']; ?></i> ? (Cette action est définitive)</h4>
            
            <?php
                if($erreur['captcha']) {
                    echo "<p style='font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;'>Veuillez valider le Captcha avant d'appuyer sur envoyer !</p>";
                } else {
                    
                }
            ?>
            <input type='radio' name='confirmation' value='1'>Oui
            <input type='radio' name='confirmation' value='0' checked>Non
            <input type='hidden' name='id' value="<?php echo $filmFetched['filmId']; ?>">
            <div class="g-recaptcha" data-sitekey="6LepcKUaAAAAAE6KaSKp1us-gaAIfBJEUeO2J1Zk"></div>
            <input type='submit' name='suppFilm' id='submitBtn' value='Confirmer'>
        </form>
    </div>
    <script src='https://www.google.com/recaptcha/api.js' async defer ></script>
</body>
</html>
