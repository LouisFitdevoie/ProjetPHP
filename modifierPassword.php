<?php session_start() ?>
<?php

    include_once('connexionDB.php');

    session_start();
    //On vérifie que l'utilisateur est bien connecté
    if(isset($_SESSION['username'])) {
        if(isset($_GET['id']) && !empty($_GET['id'])) {
            $regexNb = '/^[0-9]+$/i';
            //On vérifie que l'ID de l'utilisateur soit bien un nombre
            if(!preg_match($regexNb,$_GET['id'])) {
                echo '<script>window.location.href = "films.php";</script>';
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
                            echo '<script>window.location.href = "index.php";</script>';
                            exit();
                        }
                    }
                } else {
                    echo '<script>window.location.href = "index.php";</script>';
                    exit();
                }
            }
        //Si l'utilisateur a cliqué sur le bouton pour modifier le password
        } elseif(isset($_POST['modifUser'])) {
            //On vérifie que l'ID soit bien set
            if(isset($_POST['id']) && !empty($_POST['id'])) {
                $regexNb = '/^[0-9]+$/i';
                //On vérifie que l'ID soit bien un nombre
                if(!preg_match($regexNb,$_POST['id'])) {
                    echo '<script>window.location.href = "films.php";</script>';
                    exit();
                } else {
                    //On récupère les données de l'utilisateur correspondant à l'ID
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
                                            echo '<script>window.location.href = "login.php";</script>';
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
                                                    echo '<script>window.location.href = "login.php";</script>';
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
                                                echo '<script>window.location.href = "admin.php";</script>';
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
                                echo '<script>window.location.href = "index.php";</script>';
                                exit();
                            }
                        }
                    } else {
                        echo '<script>window.location.href = "index.php";</script>';
                        exit();
                    }
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
<!DOCTYPE html>
<html lang='fr'>
<head>
    <title>Modification du mot de passe</title>
    <meta charset='utf-8'>
    <meta name='author' content='Louis Fitdevoie'>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="Ressources/style.css">
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