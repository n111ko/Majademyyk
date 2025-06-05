<?php
global $conn;
?>

<head>
    <meta charset="UTF-8">
    <title>Registreerimine</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="andmed-lisamine">

    <!-- navigeerimismenüü -->
    <?php
    require ('nav.php');
    ?>

    <div class="andmed-input">
        <h2>Registreerimine</h2>
        <form action="signup.inc.php" method="post" class="andmed">

            <div class="form-group">
                <label for="username">Kasutajanimi:</label>
                <input type="text" id="username" name="uid" maxlength="30">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" maxlength="30">
            </div>

            <div class="form-group">
                <label for="pwd">Salasõna:</label>
                <input type="password" id="pwd" name="pwd" maxlength="30">
            </div>

            <div class="form-group">
                <label for="pwdrepeat">Korda salasõna:</label>
                <input type="password" id="pwdrepeat" name="pwdrepeat">
            </div>

            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "emptyinput") {
                    echo "Sisestage kõik andmed!";
                } else if ($_GET["error"] == "invaliduid") {
                    echo "Sisestage korrektne nimi!";
                } else if ($_GET["error"] == "invalidemail") {
                    echo "Sisestage korrektne email!";
                } else if ($_GET["error"] == "passwordsdontmatch") {
                    echo "Paroolid ei ühti!";
                } else if ($_GET["error"] == "usernametaken") {
                    echo "Nimi on hõivatud!";
                } else if ($_GET["error"] == "stmtfailed") {
                    echo "Midagi läks valesti, proovige uuesti";
                } else if ($_GET["error"] == "none") {
                    echo "Teie olete registreeritud!";
                }
            }
            ?>

            <input type="submit" name="submit" value="Registreeru" class="lisa-button">
            <a href="login.php" class="signup">Autoriseerimine</a>
        </form>
    </div>
</body>