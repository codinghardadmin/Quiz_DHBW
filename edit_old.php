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
    <form action='edit.php'>
        Frage:
        <textarea name='frage'>
        Antwort:
        <input name='antwort' type='text'>
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

if (isset($_POST["frage"]) && isset($_POST["antwort"]) && isset($_POST["schwer"]) && isset($_POST["thema"])) {

    $statement = $pdo->prepare("INSERT INTO fragen (frage, antwort, schwer, thema) VALUES (?, ?, ?, ?)");
    $statement->execute(array($_POST["frage"], $_POST["antwort"], $_POST["schwer"], $_POST["thema"])); 

    echo "<p>Die Frage wurde hinzugefügt</p>";
}

// Löschen
echo "
    <h2>Loeschen</h2>
    <form action='edit.php'>
        Frage:
        <select name='delete'>
";

$sql = "SELECT frage FROM fragen";
foreach ($pdo->query($sql) as $row) {
    echo "<option>" . $row['frage'] . "</option>";
}

echo "
        </select>
        <button>Hinzufügen</button>
    </form>
";

if (isset($_POST["delete"])) {

    $statement = $pdo->prepare("DELETE FROM fragen WHERE frage = ?");
    $statement->execute(array($_POST["delete"]));

    echo "<p>Die Frage wurde entfernt</p>";
}









$statement = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
$statement->execute(array('neu@php-einfach.de', 1));

?>

</body>

</html>