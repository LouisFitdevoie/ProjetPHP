<?php session_start() ?>
<?php

    include_once('connexionDB.php');

    session_start();

    if(isset($_SESSION['username'])) {
        if(isset($_GET['id']) && !empty($_GET['id'])) {
            $regexNb = '/^[0-9]+$/i';
            if(!preg_match($regexNb,$_GET['id'])) {
                header('Location: films.php');
                exit();
            } else {
                $id = $_GET['id'];
                $getUser = $bdd->prepare('SELECT * FROM Users WHERE userId=:id');
                $getUser->bindValue(':id',$id);
                $getUser->execute();
                $getUserFetch = $getUser->fetch();
                //On vérifie si l'ID est associé à un utilisateur dans la BDD
                if($getUserFetch) {
                    //On vérifie si l'utilisateur modifie bien son mot de passe et pas celui d'un autre
                    if($_SESSION['username'] == $getUserFetch['username']) {
                        //Si bon user
                        ////On fait rien
                    } else {
                        //Si c'est pas le bon user, on vérifie si il n'a pas les droits d'admin
                        if($_SESSION['droits'] == 1) {
                            //echo 'mauvais user mais admin';
                        } else {
                            //Si pas admin -> redirige vers index.php
                            header('Location: index.php');
                            exit();
                        }
                    }
                } else {
                    header('Location: index.php');
                    exit();
                }
            }
        } elseif(isset($_POST['modifUser'])) {
            if(isset($_POST['id']) && !empty($_POST['id'])) {
                $regexNb = '/^[0-9]+$/i';
                if(!preg_match($regexNb,$_POST['id'])) {
                    header('Location: films.php');
                    exit();
                } else {
                    $id = $_POST['id'];
                    $getUser = $bdd->prepare('SELECT * FROM Users WHERE userId=:id');
                    $getUser->bindValue(':id',$id);
                    $getUser->execute();
                    $getUserFetch = $getUser->fetch();
                    //On vérifie si l'ID est associé à un utilisateur dans la BDD
                    if($getUserFetch) {
                        $erreur['ancienPasswordIncorrect'] = false;
                        $erreur['passwordsDifferents'] = false;
                        $erreur['nouveauTropPetit'] = false;
                        $erreur['memePassword'] = false;
                        //On vérifie si l'utilisateur modifie bien son mot de passe et pas celui d'un autre
                        if($_SESSION['username'] == $getUserFetch['username']) {
                            //Si bon user
                            $ancienPassword = sha1($_POST['ancienPassword']);
                            //On vérifie que l'ancien mot de passe soit correct
                            if($ancienPassword == $getUserFetch['password']) {
                                //On vérifie que les nouveaux mots de passe entrés soient les mêmes
                                if($_POST['password1'] == $_POST['password2']) {
                                    //On vérifie que le nouveau mot de passe a bien 5 caractères min
                                    if(strlen($_POST['password1']) >= 5) {
                                        //On vérifie que le nouveau mot de passe ne soit pas le même que l'ancien
                                        $nouveauPassword = sha1($_POST['password1']);
                                        //On vérifie que le nouveau mot de passe soit différent de l'ancien
                                        if($nouveauPassword != $ancienPassword) {
                                            //Changement de mot de passe, déconnexion de l'utilisateur et redirection vers login.php
                                            $changementPassword = $bdd->prepare('UPDATE Users SET password=:nouveauPassword WHERE userId=:id');
                                            $changementPassword->bindValue(':nouveauPassword',$nouveauPassword);
                                            $changementPassword->bindValue(':id',$id);
                                            $changementPassword->execute();
                                            session_destroy();
                                            header('Location: login.php');
                                            exit();
                                        } else {
                                            $erreur['memePassword'] = true;
                                        }
                                    } else {
                                        $erreur['nouveauTropPetit'] = true;
                                    }
                                } else {
                                    $erreur['passwordsDifferents'] = true;
                                }
                            } else {
                                $erreur['ancienPasswordIncorrect'] = true;
                            }
                        } else {
                            //Si c'est pas le bon user, on vérifie si il n'a pas les droits d'admin
                            if($_SESSION['droits'] == 1) {
                                if($_SESSION['username'] == $getUserFetch['username']) { //Si il est admin, on vérifie si c'est son propre mdp qu'il modifie
                                    $ancienPassword = sha1($_POST['ancienPassword']);
                                    //On vérifie que l'ancien mot de passe soit correct
                                    if($ancienPassword == $getUserFetch['password']) {
                                        //On vérifie que les nouveaux mots de passe entrés soient les mêmes
                                        if($_POST['password1'] == $_POST['password2']) {
                                            //On vérifie que le nouveau mot de passe a bien 5 caractères min
                                            if(strlen($_POST['password1']) >= 5) {
                                                //On vérifie que le nouveau mot de passe ne soit pas le même que l'ancien
                                                $nouveauPassword = sha1($_POST['password1']);
                                                //On vérifie que le nouveau mot de passe soit différent de l'ancien
                                                if($nouveauPassword != $ancienPassword) {
                                                    //Changement de mot de passe, déconnexion de l'utilisateur et redirection vers login.php
                                                    $changementPassword = $bdd->prepare('UPDATE Users SET password=:nouveauPassword WHERE userId=:id');
                                                    $changementPassword->bindValue(':nouveauPassword',$nouveauPassword);
                                                    $changementPassword->bindValue(':id',$id);
                                                    $changementPassword->execute();
                                                    session_destroy();
                                                    header('Location: login.php');
                                                    exit();
                                                } else {
                                                    $erreur['memePassword'] = true;
                                                }
                                            } else {
                                                $erreur['nouveauTropPetit'] = true;
                                            }
                                        } else {
                                            $erreur['passwordsDifferents'] = true;
                                        }
                                    } else {
                                        $erreur['ancienPasswordIncorrect'] = true;
                                    }
                                } else { //Si il modifie le mdp de qqn d'autre, on le déconnecte pas après
                                    //On vérifie que les nouveaux mots de passe entrés soient les mêmes
                                    if($_POST['password1'] == $_POST['password2']) {
                                        //On vérifie que le nouveau mot de passe a bien 5 caractères min
                                        if(strlen($_POST['password1']) >= 5) {
                                            //On vérifie que le nouveau mot de passe ne soit pas le même que l'ancien
                                            $nouveauPassword = sha1($_POST['password1']);
                                            //On vérifie que le nouveau mot de passe soit différent de l'ancien
                                            if($nouveauPassword != $ancienPassword) {
                                                //Changement de mot de passe, déconnexion de l'utilisateur et redirection vers login.php
                                                $changementPassword = $bdd->prepare('UPDATE Users SET password=:nouveauPassword WHERE userId=:id');
                                                $changementPassword->bindValue(':nouveauPassword',$nouveauPassword);
                                                $changementPassword->bindValue(':id',$id);
                                                $changementPassword->execute();
                                                header('Location: admin.php');
                                                exit();
                                            } else {
                                                $erreur['memePassword'] = true;
                                            }
                                        } else {
                                            $erreur['nouveauTropPetit'] = true;
                                        }
                                    } else {
                                        $erreur['passwordsDifferents'] = true;
                                    }
                                }
                            } else {
                                //Si pas admin -> redirige vers index.php
                                header('Location: index.php');
                                exit();
                            }
                        }
                    } else {
                        header('Location: index.php');
                        exit();
                    }
                }
            }
        } else {
            header('Location: index.php');
        }
    } else {
        header('Location: index.php');
    }
?>

<html lang='fr'>
<head>
    <title>Modification du mot de passe</title>
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
        form {
            padding: 0 5px;
        }
        #modifPasswordDiv {
            display: block;
            background-color: #FFFFFF10;
            width: 520px;
            margin-top: 25px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            border-radius: 15px;
        }
        #modifPasswordDiv form {
            margin-bottom: 0px;
        }
        #modifPasswordDiv table {
            margin-top: 15px;
            margin-left: auto;
            margin-right: auto;
        }
        #submitBtn {
            font-size: 0.8em;
            margin-top: 0px;
            margin-bottom: 10px;
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
            margin-left: auto;
            margin-right: auto;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php
        include_once("menu.php");
    ?>
    <div id='modifPasswordDiv'>
        <h3>Modifier le mot de passe de <?php echo $getUserFetch['username']; ?>*</h3>
        <form method='POST' action='modifierPassword.php'>
            <table>
                <?php
                    if($_SESSION['username'] == $getUserFetch['username']) {
                        echo '<tr>
                            <td>Ancien mot de passe  : </td>
                            <td><input type="password" name="ancienPassword" required></td>
                        </tr>';
                    }
                ?>
                <tr>
                    <td>Nouveau mot de passe  : </td>
                    <td><input type='password' name='password1' required></td>
                </tr>
                <tr>
                    <td>Entrez le nouveau mot de passe à nouveau  : </td>
                    <td><input type='password' name='password2' required></td>
                </tr>
            </table>
            <p style="font-size:0.8em">*Vous serez déconnecté, vous devrez donc vous reconnecter après avoir modifié votre mot de passe !</p>
            <?php
                if($erreur['ancienPasswordIncorrect']) {
                    echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">L\'ancien mot de passe est incorrect, réessayez !</p>';
                } else {
                    if($erreur['passwordsDifferents']) {
                        echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Les nouveaux mots de passe entrés sont différents, réessayez !</p>';
                    } else {
                        if($erreur['nouveauTropPetit']) {
                            echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Le nouveau mot de passe entré est trop petit, entrez un mot de passe de minimum 5 caractère !</p>';
                        } else {
                            if($erreur['memePassword']) {
                                echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Le nouveau mot de passe entré est identique à l\'ancien, réessayez !</p>';
                            }
                        }
                    }
                }
            ?>
            <input type='hidden' name='id' value=<?php echo $id; ?>>
            <input type='submit' name='modifUser' id='submitBtn' value="Enregistrer">
        </form>
    </div>
</body>
</html>