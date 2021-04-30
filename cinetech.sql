-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 30 avr. 2021 à 08:56
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `cinetech`
--

-- --------------------------------------------------------

--
-- Structure de la table `movie_commentary`
--

DROP TABLE IF EXISTS `movie_commentary`;
CREATE TABLE IF NOT EXISTS `movie_commentary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_program` int(11) NOT NULL,
  `type_program` varchar(50) NOT NULL,
  `commentary` varchar(1000) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `movie_commentary`
--

INSERT INTO `movie_commentary` (`id`, `id_user`, `id_program`, `type_program`, `commentary`, `date`) VALUES
(1, 1, 550, 'movie', 'Bjr la miff', '2018-09-24 00:00:00'),
(2, 1, 550, 'movie', 'slt c cool', '2018-09-24 00:00:00'),
(3, 2, 550, 'movie', 'j\'ai bcp de choses a dire sur ce film', '2018-09-24 00:00:00'),
(4, 7, 550, 'movie', 'azerty + qwerty', '2021-04-29 16:19:00'),
(5, 7, 550, 'movie', 'nouveau commentaire', '2021-04-30 08:45:00'),
(6, 7, 550, 'movie', 'test', '2021-04-30 08:51:00');

-- --------------------------------------------------------

--
-- Structure de la table `movie_commentary_reply`
--

DROP TABLE IF EXISTS `movie_commentary_reply`;
CREATE TABLE IF NOT EXISTS `movie_commentary_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_commentary` int(11) NOT NULL,
  `id_program` int(11) NOT NULL,
  `type_program` varchar(50) NOT NULL,
  `reply` varchar(1000) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `movie_commentary_reply`
--

INSERT INTO `movie_commentary_reply` (`id`, `id_user`, `id_commentary`, `id_program`, `type_program`, `reply`, `date`) VALUES
(1, 2, 1, 550, 'movie', 'Wa con tyer serieux de penser ça', '2018-09-24 00:00:00'),
(2, 3, 2, 550, 'movie', 'grrrrrrrrrrrrrrrrrrrrr', '2018-09-25 00:00:00'),
(3, 2, 1, 550, 'movie', 'Wa con2', '2018-09-24 00:00:00'),
(4, 7, 3, 550, 'movie', 'nul nul nul', '2021-04-29 16:12:00'),
(5, 7, 3, 550, 'movie', 'bien bien bien', '2021-04-29 16:12:00'),
(6, 7, 2, 550, 'movie', 'nononon', '2021-04-29 16:14:00'),
(7, 7, 0, 550, 'movie', 'ouille ouille ouille', '2021-04-29 16:15:00'),
(8, 7, 0, 550, 'movie', 'Aie aie aie', '2021-04-29 16:16:00'),
(9, 7, 4, 550, 'movie', 'je veux commenter', '2021-04-30 08:11:00'),
(10, 7, 5, 550, 'movie', 'reponse', '2021-04-30 08:45:00');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_right` int(11) NOT NULL,
  `login` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(800) NOT NULL,
  `api_token` varchar(300) DEFAULT NULL,
  `api_session` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `id_right`, `login`, `email`, `password`, `image`, `api_token`, `api_session`) VALUES
(1, 1, 'JambonBurst', '$2y$10$27Gc0fbCEsyhRtsDTuFY0O/y6PUMwqfJ1m9wG53vWWJA7u9ZceCL6', 'okokokokok@ok.fr', 'https://static.wixstatic.com/media/109580_c3da31ed06484c7e8e225c46beecd507~mv2.png/v1/fill/w_220,h_220,al_c,q_85,usm_0.66_1.00_0.01/avatar%20neutre.webp', NULL, NULL),
(2, 1, 'Batman', '$2y$10$H6.NUVpnhTYeCM/hRRNkK.rrB5sEppSUKrGZ6oHtBJAjQUTt5oP66', 'monviermtn@ok.fr', 'https://static.wixstatic.com/media/109580_c3da31ed06484c7e8e225c46beecd507~mv2.png/v1/fill/w_220,h_220,al_c,q_85,usm_0.66_1.00_0.01/avatar%20neutre.webp', NULL, NULL),
(3, 1, 'azertyxxx', '$2y$10$bmCxWIhsu1mC1/.zBGAaJ.gJ5TojLMbZmcoygWEzaDxIEP8khYqxG', 'jojojojoj@ok.fr', 'https://static.wixstatic.com/media/109580_c3da31ed06484c7e8e225c46beecd507~mv2.png/v1/fill/w_220,h_220,al_c,q_85,usm_0.66_1.00_0.01/avatar%20neutre.webp', NULL, NULL),
(4, 1, 'Kenshi', '$2y$10$xufCi8EwEL73ONz02LK.5eshN4geR4d1orXaRKu1XlO5n6kxRz.ju', 'azertyuiop@ok.fr', 'https://static.wixstatic.com/media/109580_c3da31ed06484c7e8e225c46beecd507~mv2.png/v1/fill/w_220,h_220,al_c,q_85,usm_0.66_1.00_0.01/avatar%20neutre.webp', NULL, NULL),
(5, 1, 'Wnxbcv', '$2y$10$zqFLsGB9NjcVBInky7cmVuwWA4xew7lk/1y.R3JXQ4SpJurX9zCq6', 'oooooook@ok.fr', 'https://static.wixstatic.com/media/109580_c3da31ed06484c7e8e225c46beecd507~mv2.png/v1/fill/w_220,h_220,al_c,q_85,usm_0.66_1.00_0.01/avatar%20neutre.webp', NULL, NULL),
(6, 1, 'jojox', 'jojox@ok.fr', '$2y$10$SE82p8ngrc8bYLVMy0Dia.Rwu9OGgO1gpfGPXWW8zIwBd3K4Jx8su', 'https://medias.publidata.io/production/images/images/000/021/103/cover_medium/fight_club_affiche.jpg?1498724104', NULL, NULL),
(7, 1, 'HARDJOJOJ', 'HARDJOJOJ@ok.fr', '$2y$10$sy8GWjQoFXXA3xQ9JlQLeeVXdIZOqw8zVban.oj7JeZTJEzyybjAW', 'https://i.pinimg.com/originals/92/b7/b5/92b7b53921cbd267f531f8dbd731c8be.png', 'aba9b98784e7e615326277f4d920bae7bd388c65', '064abc14354e7f89a96e7bc01ab187ed565bbf97');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
