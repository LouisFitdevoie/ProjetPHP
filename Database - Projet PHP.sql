-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le :  mar. 08 juin 2021 à 14:09
-- Version du serveur :  5.7.26
-- Version de PHP :  7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `projetPHP`
--
CREATE DATABASE IF NOT EXISTS `projetPHP` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `projetPHP`;

-- --------------------------------------------------------

--
-- Structure de la table `Commentaires`
--

CREATE TABLE `Commentaires` (
  `commentId` int(11) NOT NULL,
  `auteurId` int(11) NOT NULL,
  `filmId` int(11) NOT NULL,
  `content` text NOT NULL,
  `note` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Commentaires`
--

INSERT INTO `Commentaires` (`commentId`, `auteurId`, `filmId`, `content`, `note`, `date`) VALUES
(1, 11, 1, 'Très bon film !', 4, '2021-04-13 15:02:21'),
(3, 14, 1, 'Pas vraiment aimé...', 3, '2021-04-13 16:23:28'),
(4, 2, 1, 'C\'était cool !', 5, '2021-04-13 16:23:51'),
(5, 1, 2, 'Je n\'ai pas aimé ce film, il était long et pas très réussi...', 2, '2021-04-29 12:32:49'),
(7, 1, 6, 'Très bon film !', 5, '2021-04-27 11:20:57'),
(8, 1, 1, 'Bon film', 4, '2021-04-29 12:32:33'),
(9, 1, 3, 'Bon film mais moins bon que le premier !', 3, '2021-04-29 16:38:25'),
(14, 1, 4, 'Pas génial comme film', 2, '2021-04-29 17:29:05'),
(19, 11, 7, 'Je préférais le premier film', 3, '2021-05-03 18:29:10'),
(20, 11, 8, 'Pas terrible', 2, '2021-05-03 18:29:26'),
(21, 11, 9, 'Génial', 5, '2021-05-03 18:29:35'),
(22, 2, 13, 'Un peu long quand même ! Mais j\'ai bien aimé le fait qu\'il y ait un conflit entre les Avengers. Team Iron Man', 4, '2021-05-08 21:26:54'),
(23, 1, 5, 'Génial !', 4, '2021-05-19 11:36:13');

-- --------------------------------------------------------

--
-- Structure de la table `Films`
--

CREATE TABLE `Films` (
  `filmId` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `resume` text NOT NULL,
  `dateDeSortie` varchar(50) NOT NULL,
  `duree` varchar(60) NOT NULL,
  `acteurs` text NOT NULL,
  `bandeAnnonce` varchar(500) NOT NULL,
  `streaming` text NOT NULL,
  `streamingLink` varchar(200) NOT NULL,
  `imageLink` varchar(255) NOT NULL,
  `ordreSortie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Films`
--

INSERT INTO `Films` (`filmId`, `titre`, `resume`, `dateDeSortie`, `duree`, `acteurs`, `bandeAnnonce`, `streaming`, `streamingLink`, `imageLink`, `ordreSortie`) VALUES
(1, 'Iron man', 'Tony Stark, inventeur de génie, vendeur d\'armes et playboy milliardaire, est kidnappé en Aghanistan. Forcé par ses ravisseurs de fabriquer une arme redoutable, il construit en secret une armure high-tech révolutionnaire qu\'il utilise pour s\'échapper. Comprenant la puissance de cette armure, il décide de l\'améliorer et de l\'utiliser pour faire régner la justice et protéger les innocents.', '30 avril 2008', '2h06', 'Robert Downey Jr. (Tony Stark), Terrence Howard (Lieutenant-Colonel James Rhodes), Gwyneth Paltrow (Pepper Potts), Jeff Bridges (Obadiah Stane)', 'https://www.youtube.com/watch?v=rDCTb9Gp2qk', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-iron-man/6aM2a8mZATiu', 'iron_man.jpg', 1),
(2, 'L\'Incroyable Hulk', 'Le scientifique Bruce Banner cherche désespérément un antidote aux radiations gamma qui ont créé Hulk. Il vit dans l\'ombre, toujours amoureux de la belle Betty Ross et parcourt la planète à la recherche d\'un remède.\r\nLa force destructrice de Hulk attire le Général Thunderbolt Ross et son bras droit Blonsky qui rêvent de l\'utiliser à des fins militaires. Ils tentent de développer un sérum pour créer des soldats surpuissants.\r\nDe retour aux États-Unis, Bruce Banner se découvre un nouvel ennemi. Après avoir essayé le sérum expérimental, Blonsky est devenu L\'Abomination, un monstre incontrôlable dont la force pure est même supérieure à celle de Hulk. Devenu fou, il s\'est transformé en plein cœur de New York.\r\nPour sauver la ville de la destruction totale, Bruce Banner va devoir faire appel au monstre qui sommeille en lui...', '23 juillet 2008', '1h54', 'Edward Norton (Bruce Banner/Hulk), Liv Tyler (Betty Ross), Tim Roth (Emil Blonsky / L\'Abomination), William Hurt (Général Thadeus Ross/Ross-la-foudre)', 'https://www.youtube.com/watch?v=vbnsfPWkjWg', '', '', 'l_incroyable_hulk.jpg', 2),
(3, 'Iron man 2', 'Le monde sait désormais que l\'inventeur milliardaire Tony Stark et le super-héros Iron Man ne font qu\'un. Malgré la pression du gouvernement, de la presse et du public pour qu\'il partage sa technologie avec l\'armée, Tony n\'est pas disposé à divulguer les secrets de son armure, redoutant que l\'information atterrisse dans de mauvaises mains. Avec Pepper Potts et James \"Rhodey\" Rhodes à ses côtés, Tony va forger de nouvelles alliances et affronter de nouvelles forces toutes-puissantes... ', '28 avril 2010', '2h04', 'Robert Downey Jr. (Tony Stark / Iron Man), Don Cheadle (Lieutenant-colonel Jim Rhodes), Scarlett Johansson (Natasha Romanoff / Black Widow), Mickey Rourke (Whiplash), Gwyneth Paltrow (Pepper Potts), Sam Rockwell (Justin Hammer), Samuel L. Jackson (Nick Fury)', 'https://www.youtube.com/watch?v=VdZj2QYTAic', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-iron-man-2/lXjCr9QmGGQJ', 'iron_man_2.jpg', 3),
(4, 'Thor', 'Au royaume d’Asgard, Thor est un guerrier aussi puissant qu’arrogant dont les actes téméraires déclenchent une guerre ancestrale. Banni et envoyé sur Terre, par son père Odin, il est condamné à vivre parmi les humains. Mais lorsque les forces du mal de son royaume s’apprêtent à se déchaîner sur la Terre, Thor va apprendre à se comporter en véritable héros… ', '27 avril 2011', '1h55', 'Chris Hemsworth (Thor), Natalie Portman (Jane Foster), Anthony Hopkins (Odin), Tom Hiddleston (Loki), Stellan Skarsgård (Dr. Erik Selvig), Colm Feore (Laufey), Idris Elba (Heimdall), Ray Stevenson (Volstagg)', 'https://www.youtube.com/watch?v=pzT3yeV9lT4', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-thor/1p4vdKzTuhzr', 'thor.jpg', 4),
(5, 'Captain America : First Avenger', 'Captain America: First Avenger nous plonge dans les premières années de l’univers Marvel. Steve Rogers, frêle et timide, se porte volontaire pour participer à un programme expérimental qui va le transformer en un Super Soldat connu sous le nom de Captain America. Allié à Bucky Barnes et Peggy Carter, il sera confronté à la diabolique organisation HYDRA dirigée par le redoutable Red Skull.      ', '17 août 2011', '2h04', 'Chris Evans (Steve Rogers / Captain America), Hayley Atwell (Peggy Carter), Sebastian Stan (Bucky), Tommy Lee Jones (Général Chester Phillips), Hugo Weaving (Red Skull), Dominic Cooper (Howard Stark), Richard Armitage (Heinz Kruger), Stanley Tucci (Dr. Abraham Erskine)', 'https://www.youtube.com/watch?v=IsiV9IJieMk', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-captain-america-first-avenger/6xvB6xZ4r95O', 'captain_america_first_avenger.jpg', 5),
(6, 'Avengers', 'Lorsque Nick Fury, le directeur du S.H.I.E.L.D., l\'organisation qui préserve la paix au plan mondial, cherche à former une équipe de choc pour empêcher la destruction du monde, Iron Man, Hulk, Thor, Captain America, Hawkeye et Black Widow répondent présents.\r\nLes Avengers ont beau constituer la plus fantastique des équipes, il leur reste encore à apprendre à travailler ensemble, et non les uns contre les autres, d\'autant que le redoutable Loki a réussi à accéder au Cube Cosmique et à son pouvoir illimité...', '20 avril 2012', '2h23', 'Robert Downey Jr. (Tony Stark / Iron man), Chris Evans (Steve Rogers / Captain America), Mark Ruffalo (Bruce Banner / Hulk), Chris Hemsworth (Thor), Scarlett Johansson (Natasha Romanoff / Black Widow), Jeremy Renner (Clint Barton / Hawkeye), Tom Hiddleston (Loki), Stellan Skarsgård (Dr. Erik Selvig)', 'https://www.youtube.com/watch?v=b-kTeJhHOhc', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-avengers/2h6PcHFDbsPy', 'avengers.jpg', 6),
(7, 'Iron man 3', 'Tony Stark, l’industriel flamboyant qui est aussi Iron Man, est confronté cette fois à un ennemi qui va attaquer sur tous les fronts. Lorsque son univers personnel est détruit, Stark se lance dans une quête acharnée pour retrouver les coupables. Plus que jamais, son courage va être mis à l’épreuve, à chaque instant. Dos au mur, il ne peut plus compter que sur ses inventions, son ingéniosité, et son instinct pour protéger ses proches. Alors qu’il se jette dans la bataille, Stark va enfin découvrir la réponse à la question qui le hante secrètement depuis si longtemps : est-ce l’homme qui fait le costume ou bien le costume qui fait l’homme ?', '24 avril 2013', '2h11', 'Robert Downey Jr. (Tony Stark / Iron man), Gwyneth Paltrow (Pepper Potts), Don Cheadle (James Rhodes), Ben Kingsley (Le Mandarin), Guy Pearce (Docteur Aldrich Killian), James Badge Dale (Eric Savin), Rebecca Hall (Maya Hansen), Jon Favreau (Happy Hogan)', 'https://www.youtube.com/watch?v=wnEr73Rq3Ac', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-iron-man-3/3s4Ihq7P2c6e', 'iron_man_3.jpg', 7),
(8, 'Thor : Le Mondes des Ténèbres', 'Thor : Le Monde des ténèbres nous entraîne dans les nouvelles aventures de Thor, le puissant Avenger, qui lutte pour sauver la Terre et les neuf mondes d’un mystérieux ennemi qui convoite l’univers tout entier… Après les films Marvel Thor et Avengers, Thor se bat pour restaurer l’ordre dans le cosmos, mais une ancienne race, sous la conduite du terrible Malekith, un être assoiffé de vengeance, revient pour répandre les ténèbres. Confronté à un ennemi que même Odin et Asgard ne peuvent contrer, Thor doit s’engager dans son aventure la plus dangereuse et la plus personnelle, au cours de laquelle il va devoir s’allier au traître Loki pour sauver non seulement son peuple et ceux qui lui sont chers, mais aussi l’univers lui-même.', '30 octobre 2013', '1h52', 'Chris Hemsworth (Thor), Natalie Portman (Jane Foster), Tom Hiddleston (Loki), Stellan Skarsgård (Selvig), Idris Elba (Heimdall), Christopher Eccleston (Malekith), Adewale Akinnuoye-Agbaje (Algrim / Kurse), Kat Dennings (Darcy Lewis)', 'https://www.youtube.com/watch?v=uq4OFEwflnI', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-thor-le-monde-des-tenebres/ZHk7aM5xTbW7', 'thor_le_monde_des_tenebres.jpg', 8),
(9, 'Captain America : Le Soldat de l\'hiver', 'Après les événements cataclysmiques de New York de The Avengers, Steve Rogers aka Captain America vit tranquillement à Washington, D.C. et essaye de s\'adapter au monde moderne. Mais quand un collègue du S.H.I.E.L.D. est attaqué, Steve se retrouve impliqué dans un réseau d\'intrigues qui met le monde en danger. S\'associant à Black Widow, Captain America lutte pour dénoncer une conspiration grandissante, tout en repoussant des tueurs professionnels envoyés pour le faire taire. Quand l\'étendue du plan maléfique est révélée, Captain America et Black Widow sollicite l\'aide d\'un nouvel allié, le Faucon. Cependant, ils se retrouvent bientôt face à un inattendu et redoutable ennemi - le Soldat de l\'Hiver.', '26 mars 2014', '2h16', 'Chris Evans (Steve Rogers / Captain America), Scarlett Johansson (Natasha Romanoff / Black Widow), Anthony Mackie (Sam Wilson / Falcon), Samuel L. Jackson (Nick Fury), Cobie Smulders (Maria Hill), Frank Grillo (Brock Rumlow), Emily VanCamp (Agent 13), Hayley Atwell (Peggy Carter)', 'https://www.youtube.com/watch?v=6mQWmUwxZjI', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-captain-america-le-soldat-de-lhiver/TVme5whcowSy', 'captain_america_le_soldat_de_l_hiver.jpg', 9),
(10, 'Les Gardiens de la Galaxie', 'Peter Quill est un aventurier traqué par tous les chasseurs de primes pour avoir volé un mystérieux globe convoité par le puissant Ronan, dont les agissements menacent l’univers tout entier. Lorsqu’il découvre le véritable pouvoir de ce globe et la menace qui pèse sur la galaxie, il conclut une alliance fragile avec quatre aliens disparates : Rocket, un raton laveur fin tireur, Groot, un humanoïde semblable à un arbre, l’énigmatique et mortelle Gamora, et Drax le Destructeur, qui ne rêve que de vengeance. En les ralliant à sa cause, il les convainc de livrer un ultime combat aussi désespéré soit-il pour sauver ce qui peut encore l’être …', '13 août 2014', '2h01', 'Chris Pratt (Peter Quill / Star-Lord), Zoe Saldana (Gamora), Dave Bautista (Drax le Destructeur), Lee Pace (Ronan l\'Accusateur), Benicio Del Toro (Le Collectionneur), Karen Gillan (Nebula), Glenn Close (Nova Prime), John C. Reilly (Rhomann Dey)', 'https://www.youtube.com/watch?v=HbB1LC_QyK0', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-les-gardiens-de-la-galaxie/1S4WM9h3KRR6', 'gardiens_de_la_galaxie.jpg', 10),
(11, 'Avengers : L\'Ère d\'Ultron', 'Alors que Tony Stark tente de relancer un programme de maintien de la paix jusque-là suspendu, les choses tournent mal et les super-héros Iron Man, Captain America, Thor, Hulk, Black Widow et Hawkeye vont devoir à nouveau unir leurs forces pour combattre le plus puissant de leurs adversaires : le terrible Ultron, un être technologique terrifiant qui s’est juré d’éradiquer l’espèce humaine.\r\nAfin d’empêcher celui-ci d’accomplir ses sombres desseins, des alliances inattendues se scellent, les entraînant dans une incroyable aventure et une haletante course contre le temps…', '22 avril 2015', '2h21', 'Robert Downey Jr. (Tony Stark / Iron Man), Chris Evans (Steve Rogers / Captain America), Mark Ruffalo (Bruce Banner / Hulk), Chris Hemsworth (Thor), Samuel L. Jackson (Nick Fury), Scarlett Johansson (Natasha Romanoff / Black Widow), Jeremy Renner (Clint Barton / Hawkeye), Elizabeth Olsen (Wanda Maximoff / Scarlett Witch)', 'https://www.youtube.com/watch?v=ANbZ-zJ7Ig8', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-avengers-lere-dultron/76IUxY0rNHzt', 'avengers_l_ere_d_ultron.jpg', 11),
(12, 'Ant-Man', 'Scott Lang, cambrioleur de haut vol, va devoir apprendre à se comporter en héros et aider son mentor, le Dr Hank Pym, à protéger le secret de son spectaculaire costume d’Ant-Man, afin d’affronter une effroyable menace…', '14 juillet 2015', '1h57', 'Paul Rudd (Scott Lang / Ant-Man), Evangeline Lilly (Hope Van Dyne), Corey Stoll (Darren Cross / Yellowjacket), Michael Douglas (Dr. Hank Pym), Bobby Cannavale (Paxton), Michael Peña (Luis), T.I. (Dave), Wood Harris (Gale)', 'https://www.youtube.com/watch?v=_mWjqYXA59E', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-ant-man/5c92KVf1zgUX', 'ant_man.jpg', 12),
(13, 'Captain America : Civil War', 'Steve Rogers est désormais à la tête des Avengers, dont la mission est de protéger l\'humanité. À la suite d\'une de leurs interventions qui a causé d\'importants dégâts collatéraux, le gouvernement décide de mettre en place un organisme de commandement et de supervision. \r\nCette nouvelle donne provoque une scission au sein de l\'équipe : Steve Rogers reste attaché à sa liberté de s\'engager sans ingérence gouvernementale, tandis que d\'autres se rangent derrière Tony Stark, qui contre toute attente, décide de se soumettre au gouvernement...', '27 avril 2016', '2h28', 'Chris Evans (Steve Rogers / Captain America), Robert Downey Jr. (Tony Stark / Iron man), Scarlett Johansson (Natasha Romanoff / Black Widow), Elizabeth Olsen (Wanda Maximoff / Scarlett Witch), Sebastian Stan (Bucky Barnes / Le Soldat de l\'hiver), Anthony Mackie (Sam Wilson / Falcon), Don Cheadle (Colonel James Rhodes / War Machine), Jeremy Renner (Clint Barton / Hawkeye), Chadwick Boseman (T\'Challa / Black Panther), Paul Bettany (Vision), Paul Rudd (Scott Lang / Ant-man), Daniel Brühl (Zemo), Tom Holland (Peter Parker / Spiderman)', 'https://www.youtube.com/watch?v=fuIIGDjEj8Q', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-captain-america-civil-war/4ovfyKnnIBCg', 'captain_america_civil_war.jpg', 13),
(14, 'Doctor Strange', 'Dans Docteur Strange des studios Marvel, un célèbre chirurgien perd l’usage de ses mains. En recherchant un traitement, il trouve une magie puissante dans un endroit mystérieux nommé Kamar-Taj. Il apprend que les forces du mal cherchent à détruire notre réalité.', '26 octobre 2016', '1h55', 'Benedict Cumberbatch (Dr. Stephen Strange), Chiwetel Ejiofor (Mordo), Tilda Swinton (L\'Ancient), Rachel McAdams (Christine Palmer), Mads Mikkelsen (Kaecilius), Scott Adkins (Lucian / Strong Zealot), Amy Landecker (Dr. Bruner), Benedict Wong (Wong)', 'https://www.youtube.com/watch?v=r849NGQZqbA', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-doctor-strange/4GgMJ1aHKHA2', 'doctor_strange.jpg', 14),
(16, 'Spider-Man : Homecoming', 'Après ses spectaculaires débuts dans Captain America : Civil War, le jeune Peter Parker découvre peu à peu sa nouvelle identité, celle de Spider-Man, le super-héros lanceur de toile. Galvanisé par son expérience avec les Avengers, Peter rentre chez lui auprès de sa tante May, sous l’œil attentif de son nouveau mentor, Tony Stark. Il s’efforce de reprendre sa vie d’avant, mais au fond de lui, Peter rêve de se prouver qu’il est plus que le sympathique super héros du quartier. L’apparition d’un nouvel ennemi, le Vautour, va mettre en danger tout ce qui compte pour lui...', '12 juillet 2017', '2h13', 'Tom Holland (Peter Parker / Spider-Man), Michael Keaton (Adrian Toomes / Le Vautour), Robert Downey Jr. (Tony Stark / Iron Man), Zendaya (Michelle Jones), Marisa Tomei (Tante May), Martin Starr (Mr. Harrington), Jacob Bataillon (Ned Leeds), Laura Harrier (Liz Allan), Jon Favreau (Happy Hogan)', 'https://www.youtube.com/watch?v=BmbfD7F3BoQ', '', '', 'spiderman_homecoming.jpg', 16),
(17, 'Thor : Ragnarok', 'Privé de son puissant marteau, Thor est retenu prisonnier sur une lointaine planète aux confins de l’univers. Pour sauver Asgard, il va devoir lutter contre le temps afin d’empêcher l’impitoyable Hela d’accomplir le Ragnarök – la destruction de son monde et la fin de la civilisation asgardienne. Mais pour y parvenir, il va d’abord devoir mener un combat titanesque de gladiateurs contre celui qui était autrefois son allié au sein des Avengers : l’incroyable Hulk…', '25 octobre 2017', '2h11', 'Chris Hemsworth (Thor), Tom Hiddleston (Loki), Cate Blanchett (Hela), Idris Elba (Heimdall), Jeff Goldblum (Le Grand Maitre), Tessa Thompson (Valkyrie), Kari Urban (Skurge), Mark Ruffalo (Bruce Banner / Hulk), Anthony Hopkins (Odin), Benedict Cumberbatch (Dr. Stephen Strange)', 'https://www.youtube.com/watch?v=RtG6scyBIeM', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-thor-ragnarok/3XqAT8UV8ojS', 'thor_ragnarok.jpg', 17),
(18, 'Black Panther', 'Après les événements qui se sont déroulés dans Captain America : Civil War, T’Challa revient chez lui prendre sa place sur le trône du Wakanda, une nation africaine technologiquement très avancée. Mais lorsqu’un vieil ennemi resurgit, le courage de T’Challa est mis à rude épreuve, aussi bien en tant que souverain qu’en tant que Black Panther. Il se retrouve entraîné dans un conflit qui menace non seulement le destin du Wakanda, mais celui du monde entier…', '14 février 2018', '2h15', 'Chadwick Boseman (T\'Challa / Black Panther), Michael B. Jordan (Erik Killmonger), Lupita Nyong\'o (Nakia), Danai Guria (Okoye), Letitia Wright (Shuri), Martin Freeman (Everett K. Ross), Daniel Kaluuya (W\'Kabi), Winston Duke (M\'Baku)', 'https://www.youtube.com/watch?v=8RQpN5kQslw', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-black-panther/1GuXuYPj99Ke', 'black_panther.jpg', 18),
(19, 'Avengers : Infinity War', 'Les Avengers et leurs alliés devront être prêts à tout sacrifier pour neutraliser le redoutable Thanos avant que son attaque éclair ne conduise à la destruction complète de l’univers.', '25 avril 2018', '2h36', 'Robert Downey Jr. (Tony Stark / Iron Man), Chris Hemsworth (Thor), Mark Ruffalo (Bruce Banner / Hulk), Chris Evans (Steve Rogers / Captain America), Scarlett Johansson (Natasha Romannoff / Black Widow), Don Cheadle (James Rhodes / War Machine), Benedict Cumberbatch (Doctor Strange), Tom Holland (Peter Parker / Spider-Man), Chadwick Boseman (T\'Challa / Black Panther), Zoe Saldana (Gamora), Karen Gillan (Nebula), Tom Hiddleston (Loki), Paul Bettany (Vision), Elizabeth Olsen (Wanda Maximoff / Scarlett Witch), Anthony Mackie (Sam Wilson / Falcon), Sebastian Stan (Bucky Barnes / Le Soldat de l\'hiver), Josh Brolin (Thanos), Chris Pratt (Peter Quill / Star-Lord)', 'https://www.youtube.com/watch?v=eIWs2IUr3Vs', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-avengers-infinity-war/1WEuZ7H6y39v', 'avengers_infinity_war.jpeg', 19),
(20, 'Ant-Man et la Guêpe', 'Après les événements survenus dans Captain America : Civil War, Scott Lang a bien du mal à concilier sa vie de super-héros et ses responsabilités de père. Mais ses réflexions sur les conséquences de ses choix tournent court lorsque Hope van Dyne et le Dr Hank Pym lui confient une nouvelle mission urgente… Scott va devoir renfiler son costume et apprendre à se battre aux côtés de La Guêpe afin de faire la lumière sur des secrets enfouis de longue date…', '18 juillet 2018', '1h58', 'Paul Rudd (Scott Lang / Ant-Man), Evangeline Lilly (Hope Van Dyne / La Guêpe), Michael Peña (Luis), Walton Goggins (Sonny Burch), Michael Douglas (Dr. Hank Pym), Hannah John-Kamen (Ava / Ghost), Laurence Fishburne (Dr. Bill Foster), Bobby Cannavale (Paxton)', 'https://www.youtube.com/watch?v=A5jzpMR6rv4', 'Disney+', '', 'ant_man_et_la_guepe.jpg', 20),
(21, 'Captain Marvel', 'Captain Marvel raconte l’histoire de Carol Danvers qui va devenir l’une des super-héroïnes les plus puissantes de l’univers lorsque la Terre se révèle l’enjeu d’une guerre galactique entre deux races extraterrestres.', '06 mars 2019', '2h04', 'Brie Larson (Carol Danvers / Captain Marvel), Samuel L. Jackson (Nick Fury), Jude Law (Yon-Rogg / Starforce Commander), Clark Gregg (Phil Coulson), Ben Mendelsohn (Talos / Keller), Gemma Chan (Minn-Erva), Lee Pace (Ronan l\'Accusateur), Annette Bening (Supreme Intelligence)', 'https://www.youtube.com/watch?v=rndLWLmwgeA', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-captain-marvel/38xJGlLAQy9a', 'captain_marvel.jpeg', 21),
(22, 'Avengers : Endgame', 'Thanos ayant anéanti la moitié de l’univers, les Avengers restants resserrent les rangs dans ce vingt-deuxième film des Studios Marvel, grande conclusion d’un des chapitres de l’Univers Cinématographique Marvel.', '24 avril 2019', '3h01', 'Robert Downey Jr. (Tony Stark / Iron Man), Chris Evans (Steve Rogers / Captain America), Mark Ruffalo (Bruce Banner / Hulk), Chris Hemsworth (Thor), Scarlett Johansson (Natasha Romannoff / Black Widow), Jeremy Renner (Clint Barton / Hawkeye), Brie Larson (Carol Danvers / Captain Marvel), Paul Rudd (Scott Lang / Ant-Man), Josh Brolin (Thanos), Don Cheadle (James Rhodes / War Machine), Karen Gillan (Nebula), Gwyneth Paltrow (Pepper Potts), Jon Favreau (Happy Hogan), Chris Pratt (Peter Quill / Star-Lord), Elizabeth Olsen (Wanda Maximoff / Scarlett Witch)', 'https://www.youtube.com/watch?v=wV-Q0o2OQjQ', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/avengers-endgame/aRbVJUb2h2Rf', 'avengers_endgame.jpg', 22),
(23, 'Spider-Man : Far From Home', 'L\'araignée sympa du quartier décide de rejoindre ses meilleurs amis Ned, MJ, et le reste de la bande pour des vacances en Europe. Cependant, le projet de Peter de laisser son costume de super-héros derrière lui pendant quelques semaines est rapidement compromis quand il accepte à contrecoeur d\'aider Nick Fury à découvrir le mystère de plusieurs attaques de créatures, qui ravagent le continent !', '03 juillet 2019', '2h10', 'Tom Holland (Peter Parker / Spider-Man), Jake Gyllenhaal (Quentin Beck / Mysterio), Zendaya (MJ), Samuel L. Jackson (Nick Fury), Jon Favreau (Happy Hogan), Marisa Tomei (Tante May), Cobie Smulders (Maria Hill), Jacob Batalon (Ned)', 'https://www.youtube.com/watch?v=inFIHmGshgk', 'Netflix', 'https://www.netflix.com/title/81055822', 'spiderman_far_from_home.jpeg', 23),
(24, 'Black Widow', 'Natasha Romanoff, alias Black Widow, voit resurgir la part la plus sombre de son passé pour faire face à une redoutable conspiration liée à sa vie d’autrefois. Poursuivie par une force qui ne reculera devant rien pour l’abattre, Natasha doit renouer avec ses activités d’espionne et avec des liens qui furent brisés, bien avant qu’elle ne rejoigne les Avengers.', '07 juillet 2021', '2h13', 'Scarlett Johansson (Natasha Romanoff / Black Widow), Florence Pugh (Yelena Belova), David Harbour (Alexei / Red Guardian), Rachel Weisz (Melina Vostokoff), O.T. Fagbenle (Mason), William Hurt (Thaddeus Ross), Ray Winstone (?), Michelle Lee (?)', 'https://www.youtube.com/watch?v=4l99M0zOEaA', '', '', 'black_widow.jpg', 24),
(26, 'Shang-Chi et la Légende des Dix Anneaux', 'Shang-Chi va devoir affronter un passé qu’il pensait avoir laissé derrière lui lorsqu’il est pris dans la toile de la mystérieuse organisation des dix anneaux.', '01 septembre 2021', '2h00', 'Simu Liu (Shang-Chi), Tony Leung Chiu Wai (Le Mandarin), Awkwfina (Katy), Michelle Yeoh (Jiang Nan), Fala Chen (?), Alina Zhang (?), Florian Munteanu (?), Ronny Chieng (?)', 'https://www.youtube.com/watch?v=YQOsxQUxMWk', '', '', 'shang_chi_et_la_legende_des_dix_anneaux.jpg', 25),
(27, 'Les Gardiens de la Galaxie Vol. 2', 'Musicalement accompagné de la « Awesome Mixtape n°2 » (la musique qu\'écoute Star-Lord dans le film), Les Gardiens de la Galaxie Vol. 2 poursuit les aventures de l\'équipe alors qu\'elle traverse les confins du cosmos. Les gardiens doivent combattre pour rester unis alors qu\'ils découvrent les mystères de la filiation de Peter Quill. Les vieux ennemis vont devenir de nouveaux alliés et des personnages bien connus des fans de comics vont venir aider nos héros et continuer à étendre l\'univers Marvel.', '26 avril 2017', '2h16', 'Chris Pratt (Peter Quill / Star-Lord), Zoe Saldana (Gamora), Dave Bautista (Drax le Destructeur), Michael Rooker (Yondu), Karen Gillan (Nebula), Kurt Russell (Ego), Sylvester Stallone (Stakar Ogord), Pom Klementieff (Mantis)', 'https://www.youtube.com/watch?v=qe6EjVKba9Q', 'Disney+', 'https://www.disneyplus.com/fr-fr/movies/marvel-studios-les-gardiens-de-la-galaxie-vol-2/ZdRX4mMbp1gM', 'gardiens_de_la_galaxie_2.jpg', 15),
(28, 'Les Éternels', 'Les Éternels, une ancienne race issue des manipulations des Célestes sur le genre humain, vivent cachés sur Terre au milieu des humains depuis plusieurs milliers d\'années. Après les évènements d\'Avengers Endgame, une tragédie imprévue les oblige à sortir de l\'ombre pour s\'assembler à nouveau face à l\'ennemi le plus ancien de la race humaine : les Déviants.', '03 novembre 2021', '2h00', 'Angelina Jolie (Thena), Kit Harington (Dane Whitman / Black Knight), Richard Madden (Ikaris), Gemma Chan (Sersi), Kumail Nanjiani (Kingo), Lauren Ridloff (Makkari), Brian Tyree Henry (Phastos), Salma Hayek (Ajak), Lia McHugh (Sprite), Dong-Seok Ma (Gilgamesh), Barry Keoghan (Druig)', 'https://www.youtube.com/watch?v=zfhMlsZW7hg', '', '', 'les_eternels.jpg', 26);

-- --------------------------------------------------------

--
-- Structure de la table `Messages`
--

CREATE TABLE `Messages` (
  `messageId` int(11) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `auteurId` int(11) NOT NULL,
  `destinataireId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

CREATE TABLE `Users` (
  `userId` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(256) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `droits` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Users`
--

INSERT INTO `Users` (`userId`, `username`, `password`, `nom`, `prenom`, `droits`) VALUES
(1, 'admin', '74dad9e7c8500a7fd750ba478263f5956dc3dee9', 'Admin', 'Admin', 1),
(2, 'popo', '74dad9e7c8500a7fd750ba478263f5956dc3dee9', 'Paulin', 'Fitdevoie', 0),
(3, 'toto', '74dad9e7c8500a7fd750ba478263f5956dc3dee9', 'Toto', 'Toto', 0),
(11, 'LouisFitd', '74dad9e7c8500a7fd750ba478263f5956dc3dee9', 'Fitdevoie', 'Louis', 0),
(12, 'titi', '74dad9e7c8500a7fd750ba478263f5956dc3dee9', 'Titi', 'Titi', 0),
(14, 'fafa', '74dad9e7c8500a7fd750ba478263f5956dc3dee9', 'Fiorini', 'Fabienne', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Commentaires`
--
ALTER TABLE `Commentaires`
  ADD PRIMARY KEY (`commentId`),
  ADD KEY `auteurId` (`auteurId`),
  ADD KEY `filmId` (`filmId`);

--
-- Index pour la table `Films`
--
ALTER TABLE `Films`
  ADD PRIMARY KEY (`filmId`);

--
-- Index pour la table `Messages`
--
ALTER TABLE `Messages`
  ADD PRIMARY KEY (`messageId`),
  ADD KEY `auteurId` (`auteurId`),
  ADD KEY `destinataireId` (`destinataireId`);

--
-- Index pour la table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Commentaires`
--
ALTER TABLE `Commentaires`
  MODIFY `commentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `Films`
--
ALTER TABLE `Films`
  MODIFY `filmId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `Messages`
--
ALTER TABLE `Messages`
  MODIFY `messageId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Users`
--
ALTER TABLE `Users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Commentaires`
--
ALTER TABLE `Commentaires`
  ADD CONSTRAINT `commentaires_ibfk_1` FOREIGN KEY (`auteurId`) REFERENCES `Users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commentaires_ibfk_2` FOREIGN KEY (`filmId`) REFERENCES `Films` (`filmId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Messages`
--
ALTER TABLE `Messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`auteurId`) REFERENCES `Users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`destinataireId`) REFERENCES `Users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
