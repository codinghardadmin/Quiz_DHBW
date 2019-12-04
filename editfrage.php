<!DOCTYPE html>
<html>

<head>
    <title>Eintrag editieren</title>
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
    </style>

</head>
<body>

<h1>Eintrag editieren</h1>

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
    <form action='edit.php' method='get'>
        <button>Zurück zum Admin-Panel</button>
    </form>
<?php 
} 
?>

</body>
</html>