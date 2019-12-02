<!DOCTYPE html>
<html>

<head>
    <title>Quiz</title>
    <meta charset="utf-8">
</head>

<body>

<?php

include("config.php");
$pdo = new PDO("mysql:host=".$db_host.";dbname=".$db_name,$db_user,$db_pass);

// https://stackoverflow.com/questions/2259155/increment-value-in-mysql-update-query

function addAktuell($id) {
    $statement = $pdo->prepare("SELECT aktuell FROM quiz WHERE id = ?");
    $statement->execute(array($id));

    $row = $statement->fetch();
    $aktuell = $row["aktuell"];

    $aktuell++;

    $statement = $pdo->prepare("UPDATE quiz SET aktuell = ? WHERE id = ?");
    $statement->execute(array($id)); 
}

function printNewQuestion($id) {
    $sql = "SELECT frage, antwort1, antwort2, antwort3, antwort4 FROM fragen ORDER BY RAND()";
}

?>

<?php

if (!isset($_GET["id"])) {
    
    if (isset($_POST["anzahl"]) && isset($_POST["schwer"]) && isset($_POST["thema"])) {

        // Erster Start des Quiz

        $anzahl = $_POST["anzahl"];
        $schwer = $_POST["schwer"];
        $thema = $_POST["thema"];

        $statement = $pdo->prepare("INSERT INTO quiz (anzahl, schwer, thema, aktuell, richtig) VALUES (?, ?, ?, ?, ?)");
        $statement->execute(array($anzahl, $schwer, $thema, 0, 1)); 
?>

<h2>Frage</h2>
<form action='edit.php' method='post'>
    <fieldset>
        <input type="radio" id="n1" name="n1" value="N1">
        <label for="n1"> N1</label> 
        <input type="radio" id="n2" name="n2" value="N2">
        <label for="n2"> N2</label>
        <input type="radio" id="n3" name="n3" value="N3">
        <label for="n3"> N3</label> 
        <input type="radio" id="n4" name="n4" value="N4">
        <label for="n4"> N4</label> 
  </fieldset>
</form>

<?php     

    } else {

        // Erstellen eines Quiz

?>
        
    <form action='quiz.php' method='post'>
        Anzahl Fragen:<br>
        <input type="number" name='anzahl'></input>
        <br>
        Schwierigkeit bis:<br>
        <input name='schwer' type='number'>
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

    }

}


?>

</body>

</html>