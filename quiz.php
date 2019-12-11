<!DOCTYPE html>
<html>

<head lang="de">
    <title>Quiz</title>
    <meta charset="utf-8">
    <style>
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
            /*width: 100%;*/
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
include("config.php");
$pdo = new PDO("mysql:host=".$db_host.";dbname=".$db_name,$db_user,$db_pass);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

// https://stackoverflow.com/questions/2259155/increment-value-in-mysql-update-query

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

function printQuestion($pdo, $id) {
    if (isset($_POST["f1"])) {
        //echo "F1: " . $_POST["f1"];
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

        echo "<b>Letzer richtiger Index: $lsg  Jetzt gewählter Index: $chosenId</b>";

        //echo "LSG: $lsg CHOSENID: $chosenId";

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

    //echo "ID: $id Aktuell: $aktuell Anzahl: $anzahl";

    $statement = $pdo->prepare("SELECT frageId FROM quizfragen WHERE quizId = ?");
    $statement->execute(array($id));

    $row = $statement->fetch();
    $i = 1;
    // Alternative, um genau den i-ten Datensatz zu bekommen? Mögliche Variante von fetch() mit Offset
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

    /*
    $lsg_index = 0;
    if ($fragen[1] == $row["antwort1"]) {
        $lsg_index = 1;
    } else if ($fragen[2] == $row["antwort1"]) {
        $lsg_index = 2;
    } else if ($fragen[3] == $row["antwort1"]) {
        $lsg_index = 3;
    }
    */

    $lsg_index = array_search($row["antwort1"], $fragen);

    $debugmsg = "LSGINDEX: " . $lsg_index . " A1: " . $fragen[0] . " A2: " . $fragen[1] . " A3: " . $fragen[2] . " A4: " . $fragen[3]; 
    echo "<script>window.alert('".$debugmsg."');</script>";

    $statement = $pdo->prepare("UPDATE quiz SET lsg = ? WHERE id = ?");
    $statement->execute(array($lsg_index, $id)); 
    
?>
<progress max="<?php echo ($anzahl); ?>" value="<?php echo ($aktuell-1); ?>"></progress>
<h2>Frage <?php echo $aktuell . " von " . $anzahl . ": " . $row["frage"]; ?></h2>
<form action='quiz.php<?php echo "?id=" . $id; ?>' method='post'>
    <!--<fieldset>-->
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
  <!--</fieldset>-->
</form>
<?php
}
?>

<?php
if (!isset($_GET["id"])) {
    if (isset($_POST["anzahl"]) && isset($_POST["schwer"]) && isset($_POST["thema"])) {
        // Initialisieren des Quiz und Weiterleitung
        $anzahl = intval($_POST["anzahl"]);
        $schwer = intval($_POST["schwer"]);
        $thema = $_POST["thema"];

        $statement_f = $pdo->prepare("SELECT id FROM fragen WHERE schwer <= ? AND thema = ? ORDER BY RAND()");
        $statement_f->execute(array($schwer, $thema));

        $count = $statement_f->rowCount();

        $statement_q = $pdo->prepare("INSERT INTO quiz (anzahl, schwer, thema, aktuell, richtig, lsg) VALUES (?, ?, ?, ?, ?, ?)");
        $statement_q->execute(array(min($count, $anzahl), $schwer, $thema, 1, 0, 0)); 
        $insertedId = $pdo->lastInsertId();

        $i = 1;
        while ($row = $statement_f->fetch()) {
            $statement = $pdo->prepare("INSERT INTO quizfragen (quizId, frageId) VALUES (?, ?)");
            $statement->execute(array($insertedId, $row["id"])); 

            if ($i > $anzahl) {
                break;
            }

            $i++;
        }

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
foreach($themen as $thema) {
    echo "<option>$thema</option>";
}
?>
        </select>
        <br><br>
        <button>Quiz starten</button>
    </form>

<?php  
    }
} else {
    $id = $_GET["id"];
    $isEnde = false;

    if ($isEnde) {
        // Übersicht
    } else {
        // Im Quiz
        $quizId = $_GET["id"] ?? -1;
        try {
            $numericId = intval($quizId);
            if ($numericId == -1) {
                header("Location: quiz.php");
            }
        } catch (Exception $e) {
            header("Location: quiz.php");
        }

        printQuestion($pdo, $numericId);
    }
}

?>
</body>
</html>