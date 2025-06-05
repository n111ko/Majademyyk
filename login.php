<?php
session_start();
include('zoneconf.php');

$error = "";

if (!empty($_POST['login']) && !empty($_POST['pass'])) {
    global $conn;
    //eemaldame kasutaja sisestusest kahtlase pahna
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars(trim($_POST['pass']));
    //SIIA UUS KONTROLL
    $sool = 'test';
    $krypt = crypt($pass, $sool);
    //kontrollime kas andmebaasis on selline kasutaja ja parool
    $paring = $conn->prepare("SELECT kasutajanimi, parool, onadmin FROM kasutajad WHERE kasutajanimi=? AND parool=?");
    $paring->bind_param('ss', $login, $krypt);
    $paring->bind_result($kasutajanimi, $parool, $onadmin);
    $paring->execute();

    if($paring->fetch() && $parool == $krypt) {
        $_SESSION['kasutaja'] = $login;
        if($onadmin == 1) {
            $_SESSION['admin'] = true;
        }
        header('Location: kinnisvara.php');
    } else {
        $error = "Kasutaja või parool on vale!";
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Autoriseerimine</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="kinnisvaraOptions.js"></script>
</head>

<body class="andmed-lisamine" style="height: 1000px;">

    <!-- navigeerimismenüü -->
    <?php
    require ('nav.php');
    ?>

    <div class="andmed-input">
        <h2>Autoriseerimine</h2>
        <form action="" method="post" class="andmed">

            <div class="form-group">
                <label for="login">Kasutajanimi:</label>
                <input type="text" id="login" name="login" maxlength="50">
            </div>

            <div class="form-group">
                <label for="pass">Salasõna:</label>
                <input type="password" id="pass" name="pass">
            </div>

            <?php
            echo ($error);
            ?>

            <input type="submit" value="Logi sisse" class="lisa-button">
            <a href="signup.php" class="signup">Registreerimine</a>
        </form>
    </div>
</body>