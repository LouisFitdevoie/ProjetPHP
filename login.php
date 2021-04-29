<?php
    session_start();

    include_once('connexionDB.php');

    if(isset($_SESSION['username'])) {
        header('Location: index.php');
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
                
                if($data[0] == 1) {
                    $req2 = $bdd->prepare('SELECT droits FROM Users WHERE username=:username');
                    $req2->bindValue(':username',$username);
                    $req2->execute();
                    $data2 = $req2->fetch();
    
                    $_SESSION['droits'] = $data2[0];
                    $_SESSION['username'] = $username;
    
                    $req->closeCursor();
                    $req2->closeCursor();
                    header('location:index.php');
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

<html lang='fr'>
    <head>
        <meta charset='utf-8'>
        <meta name='author' content='Louis Fitdevoie'>
        <title>Login</title>
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
            #loginDiv {
                display: block;
                background-color: #FFFFFF10;
                width: 350px;
                margin-top: 25px;
                margin-left: auto;
                margin-right: auto;
                text-align: center;
                border-radius: 15px;
            }
            #loginDiv form {
                margin-bottom: 0px;
            }
            #loginDiv table {
                margin-top: 10px;
                margin-left: auto;
                margin-right: auto;
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