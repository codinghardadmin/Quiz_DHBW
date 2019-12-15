<?php

/*
Auf dieser Seite befindet sich das Admin-Panel. Es können neue Fragen hinzugefügt werden, bearbeitet werden, gelöscht werden und alle bestehenden
Fragen angeschaut werden. Diese können nach den unterschiedlichen Spalten sortiert werden.
*/

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <title>Editieren</title>
    <meta charset="utf-8">
    <style>

        /*
        CSS Styles für den Body, die Inputfelder und die Buttons, die Tabelle und Tabellenfelder
        */

        body {
            margin: 50px;
            font-family: "Arial";
        }

        input[type=text], input[type=number], select, textarea {
            width: 500px;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 18px;
            margin: 2px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }


        table, td, th {  
            border: 1px solid #ddd;
            text-align: left;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 15px;
        }

    </style>
</head>
<body>
<?php
// Einlesen der Konfigurationsdatei
include("config.php");
// Erstellen des PDO Objektes für die Datenbankverbindung
$pdo = new PDO("mysql:host=".$db_host.";dbname=".$db_name,$db_user,$db_pass);
?>

<?php
// Prüfen, ob der Parameter delete gesetzt ist (wenn im Formular vorher ausgewählt)
if (isset($_POST["delete"])) {
    // SQL Statement, um die Frage an einer bestimmten id zu löschen
    $statement = $pdo->prepare("DELETE FROM fragen WHERE id = ?");
    // Fragezeichen im SQL Statement durch den Parameter delete ersetzen
    $statement->execute(array($_POST["delete"]));

    // Ausgabe, dass die Frage gelöscht wurde
    echo "<p>Die Frage wurde entfernt</p>";
}
?>

<?php

// Abfrage, ob alle Werte gesetzt sind
if (isset($_POST["frage"]) && isset($_POST["antwort1"])
 && isset($_POST["antwort2"]) && isset($_POST["antwort3"]) 
 && isset($_POST["antwort4"]) && isset($_POST["schwer"]) 
 && isset($_POST["thema"])) {

    // SQL Statement, um eine Frage in die Datenbank einzutragen
    $statement = $pdo->prepare("INSERT INTO fragen (frage, antwort1, antwort2, antwort3, antwort4, schwer, thema) VALUES (?, ?, ?, ?, ?, ?, ?)");
    // Ersetzen der Fragezeichen und Ausführung des Befehls
    $statement->execute(array($_POST["frage"], $_POST["antwort1"], $_POST["antwort2"], $_POST["antwort3"], $_POST["antwort4"], $_POST["schwer"], $_POST["thema"])); 

    // Ausgabe, dass die Frage hinzugefügt wurde
    echo "<p>Die Frage wurde hinzugefügt</p>";
}
?>

    <h1>Admin Panel: Editieren</h1>

    <!-- Button, um zum Quiz zu gelangen -->
    <form action='quiz.php' method='get'>
        <button>Zum Quiz</button>
    </form>
    <br>

    <!-- Ausklappbares Formular, in dem eine Frage hinzugefügt werden kann -->
    <details>
    <summary>Hinzufügen</summary>
    <h2>Hinzufügen</h2>
    <form action='edit.php' method='post'>
        Frage:<br>
        <textarea name='frage' placeholder='Was ist der erste Buchstabe im deutschen Alphabet?' required></textarea>
        <br>
        Richtige Antwort:<br>
        <input name='antwort1' type='text' placeholder='A' required>
        <br>
        Falsche Antwort:<br>
        <input name='antwort2' type='text' placeholder='N' required>
        <br>
        Falsche Antwort:<br>
        <input name='antwort3' type='text' placeholder='R' required>
        <br>
        Falsche Antwort:<br>
        <input name='antwort4' type='text' placeholder='X' required>
        <br>
        Schwierigkeit:<br>
        <input name='schwer' type='number' placeholder=2 required>
        <br>
        Thema:<br>
        <select name='thema' required>

        <?php
        // Durch alle Themen iterieren und diese im Select anzeigen
        foreach($themen as $thema) {
            echo "<option>" . $thema . "</option>";
        }
        ?>

        </select>
        <br><br>
        <button>Hinzufügen</button>
    </form>
    </details>

    <hr>

    <!-- Ausklapbares Formular, in dem man die Frage zum Editieren auswählen kann -->
    <details>
    <summary>Editieren</summary>
    <h2>Editieren</h2>
    <!-- Weiterleitung an die Seite, um dort die Frage zu editieren -->
    <form action='editfrage.php' method='post'>
        Frage:
        <select name='edit' required>

        <?php  
        // SQL Abfrage, um von jeder Frage in der Tabelle die id und die jeweilige Frage zu bekommen
        $sql = "SELECT id, frage FROM fragen";
        // Durch die Datensätze durchiterieren
        foreach ($pdo->query($sql) as $row) {
            // Hinzufügen der Option zum Select mit der Frage als Namen und der Frage_ID als id der Option
            echo "<option value='" .  $row['id'] . "'>" . $row['frage'] . "</option>";
        }
        ?>

        </select>
        <br>
        <br>
        <button>Bearbeiten</button>
    </form>
    </details>

    <hr>

    <!-- Ausklappbares Formular, in dem man die Frage zum löschen auswählen kann -->
    <details>
    <summary>Löschen</summary>
    <h2>Löschen</h2>
    <form action='edit.php' method='post'>
        Frage:
        <select name='delete' required>

        <?php
        // SQL Abfrage, um von jeder Frage in der Tabelle die id und die jeweilige Frage zu bekommen
        $sql = "SELECT id, frage FROM fragen";
        // Durch die Datensätze durchiterieren
        foreach ($pdo->query($sql) as $row) {
            // Hinzufügen der Option zum Select mit der Frage als Namen und der Frage_ID als id der Option
            echo "<option value='" .  $row['id'] . "'>" . $row['frage'] . "</option>";
        }
        ?>

        </select>
        <br>
        <br>
        <button>Löschen</button>
    </form>
    </details>


    <hr>

<?php


// Ein bereits aufgeklappter Bereich, in dem die Daten angezeigt werden
echo "
<details open>
<summary>Datenbank</summary>
<h2>Alle Fragen (mit Pfeilen jeweilige Spalte sortieren)</h2>
";

// Standardmäßig wird frage sortiert
$sort = "frage";
// Abfragem ob der Parameter gesetzt ist
if (isset($_GET["sort"])) {
    // Zuweisung an Variable
    $get_sort = $_GET["sort"];
    // Abfrage, ob der Parameter Sinn ergibt (einer der vorgefertigten Werte)
    if ($get_sort == "frage" or $get_sort == "antwort1" or $get_sort == "antwort2"
        or $get_sort == "antwort3"  or $get_sort == "antwort4" or $get_sort == "schwer"
        or $get_sort == "thema") {
            $sort = $get_sort;
    }
}

// Definition der SQL Statements, um sämtliche Daten aus der Tabelle fragen zu bekommen
// Diese werden dann noch nach sort(vorher definiert) aufsteigend sortieren
$sql = "SELECT frage, antwort1, antwort2, antwort3, antwort4, schwer, thema FROM fragen ORDER BY $sort ASC";

// Ausgabe der Tabellenstruktur
echo "<table>";
echo "<tr>
<th>Frage<form action='edit.php' method='get'><input type='hidden' name='sort' value='frage'><button>&#x2193;</button></form></th>
<th>Richtige Antwort<form action='edit.php' method='get'><input type='hidden' name='sort' value='antwort1'><button>&#x2193;</button></form></th>
<th>Falsche Antwort<form action='edit.php' method='get'><input type='hidden' name='sort' value='antwort2'><button>&#x2193;</button></form></th>
<th>Falsche Antwort<form action='edit.php' method='get'><input type='hidden' name='sort' value='antwort3'><button>&#x2193;</button></form></th>
<th>Falsche Antwort<form action='edit.php' method='get'><input type='hidden' name='sort' value='antwort4'><button>&#x2193;</button></form></th>
<th>Schwierigkeit<form action='edit.php' method='get'><input type='hidden' name='sort' value='schwer'><button>&#x2193;</button></form></th>
<th>Thema<form action='edit.php' method='get'><input type='hidden' name='sort' value='thema'><button>&#x2193;</button></form></th>
</tr>";

// SQL Statement ausführen und durch alle Datensätze iterieren
foreach ($pdo->query($sql) as $row) {
    // Ausgabe der einzelnen Zeilen der Tabelle
    echo "<tr><td>" . $row['frage'] . "</td>
         <td>" . $row['antwort1'] . "</td>
         <td>" . $row['antwort2'] . "</td>
         <td>" . $row['antwort3'] . "</td>
         <td>" . $row['antwort4'] . "</td>
         <td>" . $row['schwer'] . "</td>
         <td>" . $row['thema'] . "</td></tr>
    ";
}

// Tabelle 'abschließen'
echo "</table>";
echo "<br>";
echo "</details>";

?>

</body>
</html>