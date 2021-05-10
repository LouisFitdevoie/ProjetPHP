<?php session_start() ?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Accueil</title>
	<meta charset="utf-8">
	<meta name='author' content='Louis Fitdevoie'>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="Ressources/style.css">
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