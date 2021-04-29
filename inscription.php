<?php session_start() ?>
<?php

    include_once('connexionDB.php');

    session_start();

    if(isset($_SESSION['username'])) {
        header('Location: index.php');
        exit();
    } else {
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
                                $regexUsername = '/^[A-Za-z][A-Za-z0-9]{4,31}$/i';
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
                                            header('location:index.php');
                    
                                            $_SESSION['droits'] = $data2[0];
                                            $_SESSION['username'] = $username;
                    
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

<html lang='fr'>
<head>
    <title>Inscription</title>
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
        #inscriptionDiv {
            display: block;
            background-color: #FFFFFF10;
            width: 460px;
            margin-top: 25px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            border-radius: 15px;
        }
        #inscriptionDiv form {
            margin-bottom: 0px;
        }
        #inscriptionDiv table {
            margin-top: 15px;
            margin-left: auto;
            margin-right: auto;
        }
        #inscriptionDiv #submitBtn {
            margin-top: 10px;
            margin-bottom: 15px;
        }
        .g-recaptcha > div {
            margin-left: auto;
            margin-right: auto;
            margin-top: 10px;
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
    </style>
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