-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Client :  mysql5-61.90
-- Généré le :  Mar 29 Août 2017 à 09:59
-- Version du serveur :  5.5.55-0+deb7u1-log
-- Version de PHP :  5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `thisisga_mod`
--

-- --------------------------------------------------------

--
-- Structure de la table `tr_game`
--

CREATE TABLE IF NOT EXISTS `tr_game` (
  `id_game` int(10) unsigned NOT NULL,
  `game` varchar(255) DEFAULT NULL,
  `url_language` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tr_language`
--

CREATE TABLE IF NOT EXISTS `tr_language` (
  `id_language` int(10) unsigned NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `language` varchar(255) NOT NULL,
  `english` varchar(255) NOT NULL,
  `representation` varchar(255) NOT NULL,
  `parent_id_language` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tr_test`
--

CREATE TABLE IF NOT EXISTS `tr_test` (
  `id_translate` int(11) DEFAULT NULL,
  `id_language` int(11) NOT NULL,
  `id_game` int(11) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `translate` text NOT NULL,
  `id_translator` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `deactivated` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tr_translate`
--

CREATE TABLE IF NOT EXISTS `tr_translate` (
  `id_translate` int(10) unsigned NOT NULL,
  `id_language` int(10) unsigned DEFAULT NULL,
  `id_game` int(10) unsigned DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  `translate` text,
  `id_translator` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `deactivated` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tr_translator`
--

CREATE TABLE IF NOT EXISTS `tr_translator` (
  `id_translator` int(10) unsigned NOT NULL,
  `id_language` int(10) unsigned NOT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `tr_game`
--
ALTER TABLE `tr_game`
  ADD PRIMARY KEY (`id_game`);

--
-- Index pour la table `tr_language`
--
ALTER TABLE `tr_language`
  ADD PRIMARY KEY (`id_language`);

--
-- Index pour la table `tr_translate`
--
ALTER TABLE `tr_translate`
  ADD PRIMARY KEY (`id_translate`);

--
-- Index pour la table `tr_translator`
--
ALTER TABLE `tr_translator`
  ADD PRIMARY KEY (`id_translator`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `tr_game`
--
ALTER TABLE `tr_game`
  MODIFY `id_game` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tr_language`
--
ALTER TABLE `tr_language`
  MODIFY `id_language` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tr_translate`
--
ALTER TABLE `tr_translate`
  MODIFY `id_translate` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tr_translator`
--
ALTER TABLE `tr_translator`
  MODIFY `id_translator` int(10) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
