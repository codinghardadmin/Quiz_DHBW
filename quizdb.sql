-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 03. Dez 2019 um 16:57
-- Server-Version: 10.1.37-MariaDB
-- PHP-Version: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `quizdb`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fragen`
--

CREATE TABLE `fragen` (
  `id` int(11) NOT NULL,
  `frage` text NOT NULL,
  `antwort1` text NOT NULL,
  `antwort2` text NOT NULL,
  `antwort3` text NOT NULL,
  `antwort4` text NOT NULL,
  `schwer` int(11) NOT NULL,
  `thema` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fragen`
--

INSERT INTO `fragen` (`id`, `frage`, `antwort1`, `antwort2`, `antwort3`, `antwort4`, `schwer`, `thema`) VALUES
(11, 'Anzahl der Beine einer Katze', '4', '1', '5', '9', 12, 'Biologie'),
(12, 'Wie viele Augen hat ein Hund?', '2', '10', '20', '40', 1, 'Biologie'),
(13, 'Welches Element ist Bestand der organischen Chemie?', 'Kohlenstoff', 'Sauaersoff', '', '', 0, 'Chemie'),
(14, 'Welcher Teil des Prozessors ist fÃ¼r die Berechnung zustÃ¤ndig?', 'ALU', 'Register', 'Program Counter', 'ROM', 4, 'Informatik'),
(16, 'ddasd', 'ssdfvcf', 'dvfvfg', 'vfgvrg', 'btrgbt', 8, 'Physik'),
(18, 'T1', 'X', 'Y', 'Z', 'A', 2, 'Sonstiges'),
(19, 'Wie viele HÃ¤nde hat ein Mensch?', '2', '5', '40', '123', 543, 'Biologie'),
(20, 'Was hat jede Zelle?', 'Einen Kern', 'Einen Schuh', 'Ein paar Haare', 'Eine Nase', 3, 'Biologie'),
(21, 'Wie viele Beine hat ein Mensch?', '2', '54', '876', '578', 1, 'Biologie'),
(22, 'Was haben BÃ¤ume?', 'BlÃ¤tter', 'Nougat', 'StÃ¼hle', 'Trinken', 3, 'Biologie');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `quiz`
--

CREATE TABLE `quiz` (
  `id` int(11) NOT NULL,
  `anzahl` int(11) NOT NULL,
  `schwer` int(11) NOT NULL,
  `thema` text NOT NULL,
  `aktuell` int(11) NOT NULL,
  `richtig` int(11) NOT NULL,
  `lsg` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `quiz`
--

INSERT INTO `quiz` (`id`, `anzahl`, `schwer`, `thema`, `aktuell`, `richtig`, `lsg`) VALUES
(35, 3, 20, 'Biologie', 4, 1, 1),
(36, 3, 200, 'Biologie', 4, 3, 2),
(37, 4, 10, 'Biologie', 5, 3, 0),
(38, 5, 200, 'Biologie', 7, 6, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `quizfragen`
--

CREATE TABLE `quizfragen` (
  `quizId` int(11) NOT NULL,
  `frageId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `quizfragen`
--

INSERT INTO `quizfragen` (`quizId`, `frageId`) VALUES
(35, 11),
(35, 12),
(35, 20),
(36, 11),
(36, 12),
(36, 20),
(37, 12),
(37, 20),
(37, 21),
(37, 22),
(38, 11),
(38, 12),
(38, 20),
(38, 21),
(38, 22);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `fragen`
--
ALTER TABLE `fragen`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `quizfragen`
--
ALTER TABLE `quizfragen`
  ADD PRIMARY KEY (`quizId`,`frageId`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `fragen`
--
ALTER TABLE `fragen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT für Tabelle `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
