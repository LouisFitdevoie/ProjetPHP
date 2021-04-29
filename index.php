<?php session_start() ?>
<html lang="fr">
<head>
	<title>Accueil</title>
	<meta charset="utf-8">
	<meta name='author' content='Louis Fitdevoie'>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<style>
		/* MENU */
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
		/* RESTE */
		#mainAccueil {
			margin-top: 25px;
			margin-left: auto;
			margin-right: auto;
			background-color: #FFFFFF10;
			border-radius: 15px;
			max-width: 650px;
		}
		#mainAccueil #explication {
			text-align: justify;
			max-width: 600px;
			margin: 10px;
			padding: 0px;
		}
	</style>
</head>
<body>
	<?php
		include_once('menu.php');
		echo "<script>document.getElementById('accueilMenu').setAttribute('class','selected');</script>";
	?>
	<div id='mainAccueil'>
		<div id='explication'>
			<?php
				if(isset($_SESSION['username'])) {
					echo '<h3>Bienvenue ',($_SESSION['username']),' !</h3>';
					
				} else {
					echo "Vous n'êtes pas connecté !";
				}
			?>
			<p>Le Marvel Cinematic Universe, ou MCU, est une franchise cinématographique produite par Marvel Studios. A l’heure actuelle, le MCU est composé de 23 films et d’une série, mais de nombreux films et séries sont en préparation et devraient sortir prochainement.</p>
			<p>Le MCU est composé de 4 phases et de 2 cycles. La Phase I débute avec le film Iron Man  sorti en 2008 et se termine avec le film Avenger sorti en 2012. Vient ensuite la Phase II qui commence avec Iron Man 3 en 2013 et finit par Anti-Man en 2015. Le film Captain America : Civil War sorti en 2016 marque le début de la Phase III qui se finira en 2019 avec Spider-Man : Far From Home. La Phase IV a, elle, commencé en 2021 avec la sortie de la série Wandavision sur la plateforme de streaming Disney+.</p>
			<p>Les Phases I, II et III composent le Premier Cycle du MCU qui porte le nom de Saga de l’Infinité. Le Deuxième Cycle ne porte pas encore de nom mais il devrait être composé des Phases IV, V et VI.</p>
			<p>A l’origine, les films faisant partie de cet univers cinématographique traitent des Avengers, un groupe de super-héros comprenant Iron Man, Hulk, Thor, Black Widow, Hawkeye et Captain America.<p>
		</div>
	</div>
</body>
</html>

