<?php

/*
Auf dieser Seite befindet sich das Quiz. Falls ein Quiz beendet ist, wird auf dieser Seite zudem noch
das Endresultat angezeigt. Zugegriff auf ein Quiz erfolgt durch den GET Parameter in der URL.
Dort wird die ID des Quiz übergeben
*/

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <title>Quiz</title>
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

        input[type=radio] {
            padding: 12px 20px;
            margin: 8px 0;
        }

    </style>
</head>
<body>

<?php
// Einlesen der Konfigurationsdatei
include("config.php");
// Erstellen des PDO Objektes für die Datenbankverbindung
$pdo = new PDO("mysql:host=".$db_host.";dbname=".$db_name,$db_user,$db_pass);
// Emulieren der Prepeared Statements ausschalten. Keine größeren Sicherheitsrisiken. 
// Ältere Datenbanken werden so unterstützt. Bei bestimmten Datentypen muss auf die
// Konvertierung geachtet werden
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


function addAktuell($pdo, $id, $correct) {
    $statement = $pdo->prepare("SELECT aktuell, richtig FROM quiz WHERE id = ?");
    $statement->execute(array($id));

    $row = $statement->fetch();
    $aktuell = $row["aktuell"];
    $richtig = $row["richtig"];

    $aktuell++;
    
    if ($correct) {
        $richtig++;
    }

    $statement = $pdo->prepare("UPDATE quiz SET aktuell = ?, richtig = ? WHERE id = ?");
    $statement->execute(array($aktuell, $richtig, $id)); 
}


function printResults($pdo, $id) {
    $statement = $pdo->prepare("SELECT richtig, anzahl FROM quiz WHERE id = ?");
    $statement->execute(array($id));

    $row = $statement->fetch();
    $richtig = $row["richtig"];
    $anzahl = $row["anzahl"];
?>

    <h2>Endresultate</h2>
    <p>Du hast das Quiz nun beendet! Du hast <?php echo $richtig;?> Frage(n) von <?php echo $anzahl;?> Frage(n) richtig beantwortet!</p>
    <progress max="<?php echo ($anzahl); ?>" value="<?php echo ($richtig); ?>"></progress>

<?php
}
?>


<?php
function printQuestion($pdo, $id) {
    if (isset($_POST["f1"])) {

        $chosenId = 0;
        switch($_POST["f1"]) {
            case "N2": $chosenId = 1; break;
            case "N3": $chosenId = 2; break;
            case "N4": $chosenId = 3; break;
            default: $chosenId = 0;
        }

        $statement = $pdo->prepare("SELECT lsg FROM quiz WHERE id = ?");
        $statement->execute(array($id));

        $row = $statement->fetch();
        $lsg = $row["lsg"];

        if ($lsg == $chosenId) {
            echo "<br><b>Die letzte Antwort war richtig</b><br>";
            addAktuell($pdo, $id, true);
        } else {
            echo "<br><b>Die letzte Antwort war falsch</b><br>";
            addAktuell($pdo, $id, false);
        }
    }

    $statement = $pdo->prepare("SELECT aktuell, anzahl FROM quiz WHERE id = ?");
    $statement->execute(array($id));

    $row = $statement->fetch();
    $aktuell = $row["aktuell"];
    $anzahl = $row["anzahl"];

    if ($aktuell > $anzahl) {
        printResults($pdo, $id);
        exit;
    }

    $statement = $pdo->prepare("SELECT frageId FROM quizfragen WHERE quizId = ?");
    $statement->execute(array($id));

    $row = $statement->fetch();
    $i = 1;
    // !!!!!!!!!!!! Alternative, um genau den i-ten Datensatz zu bekommen? Mögliche Variante von fetch() mit Offset
    while($i < $aktuell) {
        $row = $statement->fetch();
        $i++;
    }

    $frageId = $row["frageId"];

    //echo "Frageid: $frageId";

    $statement = $pdo->prepare("SELECT frage, antwort1, antwort2, antwort3, antwort4 FROM fragen WHERE id = ?");
    $statement->execute(array($frageId));

    $row = $statement->fetch();
    echo $statement->rowCount();

    $fragen = array($row["antwort1"], $row["antwort2"], $row["antwort3"], $row["antwort4"]);
    shuffle($fragen);

    $lsg_index = array_search($row["antwort1"], $fragen);

    $statement = $pdo->prepare("UPDATE quiz SET lsg = ? WHERE id = ?");
    $statement->execute(array($lsg_index, $id)); 
    
?>

    <progress max="<?php echo ($anzahl); ?>" value="<?php echo ($aktuell-1); ?>"></progress>
    <h2>Frage <?php echo $aktuell . " von " . $anzahl . ": " . $row["frage"]; ?></h2>

    <form action='quiz.php<?php echo "?id=" . $id; ?>' method='post'>
        <input type="radio" id="n1" name="f1" value="N1" checked="checked">
        <label for="n1"> <?php echo $fragen[0] ?></label>
        <br>
        <input type="radio" id="n2" name="f1" value="N2">
        <label for="n2"> <?php echo $fragen[1] ?></label>
        <br>
        <input type="radio" id="n3" name="f1" value="N3">
        <label for="n3"> <?php echo $fragen[2] ?></label>
        <br>
        <input type="radio" id="n4" name="f1" value="N4">
        <label for="n4"> <?php echo $fragen[3] ?></label>
        <br>
        <br>
        <input name="nextfrage" value="1" type="hidden">
        <button>Weiter</button>
    </form>

<?php
}
?>

<?php

// Wenn die ID nur per POST übergeben wurde, dann soll die Seite neu geladen werden mit der ID als GET Parameter
// POST Anfrage kommt beim Erstellen des Quiz. Man kann durch die ID in der URL zu dem aktuellen Stand des Quiz
// zurückkehren bzw. anhand dieser ID das Resultat ansehen

// Hier wird der Fall abgefragen, wenn der GET Parameter noch nicht gesetzt ist
if (!isset($_GET["id"])) {

    // Dann wird geschaut, ob die Parameter, die bei der Quizerstellung gesetzt werden, verfügbar sind.
    // Wenn dies der Fall ist, dann wird ein neues Quiz initialisiert und die Daten in die Datenbank eingetragen
    if (isset($_POST["anzahl"]) && isset($_POST["schwer"]) && isset($_POST["thema"])) {

        // Hier nun Initialisieren des Quiz und Weiterleitung, damit die ID als GET Parameter verfügbar ist

        // Speichern der Parameter anzahl und schwer als int und thema
        $anzahl = intval($_POST["anzahl"]);
        $schwer = intval($_POST["schwer"]);
        $thema = $_POST["thema"];

        // SQL Statement, um die id der Fragen zu bekommen, in denen die Schwierigkeit <= der gewünschten Schwierigkeit ist
        // und das Thema der Auswahl entspricht. Zudem soll die Reihenfolge zufällig sein, was mit RAND() geschieht
        $statement_f = $pdo->prepare("SELECT id FROM fragen WHERE schwer <= ? AND thema = ? ORDER BY RAND()");
        // Ersetzen der Fragezeichen mit den übergebenen Werten und ausführen des Befehls
        $statement_f->execute(array($schwer, $thema));

        // Anzahl der Datensätze ermitteln ==> Anzahl der verfügbaren gefundenen Fragen, die der Auswahl entsprechen
        $count = $statement_f->rowCount();

        // SQL Statement, um ein neues Quiz in die Datenbank einzutragen (int die Tabelle Quiz)
        $statement_q = $pdo->prepare("INSERT INTO quiz (anzahl, schwer, thema, aktuell, richtig, lsg) VALUES (?, ?, ?, ?, ?, ?)");
        // Ersetzen der Fragezeichen und ausführen
        // Die Anzahl ist das Minimum der Angabe der Fragen und der gefundenen Fragen:
        // Je nachdem, welcher Wert geringer ist.
        $statement_q->execute(array(min($count, $anzahl), $schwer, $thema, 1, 0, 0)); 
        // Die erzeugte ID (quiz_id) auslesen. MySQL hat diese Spalte als AUTO_INCREMENT: wird automatisch hochgezählt
        $insertedId = $pdo->lastInsertId();

        // 
        $i = 1;
        while ($row = $statement_f->fetch()) {
            $statement = $pdo->prepare("INSERT INTO quizfragen (quizId, frageId) VALUES (?, ?)");
            $statement->execute(array($insertedId, $row["id"])); 

            if ($i > $anzahl) {
                break;
            }

            $i++;
        }

        // Weiterleitung an die gleiche Seite, nur mit der ID als GET Parameter. Das eigentliche Quiz beginnt
        header("Location: quiz.php?id=" . $insertedId);
?>

    <?php     
    } else {
        // Erstellen eines Quiz
    ?>  

    <h2>Quiz erstellen</h2>

    <form action='edit.php' method='get'>
        <button>Zum Admin-Panel</button>
    </form>
    <br>

    <form action='quiz.php' method='post'>
        Anzahl Fragen:<br>
        <input type="number" name='anzahl' placeholder="3"></input>
        <br>
        Schwierigkeit bis:<br>
        <input name='schwer' type='number' placeholder="8">
        <br>
        Thema:<br>
        <select name='thema'>

        <?php
        // Iterieren durch alle Themen
        foreach($themen as $thema) {
            // Jedes Thema dem Select hinzufügen
            echo "<option>$thema</option>";
        }
        ?>

        </select>
        <br><br>
        <button>Quiz starten</button>
    </form>

    <?php  
    }
    ?>

<?php
} else {

    // Wenn die ID nur per POST übergeben wurde, dann soll die Seite neu geladen werden mit der ID als GET Parameter
    // POST Anfrage kommt beim Erstellen des Quiz. Man kann durch die ID in der URL zu dem aktuellen Stand des Quiz
    // zurückkehren bzw. anhand dieser ID das Resultat ansehen
    $quizId = $_GET["id"] ?? -1;

    // Versuche, die id zum int zu parsen. Falls dies nicht gelingt, wird man einfach auf die Startseite des Quiz weitergeleitet
    // Damit soll verhindert werden, dass jmd einfach irgendeinen Wert in den GET Parameter übergibt
    try {
        // Parsen des Wertes
        $numericId = intval($quizId);
        // Zudem, wenn der Wert -1 ist (Fehlerwert)
        if ($numericId == -1) {
            // Weiterleitung an Startseite des Quiz
            header("Location: quiz.php");
        }
    } catch (Exception $e) {
        // Im catch-Block: Was passiert wenn das parsen misslingt
        header("Location: quiz.php");
    }

    printQuestion($pdo, $numericId);
}

?>

</body>
</html>