<?php
echo '
<div id="menu">
    <a href="index.php">
        <img id="logoSite" src="Ressources/img/MCU_logo.png" onmouseover="afficher_logo_gif(this)" onmouseout="masquer_logo_gif(this)">
    </a>
    <nav>
        <a href="index.php" id="accueilMenu">Accueil</a>
        <a href="films.php" id="filmsMenu">Films</a>';
            if(isset($_SESSION["username"])) {
                echo "<a href='profil.php' id='profilMenu'>Profil</a><a href='logout.php' id='logoutMenu'>Se déconnecter</a>";
            } else {
                echo "<a href='login.php' id='loginMenu'>Login</a><a href='inscription.php' id='inscriptionMenu'>Inscription</a>";
            }
            if($_SESSION["droits"] == 1) {
                echo "<a href='admin.php' id='adminMenu'>Admin</a>";
            }
    echo '</nav></div>';
    echo "
    <script>
        function afficher_logo_gif(img) {
            img.src = 'Ressources/img/marvel.gif';
        }

        function masquer_logo_gif(img) {
            img.src = 'Ressources/img/MCU_logo.png';
        }
    </script>";
?>