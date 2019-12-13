<?php

// Installationsskript. Dieses Skript nur einmal ausführen, dann wird die Datenbank (in config.php festgelegt)
// und die Tabellen erstellt. Alternativ kann auch einfach die beigefügte SQL-Datei in MySQL in der festgelegten
// Datenbank importiert werden.

// Importiert werden alle Tabellen mit Primärschlüsseln und AUTO_INCREMENT für die automatische Inkrementierung
// der ID's

include("config.php");

$pdo = new PDO("mysql:host=".$db_host,$db_user,$db_pass);

$sql = "";
$sql .= "CREATE DATABASE " . $db_name . ";";
$pdo->exec($sql);


$pdo = new PDO("mysql:host=".$db_host.";dbname=".$db_name,$db_user,$db_pass);

$sql = "";
$sql .= "CREATE TABLE `fragen` (
    `id` int(11) NOT NULL,
    `frage` text NOT NULL,
    `antwort1` text NOT NULL,
    `antwort2` text NOT NULL,
    `antwort3` text NOT NULL,
    `antwort4` text NOT NULL,
    `schwer` int(11) NOT NULL,
    `thema` text NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$sql .= "CREATE TABLE `quiz` (
    `id` int(11) NOT NULL,
    `anzahl` int(11) NOT NULL,
    `schwer` int(11) NOT NULL,
    `thema` text NOT NULL,
    `aktuell` int(11) NOT NULL,
    `richtig` int(11) NOT NULL,
    `lsg` int(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$sql .= "CREATE TABLE `quizfragen` (
    `quizId` int(11) NOT NULL,
    `frageId` int(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$sql .= "ALTER TABLE `fragen`
ADD PRIMARY KEY (`id`);

ALTER TABLE `quiz`
ADD PRIMARY KEY (`id`);

ALTER TABLE `quizfragen`
ADD PRIMARY KEY (`quizId`,`frageId`);

ALTER TABLE `fragen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

ALTER TABLE `quiz`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;";

$pdo->exec($sql);

echo "Das Installationsskript wurde ausgeführt!";

?>