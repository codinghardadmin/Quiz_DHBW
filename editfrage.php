<?php

/*
Auf dieser Seite kann ein Eintrag geändert werden. Der jeweilge Eintrag, der editiert werden soll,
wird vom aufrufenden Skript festgelegt.
*/

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <title>Eintrag editieren</title>
    <meta charset="utf-8">
    <style>

        /*
        CSS Styles für den Body, die Inputfelder und die Buttons
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

    </style>

</head>
<body>

<h1>Eintrag editieren</h1>

<?php
// Einlesen der Konfigurationsdatei
include("config.php");
// Erstellen des PDO Objektes für die Datenbankverbindung
$pdo = new PDO("mysql:host=".$db_host.";dbname=".$db_name,$db_user,$db_pass);

// Überprüfen, ob per POST etwas übertragen wurde. Wenn ja, dann lade die Seite neu mit diesem Parameter als GET
// -> Hintergrund: Man soll in der URL die ausgewählt ID auch angezeigt bekommen
// Mit header() wird die Seite mit der angegebenen Location und den Parametern neu geladen
if (isset($_POST["edit"])) {
    $editquestion = $_POST["edit"];
    header("Location: editfrage.php?edit=" . $editquestion);
}

// Auslesen des GET Parameters mit dem Standardwert 0. Anschließend versuchen, den Wert zu einem Integer zu parsen
// Falls dies nicht möglich ist (Exception), wird diese abgefangen und die editid auf 0 gesetzt
$editquestion = $_GET["edit"] ?? 0;
try {
    $editId = intval($editquestion);
} catch (Exception $e) {
    $editId = 0;
}

// Abfragen, ob editform gesetzt ist und es nun eine Aktualisierung der Daten in der Datenbank gegeben soll
// Hier wird noch frage geprüft. Diese ist allerding zwingend gesetzt, da im Formular durch required alles gesetzt sein muss
if (isset($_POST["frage"]) && isset($_POST["editform"])) {
    
    // Speichern der Parametern in Variablen
    $frage = $_POST["frage"];
    $antwort1 = $_POST["antwort1"];
    $antwort2 = $_POST["antwort2"];
    $antwort3 = $_POST["antwort3"];
    $antwort4 = $_POST["antwort4"];
    $schwer = $_POST["schwer"];
    $thema = $_POST["thema"];

    // Prepared Statement mit SQL Anweisung vorbereiten. Prepeared Statements, um z.B. SQL Injections zu verhindern
    $statement = $pdo->prepare("UPDATE fragen SET frage = ?, antwort1 = ?, antwort2 = ?, antwort3 = ?, antwort4 = ?, schwer = ?, thema = ? WHERE id = ?");
    // Die Fragezeichen werden nun für die Werte im übergebenen Array eingesetzt und der Befehl ausgeführt
    $statement->execute(array($frage, $antwort1, $antwort2, $antwort3, $antwort4, $schwer, $thema, $editId)); 

    // Ausgabe, dass die Frage aktualisiert wurde.
    echo "<p>Die Frage wurde aktualisiert!</p>";
}

// SQL Statement, um die angegebenen Werte aus der Tabelle fragen mit der gegebenen id auszulesen
$statement = $pdo->prepare("SELECT id, frage, antwort1, antwort2, antwort3, antwort4, schwer, thema FROM fragen WHERE id = ?");
// Ersetzen der Fragezeichen und Ausführung des Befehls
$statement->execute(array($editId));

?>

<?php
/*
Im folgenden Abschnitt wird zuerst mit $row immer die nächste Zeile aus der Tabelle geladen und anschließend in das Formular 
mit Zugriff auf das assoziative Array die jeweilige Spalte an der richtigen Stelle eingefügt. So wird das Formular mit den vorher
definierten Werten gefüllt.
Das Formular leitet wieder auf diese Seite weiter mit der id als GET Parameter (?edit=id)
*/
?>

<?php 
while ($row = $statement->fetch()) { 
?>

    <form action='editfrage.php<?php echo "?edit=".$row['id']; ?>' method='post'>
        ID:<br>
        <input name='id' type="number" value="<?php echo $row['id'] ?>" disabled=true>
        <br>
        Frage:<br>
        <textarea name='frage' required><?php echo $row['frage'] ?></textarea>
        <br>
        Richtige Antwort:<br>
        <input name='antwort1' type='text' value="<?php echo $row['antwort1'] ?>" required>
        <br>
        Falsche Antwort:<br>
        <input name='antwort2' type='text' value="<?php echo $row['antwort2'] ?>" required>
        <br>
        Falsche Antwort:<br>
        <input name='antwort3' type='text' value="<?php echo $row['antwort3'] ?>" required>
        <br>
        Falsche Antwort:<br>
        <input name='antwort4' type='text' value="<?php echo $row['antwort4'] ?>" required>
        <br>
        Schwierigkeit:<br>
        <input name='schwer' type='number' value="<?php echo $row['schwer'] ?>" required>
        <br>
        Thema:<br>
        <select name='thema' value="<?php  ?>" required>

<?php

// Es werden nun für das Select alle Optionen geladen. Wenn der Wert thema aus der Datenbank mit dem Wert, der in der Schleife gerade 
// durchlaufen wird übereinstimmt, wird noch das Attribut selected=true gesetzt, um diese als ausgewählt zu markieren
foreach($themen as $thema) {
    echo "<option" . ($thema == $row['thema'] ? " selected=true>" : ">") . $thema . "</option>";
}

?>
        </select>

        <!-- Für Sicherstellung, das die Daten vom Editierungsformular kommen. Mit hidden wird es nicht angezeigt und es dient dazu, dem Programm zu zeigen ob schon
             der Button 'Ändern' auf dieser Seite gedrückt wurde. Wenn dieser Wert gesetzt ist, werden die Daten aktualisiert beim Aufruf dieses Skriptes -->
        <input name="editform" value="1" type="hidden">

        <br><br>
        <button>Ändern</button>
    </form>

    <!-- Button, um zum Admin-Panel zu gelangen -->
    <form action='edit.php' method='get'>
        <button>Zurück zum Admin-Panel</button>
    </form>

<?php 
// Gehört noch zur Schleife, die durch die Datensätze der Datenbank iteriert (Info)
} 
?>

</body>
</html>