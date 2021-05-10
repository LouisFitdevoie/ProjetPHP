<?php session_start() ?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Louis Fitdevoie">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="Ressources/style.css">
        <title>Profil</title>
    </head>
    <body>
        <?php
            include_once("connexionDB.php");
            include_once('menu.php');
			//On vérifie si l'utilisateur est connecté
            if(isset($_SESSION['username'])) {
				//On récupère les données associées au compte utilisateur
                $req = $bdd->prepare('SELECT * FROM Users WHERE username=:username');
                $req->bindValue(':username',$_SESSION['username']);
                $req->execute();
                $data = $req->fetch();
                
                $username = $data['username'];
                $nom = $data['nom'];
                $prenom = $data['prenom'];
                $id = $data['userId'];

                echo "<script>document.getElementById('profilMenu').setAttribute('class','selected');</script>";
                echo '<div id="container"><div id="profilContent">';
                        echo '<h3>Bienvenue sur ton profil ',$username,' !</h3>';
                        echo '<p>Sur cette page, tu peux voir tes informations personnelles et modifier ton mot de passe.</p>';
                        echo '<p>Nom d\'utilisateur : ',$username,'</p>';
                        echo '<p>Nom : ',$nom,'</p>';
                        echo '<p>Prénom : ',$prenom,'</p>';
                        echo '<a id="editPassword" href="modifierPassword.php?id=',$id,'">Modifier le mot de passe</a>';
						echo '<a id="deleteUser" href="supprimerUser.php?id=',$id,'">Supprimer le profil</a>';
                    echo '</div></div>';
                
                if(isset($_SESSION['droits'])) {
                    //Si l'utilisateur n'a pas les droits d'administration
                    if($_SESSION['droits'] == 0) {
                        //On récupère son ID d'utilisateur
                        $getUserId = $bdd->prepare('SELECT userId FROM Users WHERE username=:username');
                        $getUserId->bindValue(':username',$_SESSION['username']);
                        $getUserId->execute();
                        $userId = $getUserId->fetch();
                        $userId = $userId[0];

                        //On regarde dans les enregistrements de la table Messages si l'utilisateur a envoyé ou reçu des messages
                        $getNbMessages = $bdd->prepare('SELECT count(*) FROM Messages WHERE auteurId=:userId OR destinataireId=:userId');
                        $getNbMessages->bindValue(':userId',$userId);
                        $getNbMessages->execute();
                        $nbMessages = $getNbMessages->fetch();
                        $nbMessages = $nbMessages[0];
                        if($nbMessages > 0) {
                            //Si l'utilisateur a reçu ou envoyé des messages -> on les récupère
                            $getMessages = $bdd->prepare('SELECT * FROM Messages WHERE auteurId=:userId OR destinataireId=:userId ORDER BY date');
                            $getMessages->bindValue(':userId',$userId);
                            $getMessages->execute();
                            //On affiche les messages dans un div scrollable
                            echo '<div id="scrollMessagesContainer"><div id="scrollMessagesDiv">';
                                echo '<h3>Historique des messages envoyés à l\'administrateur</h3>';
                                while($messages = $getMessages->fetch()) {
                                    //Affichage des messages
                                    $getUsername = $bdd->prepare('SELECT username FROM Users WHERE userId=:auteurId');
                                    $getUsername->bindValue(':auteurId',$messages['auteurId']);
                                    $getUsername->execute();
                                    $username = $getUsername->fetch();
                                    $username = $username[0];

                                    echo '<div class="message';
                                        if($_SESSION['username'] == $username) {
                                            echo '">';
                                        } else {
                                            echo ' destinataire">';
                                        }
                                        echo '<table><tr><th>';
                                        echo $username;
                                        echo '</th></tr><tr><td class="messageContent">';
                                        echo $messages['content'];
                                        echo '</td></tr><tr><td class="messageDate">';
                                        echo $messages['date'];
                                        echo '</td></tr></table>';
                                    echo '</div>';
                                }
                            echo '</div></div>';
                        }

                        echo '<div id="newMessageContainer">';
                            echo '<div id="newMessage">';
                                echo '<h3>Envoyer un message à l\'administrateur</h3>';
                                echo '<form method="POST" action="envoyerMessage.php">';
                                    echo '<input type="hidden" name="destinataireId" value="1">'; //Value = 1 -> admin à l'userId 1
                                    echo '<textarea name="content" id="messageContent" placeholder="Entrez votre message ici..."></textarea>';
                                    echo '<div id="btnDiv"><input type="submit" name="addMessage" id="addMessageBtn" value="Envoyer"></div>';
                                echo '</form>';
                            echo '</div>';
                        echo '</div>';
                    } elseif($_SESSION['droits'] == 1) {
                        //Si l'utilisateur a les droits d'administration
                        //On récupère son ID d'utilisateur
                        $getUserId = $bdd->prepare('SELECT userId FROM Users WHERE username=:username');
                        $getUserId->bindValue(':username',$_SESSION['username']);
                        $getUserId->execute();
                        $userId = $getUserId->fetch();
                        $userId = $userId[0];
                        //On regarde dans les enregistrements de la table Messages si l'utilisateur a envoyé ou reçu des messages
                        $getNbMessages = $bdd->prepare('SELECT count(*) FROM Messages WHERE auteurId=:userId OR destinataireId=:userId');
                        $getNbMessages->bindValue(':userId',$userId);
                        $getNbMessages->execute();
                        $nbMessages = $getNbMessages->fetch();
                        $nbMessages = $nbMessages[0];
                        if($nbMessages > 0) {
                            //Si l'utilisateur a reçu ou envoyé des messages -> on les récupère
                            $getMessages = $bdd->prepare('SELECT * FROM Messages WHERE destinataireId=:userId OR auteurId=:userId ORDER BY date DESC');
                            $getMessages->bindValue(':userId',$userId);
                            $getMessages->execute();
                            //On les affiche dans un div scrollable
                            echo '<div id="scrollMessagesContainer"><div id="scrollMessagesDiv">';
                                echo '<h3>Derniers messages reçus</h3>';
                                while($messages = $getMessages->fetch()) {
                                    $getUsername = $bdd->prepare('SELECT username FROM Users WHERE userId=:auteurId');
                                    $getUsername->bindValue(':auteurId',$messages['auteurId']);
                                    $getUsername->execute();
                                    $username = $getUsername->fetch();
                                    $username = $username[0];

                                    echo '<div class="message';
                                        if($_SESSION['username'] == $username) {
                                            echo '">';
                                        } else {
                                            echo ' destinataire">';
                                        }
                                        echo '<table><tr><th>';
                                        if($_SESSION['username'] == $username) {
                                            echo $username,' à ';
                                            $getDestinataire = $bdd->prepare('SELECT username FROM Users WHERE userId=:destinataireId');
                                            $getDestinataire->bindValue(':destinataireId',$messages['destinataireId']);
                                            $getDestinataire->execute();
                                            $destUsername = $getDestinataire->fetch();
                                            $destUsername = $destUsername[0];
                                            echo $destUsername;
                                        } else {
                                            echo 'De ',$username;
                                        }
                                        echo '</th></tr><tr><td class="messageContent">';
                                        echo $messages['content'];
                                        echo '</td></tr><tr><td class="messageDate">';
                                        echo $messages['date'];
                                        echo '</td></tr>';
                                        //On vérifie si l'utilisateur est l'auteur du message ou pas
                                        if($username == $_SESSION['username']) {
                                            echo '</table>';
                                        } else {
                                            //S'il n'est pas l'auteur du message -> affiche un bouton pour lui permettre de répondre à l'auteur
                                            echo '<tr><td><a id="',$username,'" class="repondreBtn">Répondre','<p style="display:none">',$messages['auteurId'],'</p>','</a></td></tr></table>';
                                        }
                                    echo '</div>';
                                }
                            echo '</div></div>';
                            echo '<div id="newMessageContainer" class="hidden">';
                                echo '<div id="newMessage">';
                                    echo '<h3 id="repondreH3">Répondre à </h3>';
                                    echo '<form method="POST" action="envoyerMessage.php">';
                                        echo '<input type="hidden" name="destinataireId" value="1" id="destinataireId">';
                                        echo '<textarea name="content" id="messageContent" placeholder="Entrez votre message ici..."></textarea>';
                                        echo '<div id="btnDiv"><input type="submit" name="addMessage" id="addMessageBtn" value="Envoyer"></div>';
                                    echo '</form>';
                                echo '</div>';
                            echo '</div>';
                        }
                    }
                }
            } else {
                echo '<script>window.location.href = "index.php";</script>';
            }
        ?>
        </div>
        <script src='Ressources/jquery-3.3.1.min.js'></script>
        <script>
            $(document).ready(function() {
                $('.repondreBtn',this).click(function() {
                    console.log($(this).attr('id'));
                    $('#newMessageContainer').removeClass('hidden');
                    $('#repondreH3').text("Répondre à " + $(this).attr('id'));
                    $('#destinataireId').attr("value",$(this).children('p').text());
                });
            });
        </script>
    </body>
</html>