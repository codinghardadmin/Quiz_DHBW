<!DOCTYPE html>
<html>

<head>
    <title>Editieren</title>
    <meta charset="utf-8">
</head>

<body>

<?php

$pdo = new PDO('mysql:host=localhost;dbname=quizdb', 'root', '');

// Hinzufügen
echo "
    <h2>Hinzufügen</h2>
    <form action='edit.php' method='post'>
        Frage:
        <textarea name='frage'></textarea>
        Richtige Antwort:
        <input name='antwort1' type='text'>
        Falsche Antwort:
        <input name='antwort2' type='text'>
        Falsche Antwort:
        <input name='antwort3' type='text'>
        Falsche Antwort:
        <input name='antwort4' type='text'>
        Schwierigkeit:
        <input name='schwer' type='number'>
        Thema:
        <select name='thema'>
            <option>Chemie</option>
            <option>Biologie</option>
            <option>Physik</option>
        </select>
        <button>Hinzufügen</button>
    </form>
";

echo "<br>";

if (isset($_POST["frage"]) && isset($_POST["antwort1"])
 && isset($_POST["antwort2"]) && isset($_POST["antwort3"]) 
 && isset($_POST["antwort4"]) && isset($_POST["schwer"]) 
 && isset($_POST["thema"])) {

    $statement = $pdo->prepare("INSERT INTO fragen (frage, antwort1, antwort2, antwort3, antwort4, schwer, thema) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $statement->execute(array($_POST["frage"], $_POST["antwort1"], $_POST["antwort2"], $_POST["antwort3"], $_POST["antwort4"], $_POST["schwer"], $_POST["thema"])); 

    echo "<p>Die Frage wurde hinzugefügt</p>";
}

// Editieren und Löschen
echo "
    <h2>Editieren und löschen</h2>
    <form action='edit.php' method='post'>
        Frage:
        <select name='delete'>
";

$sql = "SELECT frage FROM fragen";
foreach ($pdo->query($sql) as $row) {
    echo "<option>" . $row['frage'] . "</option>";
}

echo "
        </select>
        <button name='chooseBtn' value='delete'>Löschen</button>
        <button name='chooseBtn' value='edit'>Bearbeiten</button>
    </form>
";

if (isset($_POST["chooseBtn"])) {
    switch($_REQUEST["chooseBtn"]) {
        case "delete":

            $statement = $pdo->prepare("DELETE FROM fragen WHERE frage = ?");
            $statement->execute(array($_POST["delete"]));

            echo "<p>Die Frage wurde entfernt</p>";

            break;

        case "edit":

            header("Location: test.php");

            break;
    }
}

if (isset($_POST["delete"])) {

    
}









//$statement = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
//$statement->execute(array('neu@php-einfach.de', 1));

?>

</body>

</html>