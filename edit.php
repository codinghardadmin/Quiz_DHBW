<!DOCTYPE html>
<html>

<head>
    <title>Editieren</title>
    <meta charset="utf-8">
    <style>
        body {
            margin: 50px;
            font-family: "Arial";
        }
        
        /*button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            -webkit-transition-duration: 0.4s;  Safari 
            transition-duration: 0.4s;
        }
        button:hover {
            box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
        }*/




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
include("config.php");
$pdo = new PDO("mysql:host=".$db_host.";dbname=".$db_name,$db_user,$db_pass);
?>
    <h1>Admin Panel: Editieren</h1>

    <details>
    <summary>Hinzufügen</summary>
    <h2>Hinzufügen</h2>
    <form action='edit.php' method='post'>
        Frage:<br>
        <textarea name='frage' placeholder='Was ist der erste Buchstabe im deutschen Alphabet?'></textarea>
        <br>
        Richtige Antwort:<br>
        <input name='antwort1' type='text' placeholder='A'>
        <br>
        Falsche Antwort:<br>
        <input name='antwort2' type='text' placeholder='N'>
        <br>
        Falsche Antwort:<br>
        <input name='antwort3' type='text' placeholder='R'>
        <br>
        Falsche Antwort:<br>
        <input name='antwort4' type='text' placeholder='X'>
        <br>
        Schwierigkeit:<br>
        <input name='schwer' type='number' placeholder=2>
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
        <button>Hinzufügen</button>
    </form>
    </details>

<?php
if (isset($_POST["frage"]) && isset($_POST["antwort1"])
 && isset($_POST["antwort2"]) && isset($_POST["antwort3"]) 
 && isset($_POST["antwort4"]) && isset($_POST["schwer"]) 
 && isset($_POST["thema"])) {

    $statement = $pdo->prepare("INSERT INTO fragen (frage, antwort1, antwort2, antwort3, antwort4, schwer, thema) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $statement->execute(array($_POST["frage"], $_POST["antwort1"], $_POST["antwort2"], $_POST["antwort3"], $_POST["antwort4"], $_POST["schwer"], $_POST["thema"])); 

    echo "<p>Die Frage wurde hinzugefügt</p>";
}
?>
    <hr>
    <details>
    <summary>Editieren</summary>
    <h2>Editieren</h2>
    <form action='editfrage.php' method='post'>
        Frage:
        <select name='edit'>
<?php
$sql = "SELECT id, frage FROM fragen";
foreach ($pdo->query($sql) as $row) {
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
    <details>
    <summary>Löschen</summary>
    <h2>Löschen</h2>
    <form action='edit.php' method='post'>
        Frage:
        <select name='delete'>
<?php
$sql = "SELECT id, frage FROM fragen";
foreach ($pdo->query($sql) as $row) {
    echo "<option value='" .  $row['id'] . "'>" . $row['frage'] . "</option>";
}
?>
        </select>
        <br>
        <br>
        <button>Löschen</button>
    </form>
    </details>
<?php
if (isset($_POST["delete"])) {
    $statement = $pdo->prepare("DELETE FROM fragen WHERE id = ?");
    $statement->execute(array($_POST["delete"]));

    echo "<p>Die Frage wurde entfernt</p>";
}
?>
    <hr>

<?php



echo "
<details open>
<summary>Datenbank</summary>
<h2>Alle Fragen (mit Pfeilen jeweilige Spalte sortieren)</h2>
";


$sort = "frage";
if (isset($_GET["sort"])) {
    $get_sort = $_GET["sort"];
    if ($get_sort == "frage" or $get_sort == "antwort1" or $get_sort == "antwort2"
        or $get_sort == "antwort3"  or $get_sort == "antwort4" or $get_sort == "schwer"
        or $get_sort == "thema") {
            $sort = $get_sort;
    }
}

$sql = "SELECT frage, antwort1, antwort2, antwort3, antwort4, schwer, thema FROM fragen ORDER BY $sort ASC";

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

foreach ($pdo->query($sql) as $row) {
    echo "<tr><td>" . $row['frage'] . "</td>
         <td>" . $row['antwort1'] . "</td>
         <td>" . $row['antwort2'] . "</td>
         <td>" . $row['antwort3'] . "</td>
         <td>" . $row['antwort4'] . "</td>
         <td>" . $row['schwer'] . "</td>
         <td>" . $row['thema'] . "</td></tr>
    ";
}

echo "</table>";
echo "<br>";

/*

$sql = "SELECT frage, antwort1, antwort2, antwort3, antwort4, schwer, thema FROM fragen ORDER BY schwer ASC";

foreach ($pdo->query($sql) as $row) {
    echo "<p>Frage: " . $row['frage'] . "<br>
         Richtige Antwort: " . $row['antwort1'] . "<br>
         Falsche Antwort 1: " . $row['antwort2'] . "<br>
         Falsche Antwort 2: " . $row['antwort3'] . "<br>
         Falsche Antwort 3: " . $row['antwort4'] . "<br>
         Schwierigkeit: " . $row['schwer'] . "<br>
         Thema: " . $row['thema'] . "<br>
    ";
}

echo "</details>";
*/



// ORDER BY SCHWER ODER THEMA ALS 2 BUTTONS!
// MUSS NOCH IMPLEMENTIERT WERDEN!

//echo "<br>";


?>
</body>
</html>