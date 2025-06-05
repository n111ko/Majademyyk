<?php
session_start();
require('zoneconf.php');
global $conn;
?>

<!DOCTYPE html>
<html lang="et">
    <head>
        <meta charset="UTF-8">
        <title>Koduleht</title>
        <link rel="stylesheet" href="style.css">
        <script defer src="kinnisvaraOptions.js"></script>
    </head>

    <body class="main">
        <!-- navigeerimismenüü -->
        <?php
        require ('nav.php');
        ?>

        <!-- skript, et põhilehel olev pilt kerimise ajal üles tõuseks -->
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const updateBackground = () => {
                    const scrollY = window.scrollY;
                    document.body.style.backgroundPosition = `center ${-scrollY * 0.3}px`;
                };

                // esialgne positsioon
                updateBackground();

                // uuendada scrolliga
                window.addEventListener("scroll", updateBackground);
            });
        </script>

        <!-- sisu -->
        <main>
            <section>
                <div class="welcome">
                    Teie lugu algab õigest kohast.
                    <br>
                    <a href="kinnisvara.php">Alusta</a>
                </div>
            </section>

            <section class="main-info">
                <div class="hidden">
                    Kas otsite ärihoonet, uut kontorit, poodi, ladu või lihtsalt huvitavat investeeringut? Olete jõudnud õigesse kohta! Siit leiate erinevaid pakkumisi-alates hubastest elamutest kuni avarate kaubandusobjektideni erinevates linnades ja piirkondades. Oleme kogunud kõik ühte kohta, et saaksite rahulikult valida, võrrelda ja leida, mis sobib teie ülesannetega ideaalselt.
                </div>

                <div class="hidden2">
                    <?php
                    global $conn;
                    $paring = $conn->prepare('SELECT kinnisvara_ID, kinnisvaraNimetus, linn, aadress, pilt, suurus, tubadeArv, hind FROM kinnisvara LIMIT 3');
                    $paring->bind_result($kinnisvara_ID, $nimetus, $linn, $aadress, $pilt, $suurus, $tubade_arv, $hind);
                    $paring->execute();

                    echo "<div class='cards-vert'>";

                    while($paring->fetch()) {
                        echo "<div class='card-vert'>";

                        echo "<div class='property-image-vert'>";
                        echo "<img src='$pilt' alt='property'>";
                        echo "</div>";

                        echo "<div class='property-info-vert'>";
                        echo "<div class='nimetus-vert'>". $nimetus, $linn ."</div>";
                        echo "<div class='address-vert'>". $aadress ."</div>";

                        echo "<div class='price-vert'>". $hind ."€</div>";

                        echo "<div class='property-details-vert'>";
                        echo "<div class='rooms-vert'>". "<img src='content/pictures/room-icon.png' alt='' id='room-icon-vert'>"  . $tubade_arv ."</div>";
                        echo "<div class='area-vert'>". $suurus ."m²</div>";
                        echo "</div>";

                        echo "<button class='buy-vert'>Osta</button>";
                        echo "</div>";

                        echo "</div>";
                    }

                    echo "</div>";
                    ?>
                </div>
            </section>
        </main>
    </body>

    <!-- jalus -->
    <?php
    include ('footer.php');
    ?>
</html>