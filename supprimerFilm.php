<?php session_start() ?>
<!DOCTYPE html>
<html lang='fr'>
<head>
	<title>Supprimer le film [Admin]</title>      
    <meta charset='utf-8'>
    <meta name='author' content='Louis Fitdevoie'>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="Ressources/style.css">
</head>
<body>
    <?php
        include_once('menu.php');
        include_once("connexionDB.php");

        $id = 0;
        //On vérifie que l'utilisateur est connecté
        if(isset($_SESSION['username'])) {
            //On vérifie que l'utilisateur ait les droits d'administration
            if($_SESSION['droits'] == 1) {
                //On vérifie que l'ID de film soit set
                if(isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $regexNb = '/^[0-9]+$/i';
                    //On vérifie que l'ID soit bien un nombre
                    if(preg_match($regexNb,$id)) {
                        //On récupère les données associées à l'ID du film
                        $film = $bdd->prepare('SELECT * FROM Films WHERE filmId=:filmId');
                        $film->bindValue(':filmId',$id);
                        $film->execute();
                        $filmFetched = $film->fetch();
                    } else {
                        echo '<script>window.location.href = "films.php";</script>';
                        exit();
                    }
                //On vérifie si l'utilisateur à cliqué sur le bouton supprimer
                } elseif(isset($_POST['suppFilm'])) {

                    $erreur['captcha'] = false;
                    //On vérifie que l'utilisateur ait bien complété le Captcha
                    if(!empty($_POST['g-recaptcha-response'])) {
                        $secret = '6LepcKUaAAAAAIkB52vCXMgQ5h4wHjqDG-i4d_mU';
                        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
                        $responseData = json_decode($verifyResponse);
                        if($responseData->success){
                            //On vérifie que l'ID de film soit set
                            if(isset($_POST['id']) && !empty($_POST['id'])) {
                                //On vérifie que l'utilisateur a confirmé qu'il voulait supprimer le film
                                if($_POST['confirmation'] == 1) {
                                    //Suppression du film et redirection vers la liste des films
                                    $delete = $bdd->prepare('DELETE FROM Films WHERE filmId=:id');
                                    $delete->bindValue(':id',$_POST['id']);
                                    $delete->execute();
                                    echo '<script>window.location.href = "films.php";</script>';
                                    exit();
                                } else {
                                    $location = 'Location: infosFilm.php?id='.$_POST['id'];
                                    echo '<script>window.location.href = "',$location,'";</script>';
                                    exit();
                                }
                            } else {
                                echo '<script>window.location.href = "films.php";</script>';
                                exit();
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
    <h1 id="suppFilmH1">Suppression du film <?php echo $dataAvantModifFetched['titre']; ?></h1>
    <div id='suppFilmDiv'>
        <form method="POST" action="supprimerFilm.php" enctype='multipart/form-data'>
            <h4>Êtes-vous sûr de vouloir supprimer le film <i><?php echo $filmFetched['titre']; ?></i> ? (Cette action est définitive)</h4>
            
            <?php
                if($erreur['captcha']) {
                    echo "<p style='font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;'>Veuillez valider le Captcha avant d'appuyer sur envoyer !</p>";
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