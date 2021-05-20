<?php session_start() ?>
<!DOCTYPE html>
<html lang='fr'>
<head>
	<title>Suppression de profil [Admin]</title>      
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
        //On vérifie que l'utilisateur est connecté et qu'il a les droits d'administrateur
        if(isset($_SESSION['username'])) {
            if($_SESSION['droits'] == 1) {
                //On vérifie que l'ID d'utilisateur soit bien set
                if(isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $regexNb = '/^[0-9]+$/i';
                    //On vérifie que l'ID soit bien un nombre
                    if(preg_match($regexNb,$id)) {
                        //On récupère les données de l'utilisateur
                        $user = $bdd->prepare('SELECT * FROM Users WHERE userId=:userId');
                        $user->bindValue(':userId',$id);
                        $user->execute();
                        $userFetched = $user->fetch();
                    } else {
                        echo '<script>window.location.href = "admin.php";</script>';
                        exit();
                    }
                //Si l'utilisateur a cliqué sur le bouton pour supprimer le compte utilisateur
                } elseif(isset($_POST['suppUser'])) {

                    $erreur['captcha'] = false;
                    //On vérifie que l'utilisateur a complété le Captcha
                    if(!empty($_POST['g-recaptcha-response'])) {
                        $secret = '6LepcKUaAAAAAIkB52vCXMgQ5h4wHjqDG-i4d_mU';
                        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
                        $responseData = json_decode($verifyResponse);
                        if($responseData->success){
                            //On vérifie que l'ID d'utilisateur est bien set
                            if(isset($_POST['id']) && !empty($_POST['id'])) {
                                //On vérifie que l'utilisateur a confirmé qu'il voulait supprimer le compte utilisateur
                                if($_POST['confirmation'] == 1) {
                                    $delete = $bdd->prepare('DELETE FROM Users WHERE userId=:id');
                                    $delete->bindValue(':id',$_POST['id']);
                                    $delete->execute();
                                    echo '<script>window.location.href = "admin.php";</script>';
                                    exit();
                                } else {
                                    echo '<script>window.location.href = "admin.php";</script>';
                                    exit();
                                }
                            } else {
                                echo '<script>window.location.href = "admin.php";</script>';
                                exit();
                            }
                        } else {
                            $erreur['captcha'] = true;
                        }
                    } else {
                        $erreur['captcha'] = true;
                    }
                } else {
                    echo '<script>window.location.href = "admin.php";</script>';
                    exit();
                }
            //Si l'utilisateur n'a pas les droits d'administration
            } elseif($_SESSION['droits'] == 0) {
                //On vérifie que l'ID du compte utilisateur soit bien passé dans la requête GET
                if(isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $regexNb = '/^[0-9]+$/i';
                    //On vérifie que l'ID de compte soit bien un nombre et qu'un utilisateur lui est bien associé
                    if(preg_match($regexNb,$id)) {
                        $user = $bdd->prepare('SELECT * FROM Users WHERE userId=:userId');
                        $user->bindValue(':userId',$id);
                        $user->execute();
                        $userFetched = $user->fetch();
                        //On vérifie que l'utilisateur qui veut supprimer le profil soit bien le bon utilisateur
                        if($userFetched['username'] != $_SESSION['username']) {
                            echo '<script>window.location.href = "profil.php";</script>';
                            exit();
                        }
                    } else {
                        echo '<script>window.location.href = "profil.php";</script>';
                        exit();
                    }
                //On vérifie si l'utilisateur a cliqué sur le bouton pour supprimer l'utilisateur
                } elseif(isset($_POST['suppUser'])) {

                    $erreur['captcha'] = false;
                    //On vérifie que l'utilisateur a bien complété le Captcha
                    if(!empty($_POST['g-recaptcha-response'])) {
                        $secret = '6LepcKUaAAAAAIkB52vCXMgQ5h4wHjqDG-i4d_mU';
                        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
                        $responseData = json_decode($verifyResponse);
                        if($responseData->success){
                            if(isset($_POST['id']) && !empty($_POST['id'])) {
                                //On vérifie que l'utilisateur a confirmé qu'il voulait supprimer le compte
                                if($_POST['confirmation'] == 1) {
                                    //Suppression du compte utilisateur
                                    $delete = $bdd->prepare('DELETE FROM Users WHERE userId=:id');
                                    $delete->bindValue(':id',$_POST['id']);
                                    $delete->execute();
                                    session_destroy();
                                    echo '<script>window.location.href = "index.php";</script>';
                                    exit();
                                } else {
                                    echo '<script>window.location.href = "profil.php";</script>';
                                    exit();
                                }
                            } else {
                                echo '<script>window.location.href = "profil.php";</script>';
                                exit();
                            }
                        } else {
                            $erreur['captcha'] = true;
                        }
                    } else {
                        $erreur['captcha'] = true;
                    }
                } else {
                    echo '<script>window.location.href = "admin.php";</script>';
                    exit();
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
    <h1 id="suppUserH1">Suppression du compte de <?php echo $userFetched['username']; ?></h1>
    <div id='suppUserDiv'>
        <form method="POST" action="supprimerUser.php" enctype='multipart/form-data'>
            <h4>Êtes-vous sûr de vouloir supprimer ce profil ? (Cette action est définitive et entrainera la suppression de tous ses commentaires)</h4>
            
            <?php
                if($erreur['captcha']) {
                    echo "<p style='font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;'>Veuillez valider le Captcha avant d'appuyer sur envoyer !</p>";
                } else {
                    
                }
            ?>
            <input type='radio' name='confirmation' value='1'>Oui
            <input type='radio' name='confirmation' value='0' checked>Non
            <input type='hidden' name='id' value="<?php echo $userFetched['userId']; ?>">
            <div class="g-recaptcha" data-sitekey="6LepcKUaAAAAAE6KaSKp1us-gaAIfBJEUeO2J1Zk"></div>
            <input type='submit' name='suppUser' id='submitBtn' value='Confirmer'>
        </form>
    </div>
    <script src='https://www.google.com/recaptcha/api.js' async defer ></script>
</body>
</html>