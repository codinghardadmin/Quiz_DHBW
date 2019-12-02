<!DOCTYPE html>
<html>

<head>
    <title>Editieren</title>
    <meta charset="utf-8">

    <style>
        body {
            margin: 50px;
            font-family: "Arial";
            background: #EEEEEE;
        }
    </style>

</head>

<body>

<?php

include("config.php");
$pdo = new PDO("mysql:host=".$db_host.";dbname=".$db_name,$db_user,$db_pass);

?>

    <h2>Hinzufügen</h2>
    <form action='edit.php' method='post'>
        Frage:<br>
        <textarea name='frage' placeholder='Was ist der erste Buchstabe im deutschen Alphabet?'></textarea>
        <br>
        Richtige Antwort:<br>
        <input name='antwort1' type='text' placeholder='A'>
        <br>
        Falsche Antwort:<br>
        <input name='antwort2' type='text' placeholder='N'>
        <br>
        Falsche Antwort:<br>
        <input name='antwort3' type='text' placeholder='R'>
        <br>
        Falsche Antwort:<br>
        <input name='antwort4' type='text' placeholder='X'>
        <br>
        Schwierigkeit:<br>
        <input name='schwer' type='number' placeholder=2>
        <br>
        Thema:<br>
        <select name='thema'>

<?php

foreach($themen as $thema) {
    echo "<option>" . $thema . "</option>";
}

?>

        </select>
        <br><br>
        <button>Hinzufügen</button>
    </form>

<?php

if (isset($_POST["frage"]) && isset($_POST["antwort1"])
 && isset($_POST["antwort2"]) && isset($_POST["antwort3"]) 
 && isset($_POST["antwort4"]) && isset($_POST["schwer"]) 
 && isset($_POST["thema"])) {

    $statement = $pdo->prepare("INSERT INTO fragen (frage, antwort1, antwort2, antwort3, antwort4, schwer, thema) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $statement->execute(array($_POST["frage"], $_POST["antwort1"], $_POST["antwort2"], $_POST["antwort3"], $_POST["antwort4"], $_POST["schwer"], $_POST["thema"])); 

    echo "<p>Die Frage wurde hinzugefügt</p>";
}

?>

    <hr>
    <h2>Editieren</h2>
    <form action='editfrage.php' method='post'>
        Frage:
        <select name='edit'>

<?php

$sql = "SELECT id, frage FROM fragen";
foreach ($pdo->query($sql) as $row) {
    echo "<option value='" .  $row['id'] . "'>" . $row['frage'] . "</option>";
}

?>

        </select>
        <br>
        <br>
        <button>Bearbeiten</button>
    </form>

    <hr>
    <h2>Löschen</h2>
    <form action='edit.php' method='post'>
        Frage:
        <select name='delete'>

<?php

$sql = "SELECT id, frage FROM fragen";
foreach ($pdo->query($sql) as $row) {
    echo "<option value='" .  $row['id'] . "'>" . $row['frage'] . "</option>";
}

?>

        </select>
        <br>
        <br>
        <button>Löschen</button>
    </form>

<?php

if (isset($_POST["delete"])) {

    $statement = $pdo->prepare("DELETE FROM fragen WHERE id = ?");
    $statement->execute(array($_POST["delete"]));

    echo "<p>Die Frage wurde entfernt</p>";
}

?>

    <hr>

<?php

echo "<h2>Alle Fragen</h2>";

$sql = "SELECT frage, antwort1, antwort2, antwort3, antwort4, schwer, thema FROM fragen ORDER BY schwer ASC";

foreach ($pdo->query($sql) as $row) {
    echo "<p>Frage: " . $row['frage'] . "<br>
         Richtige Antwort: " . $row['antwort1'] . "<br>
         Falsche Antwort 1: " . $row['antwort2'] . "<br>
         Falsche Antwort 2: " . $row['antwort3'] . "<br>
         Falsche Antwort 3: " . $row['antwort4'] . "<br>
         Schwierigkeit: " . $row['schwer'] . "<br>
         Thema: " . $row['thema'] . "<br>
    ";
}

?>

</body>

</html>