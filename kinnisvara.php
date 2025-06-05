<?php
session_start();
require('zoneconf.php');
require ('sorteerimine.php');
global $conn;

$sorttulp="kinnisvaraNimetus";
$otsisona="";

if(isSet($_REQUEST["sort"])) {
    $sorttulp=$_REQUEST["sort"];
}

if(isSet($_REQUEST["otsisona"])) {
    $otsisona=$_REQUEST["otsisona"];
}

$majad = kysiKaupadeAndmed($sorttulp, $otsisona);

function kustutaKinnisvara($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM kinnisvara WHERE kinnisvara_ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

function muudaKinnisvara($id, $nimetus, $linn, $aadress, $suurus, $tubadeArv, $hind, $ettevote_ID) {
    global $conn;
    $stmt = $conn->prepare("UPDATE kinnisvara SET kinnisvaraNimetus=?, linn=?, aadress=?, suurus=?, tubadeArv=?, hind=?, ettevote_ID=? WHERE kinnisvara_ID=?");
    $stmt->bind_param("sssiiiii", $nimetus, $linn, $aadress, $suurus, $tubadeArv, $hind, $ettevote_ID, $id);
    $stmt->execute();
}

if (isset($_GET["kustutusid"])) {
    kustutaKinnisvara($_GET["kustutusid"]);
    header("Location: kinnisvara.php");
    exit();
}

if (isset($_POST["muutmine"])) {
    muudaKinnisvara(
        $_POST["muudetudid"],
        $_POST["kinnisvaraNimetus"],
        $_POST["linn"],
        $_POST["aadress"],
        $_POST["suurus"],
        $_POST["tubadeArv"],
        $_POST["hind"],
        $_POST["ettevote_ID"]
    );
    header("Location: kinnisvara.php");
    exit();
}

?>

<script>
    // sorteerimise kuvamine vajutamisel
    document.addEventListener("DOMContentLoaded", function () {
        const filterButton = document.getElementById("filter-button");
        const filterPanel = document.getElementById("filter-panel");

        filterButton.addEventListener("click", function () {
            filterPanel.classList.toggle("visible");
        });
    });

    // täht
    function toggleStar(button) {
        const img = button.querySelector('img');
        const isActive = button.classList.toggle('active');
        img.src = isActive ? 'content/pictures/toggled-star.png' : 'content/pictures/star.png';
    }

    // scroll
    function saveScrollPosition() {
        localStorage.setItem('scrollPosition', window.pageYOffset);
    }

    function restoreScrollPosition() {
        const scrollPosition = localStorage.getItem('scrollPosition');
        if (scrollPosition) {
            window.scrollTo(0, parseInt(scrollPosition));
            localStorage.removeItem('scrollPosition');
        }
    }

    window.addEventListener('load', restoreScrollPosition);

    document.addEventListener('DOMContentLoaded', function() {
        const navigationLinks = document.querySelectorAll('a[href*="muutmisid="], a[href*="kustutusid="], a.signup[href="kinnisvara.php"]');
        
        navigationLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                saveScrollPosition();
            });
        });
        
        // salvesta nupp
        const forms = document.querySelectorAll('form');
        forms.forEach(function(form) {
            const salvestaButton = form.querySelector('input[name="muutmine"][value="Salvesta"]');
            if (salvestaButton) {
                form.addEventListener('submit', function() {
                    saveScrollPosition();
                });
            }
        });
    });

</script>

<head>
    <meta charset="UTF-8">
    <title>Kinnisvara</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="kinnisvara">
    <!-- navigeerimismenüü -->
    <?php
    require ('nav.php');
    ?>

    <main>
        <div class="search">
            <div class="search-bar">
                <div class="search-container">
                    <button id="filter-button"><img src="content/pictures/settings-icon.png" alt=""></button>
                    <form action="kinnisvara.php" method="get" class="search-form">
                        <label for="search-input"></label>
                        <input type="text" name="otsisona" id="search-input" placeholder="Kinnisvara nimetus, ettevõte nimetus, tubade arv...">
                        <button id="search-button" type="submit"><img src="content/pictures/search-icon.png" alt=""></button>
                    </form>
                </div>
            </div>

            <div id="filter-panel">
                <div class="filter-content">
                    <div class="filter-title">Sorteeri</div>
                    <div class="filter-buttons">
                        <a href="kinnisvara.php?sort=kinnisvaraNimetus" class="filter-option">Nimetus: kinnisvara</a>
                        <a href="kinnisvara.php?sort=ettevoteNimetus" class="filter-option">Nimetus: ettevõte</a>
                        <a href="kinnisvara.php?sort=hind_asc" class="filter-option">Hind: madal - kõrge</a>
                        <a href="kinnisvara.php?sort=hind_desc" class="filter-option">Hind: kõrge - madal</a>
                        <a href="kinnisvara.php?sort=suurus" class="filter-option">Suurus: väike - suur</a>
                        <a href="kinnisvara.php?sort=suurus_desc" class="filter-option">Suurus: suur - väike</a>
                        <a href="kinnisvara.php?sort=tubadeArv" class="filter-option">Toad: vähem - rohkem</a>
                        <a href="kinnisvara.php?sort=tubadeArv_desc" class="filter-option">Toad: rohkem - vähem</a>
                    </div>
                    <div class="filter-actions">
                        <a href="kinnisvara.php?sort=kinnisvaraNimetus" class="btn-reset">Lähtesta</a>
                    </div>
                </div>
            </div>
        </div>

        <?php
        global $conn;
        $paring = $conn->prepare('SELECT k.kinnisvara_ID, k.kinnisvaraNimetus, k.linn, k.aadress, k.pilt, k.suurus, k.tubadeArv, k.hind,
    e.ettevoteNimetus
 FROM kinnisvara k
 JOIN ettevote e ON k.ettevote_ID = e.ettevote_ID');
        $paring->bind_result($kinnisvara_ID, $kinnisvaraNimetus, $linn, $aadress, $pilt, $suurus, $tubadeArv, $hind, $ettevoteNimetus);
        $paring->execute();
        $paring->store_result();

        // ettevõted
        $ettevotted = [];
        $ettevote_paring = $conn->query("SELECT ettevote_ID, ettevoteNimetus FROM ettevote");
        while ($rida = $ettevote_paring->fetch_assoc()) {
            $ettevotted[] = $rida;
        }
        ?>

        <?php foreach($majad as $maja): ?>
            <?php if (isset($_GET["muutmisid"]) && intval($_GET["muutmisid"]) == $maja->kinnisvara_ID): ?>
                <!-- vorm redigeerimiseks -->
                <form action="kinnisvara.php" method="post" class="edit-form">
                    <input type="hidden" name="muudetudid" value="<?=$maja->kinnisvara_ID ?>">
                    <div class="cards">
                        <div class="card">
                            <div class="property-image">
                                <img src="<?=$maja->pilt ?>" alt="property">
                            </div>

                            <div class="property-info">
                                <div class="salvestaKatkesta">
                                    <!-- ettevõted -->
                                    <div class="form-group">
                                        <label for="ettevote_ID">Ettevõte nimetus:</label>
                                        <select name="ettevote_ID" class="muuda-input" id="ettevote_ID">
                                            <?php foreach ($ettevotted as $ettevote): ?>
                                                <option value="<?= $ettevote['ettevote_ID'] ?>" <?= $ettevote['ettevoteNimetus'] == $maja->ettevoteNimetus ? 'selected' : '' ?>>
                                                    <?= $ettevote['ettevoteNimetus'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="kinnisvaraNimetus">Kinnisvara nimetus:</label>
                                        <input type="text" name="kinnisvaraNimetus" class="muuda-input" id="kinnisvaraNimetus" value="<?=$maja->kinnisvaraNimetus ?>" maxlength="50" />
                                    </div>

                                    <div class="form-group">
                                        <label for="linn">Linn:</label>
                                        <input type="text" name="linn" class="muuda-input" id="linn" value="<?=$maja->linn ?>" maxlength="30" />
                                    </div>

                                    <div class="form-group">
                                        <label for="aadress">Aadress:</label>
                                        <input type="text" name="aadress" class="muuda-input" id="aadress" value="<?=$maja->aadress ?>" maxlength="50" />
                                    </div>

                                    <div class="form-group">
                                        <label for="suurus">Suurus (m²):</label>
                                        <input type="number" name="suurus" class="muuda-input" id="suurus" value="<?=$maja->suurus ?>" min="1" max="10000" />
                                    </div>

                                    <div class="form-group">
                                        <label for="tubadeArv">Tubade arv:</label>
                                        <input type="number" name="tubadeArv" class="muuda-input" id="tubadeArv" value="<?=$maja->tubadeArv ?>" min="1" max="100" />
                                    </div>

                                    <div class="form-group">
                                        <label for="hind">Hind (€):</label>
                                        <input type="number" name="hind" class="muuda-input" id="hind" value="<?=$maja->hind ?>" min="1" max="999999999" />
                                    </div>

                                    <input type="submit" name="muutmine" class="lisa-button" value="Salvesta" />
                                    <a href="kinnisvara.php" class="signup">Katkesta</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php else: ?>

                <!-- tavaline vaade -->
                <div class='cards'>
                    <div class='card'>
                        <div class='property-image'>
                            <img src='<?=$maja->pilt ?>' alt='property'>
                        </div>

                        <div class='property-info'>
                            <div class='ettevote'>
                                <?= $maja->ettevoteNimetus ?>
                                <?php if (isset($_SESSION["kasutaja"])) { ?>
                                    <button class="star" onclick="toggleStar(this)" aria-label="salvesta kinnisvara">
                                        <img src="content/pictures/star.png" alt="salvesta täht">
                                    </button>
                                <?php } ?>
                            </div>

                            <div class='nimetus'><?= $maja->kinnisvaraNimetus ?></div>

                            <div class='address'><?= $maja->aadress . ", " . $maja->linn ?></div>

                            <div class='property-details'>
                                <div class='rooms'><img src='content/pictures/room-icon.png' alt='' id='room-icon'><?= $maja->tubadeArv ?></div>
                                <div class='area'><?= $maja->suurus ?>m²</div>
                            </div>

                            <div class='buy-price'>
                                <button class='buy' onclick="return confirm('Kas soovite kinnisvara osta?')">Osta</button>
                                <div class='price'><?= $maja->hind ?>€</div>
                            </div>

                            <!-- halduse lingid -->
                            <?php if (!empty($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                                <div class='admin-links'>
                                    <a href='kinnisvara.php?muutmisid=<?= $maja->kinnisvara_ID ?>'>Muuda</a> |
                                    <a href='kinnisvara.php?kustutusid=<?= $maja->kinnisvara_ID ?>' onclick="return confirm('Kas soovite kinnisvara kustutada?')">Kustuta</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php
        $conn->close();
        ?>
    </main>
</body>

<?php
include ('footer.php');
?>