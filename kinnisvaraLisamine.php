<?php
session_start();
include('zoneconf.php');
global $conn;

if(isSet($_REQUEST["nimetus"]) && !empty($_REQUEST["nimetus"])) {
    $paring = $conn->prepare("INSERT INTO kinnisvara (kinnisvaraNimetus, linn, aadress, pilt, suurus, tubadeArv, hind, ettevote_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $paring->bind_param("ssssiiii", $_REQUEST["nimetus"], $_REQUEST["linn"], $_REQUEST["aadress"]  ,$_REQUEST["pilt"], $_REQUEST["suurus"], $_REQUEST["tubadeArv"], $_REQUEST["hind"], $_REQUEST["ettevote_ID"]);
    $paring->execute();
    header("Location:$_SERVER[PHP_SELF]?success=true");
}

$ettevotted = [];
$ettevote_paring = $conn->query("SELECT ettevote_ID, ettevoteNimetus FROM ettevote");
while ($rida = $ettevote_paring->fetch_assoc()) {
    $ettevotted[] = $rida;
}

?>

<head>
    <meta charset="UTF-8">
    <title>Kinnisvara lisamine</title>
    <link rel="stylesheet" href="style.css">
</head>

<?php if (isset($_SESSION["kasutaja"])) { ?>
<body class="andmed-lisamine">
    <?php
    require ('nav.php');
    ?>

    <div class="andmed-input">
        <h2>Lisa uus kinnisvara</h2>
        <form method="post" action="" class="andmed">
            <div class="form-group">
                <label for="nimetus">Kinnisvara nimetus:</label>
                <input type="text" name="nimetus" id="nimetus" maxlength="50" required>
            </div>

            <div class="form-group">
                <label for="linn">Linn:</label>
                <input type="text" name="linn" id="linn" maxlength="30" required>
            </div>

            <div class="form-group">
                <label for="aadress">Aadress:</label>
                <input type="text" name="aadress" id="aadress" maxlength="50" required>
            </div>

            <div class="form-group">
                <label for="pilt">Pildi URL:</label>
                <input type="text" name="pilt" id="pilt" maxlength="100">
            </div>

            <div class="form-group">
                <label for="suurus">Suurus (m²):</label>
                <input type="number" name="suurus" id="suurus" min="1" max="10000" required>
            </div>

            <div class="form-group">
                <label for="tubadeArv">Tubade arv:</label>
                <input type="number" name="tubadeArv" id="tubadeArv" min="1" max="100" required>
            </div>

            <div class="form-group">
                <label for="hind">Hind (€):</label>
                <input type="number" name="hind" id="hind" min="1" max="999999999" required>
            </div>

            <div class="form-group">
                <label for="ettevote_ID">Ettevõte:</label>
                <select name="ettevote_ID" id="ettevote_ID" required>
                    <option value="">-- Vali ettevõte --</option>
                    <?php foreach ($ettevotted as $ettevote): ?>
                        <option value="<?= $ettevote['ettevote_ID'] ?>">
                            <?= htmlspecialchars($ettevote['ettevoteNimetus']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if (isset($_GET["success"]) && $_GET["success"] == "true"): ?>
            <div class="success-message">Kinnisvara lisatud!</div>
            <?php endif; ?>

            <input type="submit" value="Lisa kinnisvara" class="lisa-button">
        </form>
    </div>
</body>
<?php } ?>