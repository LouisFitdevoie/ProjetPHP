<?php session_start() ?>
<?php

    include_once('connexionDB.php');

    //On vérifie si l'utilisateur est déjà connecté -> si oui on le redirige vers l'accueil
    if(isset($_SESSION['username'])) {
        echo '<script>window.location.href = "index.php";</script>';
        exit();
    } else {
        //On réinitialise les erreurs
        $erreur['captcha'] = false;
        $erreur['champsVides'] = false;
        $erreur['password'] = false;
        $erreur['usernameIncorrect'] = false;
        $erreur['username'] = false;
        $erreur['nomPrenom'] = false;
        $erreur['nom'] = false;
        $erreur['prenom'] = false;
        $erreur['tropPetit'] = false;

        
        //On vérifie que l'utilisateur a bien cliqué sur Envoyer
        if(isset($_POST['inscriptionValide'])) {
            if(!empty($_POST['g-recaptcha-response'])) {
                $secret = '6LepcKUaAAAAAIkB52vCXMgQ5h4wHjqDG-i4d_mU';
                $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
                $responseData = json_decode($verifyResponse);
                if($responseData->success){
                    //On vérifie que tous les champs ont bien été complétés
                    if(!empty($_POST['username']) && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['password1']) && !empty($_POST['password2'])) {
                        //On vérifie que l'utilisateur a bien entré deux fois le même mot de passe
                        if($_POST['password1'] === $_POST['password2']) {
                            //On vérifie que le mot de passe ait minimum 5 caractères
                            if(strlen($_POST['password1']) >= 5) {
                                $password = sha1($_POST['password1']);
                                //On vérifie que le nom d'utilisateur, le nom et le prénom ne commencent ou ne finissent pas par un ou plusieurs espaces
                                $regexUsername = '/^[A-Za-z][A-Za-z0-9]{3,31}$/i';
                                $regexNomPrenom = '/^[A-Za-z]{2,31}$/i';
                                if(preg_match($regexUsername,$_POST['username'])) {
                                    $username = $_POST['username'];
                                } else {
                                    $erreur['usernameIncorrect'] = true;
                                }
                                if(preg_match($regexNomPrenom,$_POST['nom'])) {
                                    $nom = $_POST['nom'];
                                } else {
                                    $erreur['nom'] = true;
                                }
                                if(preg_match($regexNomPrenom,$_POST['prenom'])) {
                                    $prenom = $_POST['prenom'];
                                } else {
                                    $erreur['prenom'] = true;
                                }

                                //On vérifie que le nom d'utilisateur n'existe pas déjà dans la BDD
                                $verifUsernameNotExist = $bdd->prepare('SELECT * FROM Users WHERE (username=:username)');
                                $verifUsernameNotExist->bindValue(':username',$username);
                                $verifUsernameNotExist->execute();
                                $usernameExist = $verifUsernameNotExist->fetch();
                
                                if($usernameExist) {
                                    //echo "Le nom d'utilisateur entré existe déjà";
                                    $erreur['username'] = true;
                                } else {
                                    //echo "Username ok<br>";
                                    if(!$erreur['usernameIncorrect'] && !$erreur['nom'] && !$erreur['prenom']) {
                                        $verifNomPrenomNotExist = $bdd->prepare('SELECT * FROM Users WHERE (nom=:nom AND prenom=:prenom)');
                                        $verifNomPrenomNotExist->bindValue(':nom',$nom);
                                        $verifNomPrenomNotExist->bindValue(':prenom',$prenom);
                                        $verifNomPrenomNotExist->execute();
                                        $nomPrenomExist = $verifNomPrenomNotExist->fetch();
                    
                                        if($nomPrenomExist) {
                                            //echo 'La combinaison Nom et Prénom existe déjà !';
                                            $erreur['nomPrenom'] = true;
                                        } else {
                                            //echo 'Tout est ok';
                                            //Enregistrement DB 
                                            $req = $bdd->prepare('INSERT INTO Users VALUES (null,:username,:password,:nom,:prenom,0)');
                                            $req->bindValue(':username',$username);
                                            $req->bindValue(':password',$password);
                                            $req->bindValue(':nom',ucfirst($nom));
                                            $req->bindValue(':prenom',ucfirst($prenom));
                                            $req->execute();
                                            $req->closeCursor();
                    
                                            $_SESSION['droits'] = 0;
                                            $_SESSION['username'] = $username;

                                            echo '<script>window.location.href = "index.php";</script>';
                                            exit(); 
                                        }
                                    }
                                }
                            } else {
                                $erreur['tropPetit'] = true;
                            }
                            
                        } else {
                            $erreur['password'] = true;
                        }
                    } else {
                        $erreur['champsVides'] = true;
                    }
                } else {
                    $erreur['captcha'] = true;
                }
            } else {
                $erreur['captcha'] = true;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang='fr'>
<head>
    <title>Inscription</title>
    <meta charset='utf-8'>
    <meta name='author' content='Louis Fitdevoie'>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="Ressources/style.css">
</head>
<body>
    <?php
        include_once("menu.php");
        echo "<script>document.getElementById('inscriptionMenu').setAttribute('class','selected');</script>";
    ?>
    <div id='inscriptionDiv'>
        <form method='POST' action='inscription.php'>
            <table>
                <tr>
                    <td>Nom d'utilisateur : </td>
                    <td><input type='text' name='username' required <?php if(isset($_POST['inscriptionValide'])) { echo "value='",$_POST['username'],"'"; } ?>></td>
                </tr>
                <tr>
                    <td>Nom  : </td>
                    <td><input type='text' name='nom' required <?php if(isset($_POST['inscriptionValide'])) { echo "value='",$_POST['nom'],"'"; } ?>></td>
                </tr>
                <tr>
                    <td>Prénom  : </td>
                    <td><input type='text' name='prenom' required <?php if(isset($_POST['inscriptionValide'])) { echo "value='",$_POST['prenom'],"'"; } ?>></td>
                </tr>
                <tr>
                    <td>Mot de passe  : </td>
                    <td><input type='password' name='password1' required></td>
                </tr>
                <tr>
                    <td>Entrez le mot de passe à nouveau  : </td>
                    <td><input type='password' name='password2' required></td>
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
                        echo "<p style='font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;'>Un ou plusieurs champs n'ont pas été complétés, veuillez compléter tous les champs avant de valider !</p>";
                    } else {
                        if($erreur['usernameIncorrect']) {
                            echo "<p style='font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;'>Le nom d'utilisateur n'est pas correct, veuillez réessayer en entrant un nom d'utilisateur qui contient 4 à 31 caractères et qui ne contient que des lettres ou des chiffres !</p>";
                        } else {
                            if($erreur['username']) {
                                echo "<p style='font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;'>Le nom d'utilisateur entré existe déjà, veuillez réessayer !</p>";
                            } else {
                                if($erreur['nom']) {
                                    echo "<p style='font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;'>Le nom entré contient d'autres caractères que des lettres, veuillez réessayer !</p>";
                                } else {
                                    if($erreur['prenom']) {
                                        echo "<p style='font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;'>Le prénom entré contient d'autres caractères que des lettres, veuillez réessayer !</p>";
                                    } else {
                                        if($erreur['nomPrenom']) {
                                            echo "<p style='font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;'>Un compte utilisateur associé à la combinaison nom prénom entrée existe déjà, veuillez réessayer !</p>";
                                        } else {
                                            if($erreur['password']) {
                                                echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Erreur, les mots de passe entrés sont différents !</p>';
                                            } else {
                                                if($erreur['tropPetit']) {
                                                    echo "<p style='font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;'>Le mot de passe entré doit contenir au minimum 5 caractères, réessayez !</p>";
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
            <input type='submit' name='inscriptionValide' id='submitBtn' value='Inscription'>
        </form>
    </div>
    <script src='https://www.google.com/recaptcha/api.js' async defer ></script>
</body>
</html>