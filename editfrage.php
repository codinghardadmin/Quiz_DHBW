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

if (isset($_POST["edit"])) {
    $editquestion = $_POST["edit"];
    header("Location: editfrage.php?edit=" . $editquestion);
}

$editquestion = $_GET["edit"] ?? 0; // 0 als Standardwert zum bearbeiten. Evtl. header() mit Location oder Programm exit;
try {
    $editId = intval($editquestion);
} catch (Exception $e) {
    $editId = 0;
}

if (isset($_POST["frage"]) && isset($_POST["editform"])) { // Hier evtl noch mehr abprüfen! Pflichtfelder !!! Dies kann auch mit required im Formularfeld passieren

    $frage = $_POST["frage"];
    $antwort1 = $_POST["antwort1"];
    $antwort2 = $_POST["antwort2"];
    $antwort3 = $_POST["antwort3"];
    $antwort4 = $_POST["antwort4"];
    $schwer = $_POST["schwer"];
    $thema = $_POST["thema"];

    $statement = $pdo->prepare("UPDATE fragen SET frage = ?, antwort1 = ?, antwort2 = ?, antwort3 = ?, antwort4 = ?, schwer = ?, thema = ? WHERE id = ?");
    $statement->execute(array($frage, $antwort1, $antwort2, $antwort3, $antwort4, $schwer, $thema, $editId)); 

    echo "<p>Die Frage wurde aktualisiert!</p>" . $editId;
}

$statement = $pdo->prepare("SELECT id, frage, antwort1, antwort2, antwort3, antwort4, schwer, thema FROM fragen WHERE id = ?");
$editquestion = "";
$statement->execute(array($editId));

?>


<?php while ($row = $statement->fetch()) { ?>

    <h2>Frage editieren</h2>
    <form action='editfrage.php<?php echo "?edit=".$row['id']; ?>' method='post'>
        ID:<br>
        <input name='id' type="number" value="<?php echo $row['id'] ?>" disabled=true>
        <br>
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
        <select name='thema' value="<?php  ?>">

<?php

foreach($themen as $thema) {
    echo "<option" . ($thema == $row['thema'] ? " selected=true>" : ">") . $thema . "</option>";
}

?>

        </select>
        <!-- Für Sicherstellung, das die Daten vom Editierungsformular kommen -->
        <input name="editform" value="1" type="hidden">

        <br><br>
        <button>Ändern</button>
    </form>

<?php } ?>

<?php

/*
var_dump(isset($_POST["frage"]));
var_dump(isset($_POST["antwort1"]));
var_dump(isset($_POST["antwort2"]));
var_dump(isset($_POST["antwort3"]));
var_dump(isset($_POST["antwort4"]));
var_dump(isset($_POST["thema"]));
var_dump(isset($_POST["editform"]));
var_dump(isset($_POST["id"]));

$frage = $_POST["frage"];
$antwort1 = $_POST["antwort1"];
$antwort2 = $_POST["antwort2"];
$antwort3 = $_POST["antwort3"];
$antwort4 = $_POST["antwort4"];
$thema = $_POST["thema"];
$editform = $_POST["editform"];
$id = $_POST["id"];

*/

?>

</body>

</html>