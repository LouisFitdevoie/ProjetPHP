<?php
    session_start();

    include_once('connexionDB.php');
    //Si l'utilisateur est déjà connecté -> on le redirige vers l'accueil
    if(isset($_SESSION['username'])) {
        echo '<script>window.location.href = "index.php";</script>';
        exit();
    } else {
        $erreur['password'] = false;
        $erreur['champsVides'] = false;

        //On vérifie que l'utilisateur vienne bien du formulaire
        if(isset($_POST['valid'])) {
            //On vérifie que l'utilisateur ait bien rempli tous les champs
            if(!empty($_POST['username']) && !empty($_POST['password'])) {
                $req = $bdd->prepare('SELECT count(*) FROM Users WHERE (username=:username AND password=:password)');
                $username = $_POST['username'];
                $password = sha1($_POST['password']);
                $req->bindValue(':username',$username);
                $req->bindValue(':password',$password);
                $req->execute();
                $data = $req->fetch();
                //Si la combinaison nom d'utilisateur et mot de passe est correcte -> connexion de l'utilisateur et redirection vers l'accueil
                if($data[0] == 1) {
                    $req2 = $bdd->prepare('SELECT droits FROM Users WHERE username=:username');
                    $req2->bindValue(':username',$username);
                    $req2->execute();
                    $data2 = $req2->fetch();
    
                    $_SESSION['droits'] = $data2[0];
                    $_SESSION['username'] = $username;
    
                    $req->closeCursor();
                    $req2->closeCursor();
                    echo '<script>window.location.href = "index.php";</script>';
                    exit();
                } else {
                    $erreur['password'] = true;
                }
            } else {
                $erreur['champsVides'] = true;
            }
            
        }
    }
?>
<!DOCTYPE html>
<html lang='fr'>
    <head>
        <meta charset='utf-8'>
        <meta name='author' content='Louis Fitdevoie'>
        <title>Login</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="Ressources/style.css">
    </head>
    <body>
        <?php
            include_once("menu.php");
            echo "<script>document.getElementById('loginMenu').setAttribute('class','selected');</script>";
        ?>
        <div id='loginDiv'>
            <form method='POST' action='login.php'>
                <table>
                    <tr>
                        <td>Nom d'utilisateur : </td>
                        <td><input type='text' name='username' required <?php if(isset($_POST['valid'])) { echo "value='",$_POST['username'],"'"; } ?>></td>
                    </tr>
                    <tr>
                        <td>Mot de passe  : </td>
                        <td><input type='password' name='password' required></td>
                    </tr>
                </table>
                <?php
                    if($erreur['champsVides']) {
                        echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Un ou plusieurs champs sont vides, Veuillez remplir tous les champs !</p>';
                    } else {
                        if($erreur['password']) {
                            echo '<p style="font-size:0.8em;color:#E13930;margin-top:5px;margin-bottom:5px;">Mot de passe incorrect ou nom d\'utilisateur inconnu, réessayez !</p>';
                        }
                    }
                ?>
                <input type='submit' name='valid' id='submitBtn' value="Connexion">
            </form>
        </div>
    </body>
</html>