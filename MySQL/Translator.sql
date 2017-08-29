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

INSERT INTO `tr_translator` (`id_translator`, `id_language`, `nickname`, `password`, `email`) VALUES
(0, 1, 'admin', 'root2017', 'contact@benoitfreslon.com');

INSERT INTO `tr_language` (`id_language`, `code`, `language`, `english`, `representation`, `parent_id_language`) VALUES
(1, 'en', 'English', 'English', 'en_US', NULL),
(2, 'fr', 'FranÃ§ais', 'French', 'fr_FR', NULL),
(3, 'de', 'Deutsch', 'German', 'de_DE', NULL),
(4, 'es', 'EspaÃ±ol', 'Spanish', 'es_ES', NULL),
(5, 'it', 'Italiano', 'Italian', 'it_IT', NULL),
(6, 'ru', 'PÑƒÑÑÐºÐ¸Ð¹ ÑÐ·Ñ‹Ðº', 'Russian', 'ru_RU', NULL),
(8, 'pl', 'Polish', 'Polish', 'pl_PL', NULL),
(10, 'pt', 'PortuguÃªs', 'Portuguese', 'pt_PT', NULL),
(11, 'cs', 'ÄeÅ¡tina', 'Czech', 'cs_CZ', NULL),
(12, 'da', 'Dansk', 'Danish', 'da_DK', NULL),
(13, 'nl', 'Nederlands', 'Dutch', 'nl_NL', NULL),
(14, 'fi', 'Suomi', 'Finnish', 'fi_FI', NULL),
(15, 'hu', 'Magyar', 'Hungarian', 'hu_HU', NULL),
(16, 'ja', 'æ—¥æœ¬èªž', 'Japanese', 'ja_JP', NULL),
(18, 'ko', 'í•œêµ­ì–´', 'Korean', 'ko_KR', NULL),
(19, 'no', 'Norsk', 'Norwegian', 'nb_NO', NULL),
(20, 'zh-CN', 'ç®€ä½“ä¸­æ–‡', 'Chinese', 'zh_CN', NULL),
(21, 'sv', 'Svenska', 'Swedish', 'sv_SE', NULL),
(22, 'zh-TW', 'ä¸­æ–‡', 'Traditional Chinese', 'zh_TW', 20),
(23, 'tr', 'TÃ¼rkÃ§e', 'Turkish', 'tr_TR', NULL),
(24, 'bg', 'Ð‘ÑŠÐ»Ð³Ð°Ñ€ÑÐºÐ¸ ÐµÐ·Ð¸Ðº', 'Bulgarian', 'bg_BG', NULL),
(25, 'he', '×¢×‘×¨×™×ª', 'Hebrew', 'he_IL', NULL),
(26, 'is', 'Islenska', 'Icelandic', 'is_IS', NULL),
(27, 'gre', 'Î•Î»Î»Î·Î½Î¹ÎºÎ¬', 'Greek', 'el_GR', NULL),
(28, 'sl', 'slovenÅ¡Äina', 'Slovenian', 'sl_SI', NULL),
(29, 'lt', 'LietuviÅ³ kalba', 'Lithuanian', 'lt_LT', NULL),
(31, 'hr', 'Hrvatski jezik', 'Croatian', 'hr_HR', NULL),
(33, 'ro', 'RomÃ¢nÄƒ', 'Romanian', 'ro_RO', NULL),
(34, 'ar', 'Ø§Ù„Ø¹Ø±Ø¨ÙŠØ©', 'Arabic', 'ar_AR', NULL),
(35, 'ca', 'CatalÃ ', 'Catalan', 'ca_ES', NULL),
(37, 'ukr', 'ÑƒÐºÑ€Ð°Ñ—Ð½ÑÑŒÐºÐ° Ð¼Ð¾Ð²Ð°', 'Ukrainian', 'uk_UA', NULL),
(38, 'sr', 'ÑÑ€Ð¿ÑÐºÐ¸', 'Serbian', 'sr_RS', NULL),
(40, 'pt-BR', 'Portuguese Brazil', 'Portuguese Brazil', 'pt_BR', 10),
(42, 'vi', 'Tiáº¿ng Viá»‡t', 'Vietnamese', 'vi_VI', NULL);
