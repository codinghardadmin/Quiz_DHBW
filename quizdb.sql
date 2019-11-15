-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 15. Nov 2019 um 22:21
-- Server-Version: 10.1.38-MariaDB
-- PHP-Version: 7.3.4

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
(2, 'a', 'a', 'a', 'a', 'a', 3, 'Chemie'),
(5, 'erfddsaf', 'dfsafas', 'dsfsf', 'asfdsf', 'fasdf', 3, 'Biologie'),
(6, 'hgjzthjzt', 'jruzj', 'oilÃ¶opÃ¶', 'trgtrh', 'io', 4, 'Physik'),
(7, 'sadfds sadfds sadfds sadfds sadfds sadfds sadfds', 'fsdaf', 'fsdfh', 'hfdh', 'zuzi', 4, 'Chemie'),
(8, 'sadfds sadfds sadfds sadfds sadfds sadfds sadfds', 'fsdaf', 'fsdfh', 'hfdh', 'zuzi', 4, 'Chemie'),
(9, 'sadfds sadfds sadfds sadfds sadfds sadfds sadfds', 'fsdaf', 'fsdfh', 'hfdh', 'zuzi', 4, 'Chemie');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `fragen`
--
ALTER TABLE `fragen`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `fragen`
--
ALTER TABLE `fragen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
