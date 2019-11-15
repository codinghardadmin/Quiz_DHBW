<!DOCTYPE html>
<html>

<head>
    <title>Frage editieren</title>
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

$sql = "SELECT id, frage, antwort1, antwort2, antwort3, antwort4, schwer, thema FROM fragen WHERE frage = ?";

$statement = $pdo->prepare($sql);

$editquestion = "";

if (isset($_POST["edit"])) {
    $editquestion = $_POST["edit"];
}


$statement->execute(array($editquestion));


?>


<?php while ($row = $statement->fetch()) { ?>

    <h2>Frage editieren</h2>
    <form action='editfrage.php' method='post'>
        ID:<br>
        <input name='id' type="text" value="<?php echo $row['id'] ?>" disabled>
        Frage:<br>
        <textarea name='frage'><?php echo $row['frage'] ?></textarea>
        <br>
        Richtige Antwort:<br>
        <input name='antwort1' type='text' value="<?php echo $row['antwort1'] ?>">
        <br>
        Falsche Antwort:<br>
        <input name='antwort2' type='text' value="<?php echo $row['antwort2'] ?>">
        <br>
        Falsche Antwort:<br>
        <input name='antwort3' type='text' value="<?php echo $row['antwort3'] ?>">
        <br>
        Falsche Antwort:<br>
        <input name='antwort4' type='text' value="<?php echo $row['antwort4'] ?>">
        <br>
        Schwierigkeit:<br>
        <input name='schwer' type='number' value="<?php echo $row['schwer'] ?>">
        <br>
        Thema:<br>
        <!-- WICHTIG! ALLE ANZEIGEN UND AKTUELLES ALS AUSWAHL EINSTELLEN!!! -->
        <!-- WICHTIG! ALLE ANZEIGEN UND AKTUELLES ALS AUSWAHL EINSTELLEN!!! -->
        <!-- WICHTIG! ALLE ANZEIGEN UND AKTUELLES ALS AUSWAHL EINSTELLEN!!! -->
        <select name='thema' value="<?php  ?>">

<?php

foreach($themen as $thema) {
    echo "<option>" . $thema . "</option>";
}

?>

        </select>
        <!-- Für Sicherstellung, das die Daten vom Editierungsformular kommen -->
        <input id="editform" value="1" type="hidden">

        <br><br>
        <button>Ändern</button>
    </form>

<?php } ?>

<?php

if (isset($_POST["frage"]) && isset($_POST["antwort1"])
 && isset($_POST["antwort2"]) && isset($_POST["antwort3"]) 
 && isset($_POST["antwort4"]) && isset($_POST["schwer"]) 
 && isset($_POST["thema"]) && isset($_POST["editform"])
 && isset($_POST["id"])) {

    $statement = $pdo->prepare("UPDATE fragen SET frage = ?, antwort1 = ?, antwort2 = ?, antwort3 = ?, antwort4 = ?, schwer = ?, thema = ? WHERE frage = ?");
    $statement->execute(array($_POST["frage"], $_POST["antwort1"], $_POST["antwort2"], $_POST["antwort3"], $_POST["antwort4"], $_POST["schwer"], $_POST["id"])); 

    echo "<p>Die Frage wurde aktualisiert</p>";
}

?>

</body>

</html>