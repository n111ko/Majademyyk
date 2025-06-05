<!-- navigeerimismenüü -->
<nav class="menu">
    <div class="logo">
        <a href="index.php">DreamKodu</a>
    </div>
    <div class="left-menu">
        <ul>
            <li>
                <a href="kinnisvara.php">Kinnisvara</a>
            </li>
            <li><a href="#" <!--target="_blank"-->Teenused</a></li>
            <li><a href="#">Kontaktid</a></li>

            <?php if (!empty($_SESSION['kasutaja'])): ?>
                <li><a href="kinnisvaraLisamine.php">Müü</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <?php
    if (isset($_SESSION["kasutaja"])) {
        echo '<div class="right-menu">';
        echo '<ul>';

        echo '<li>';
        echo $_SESSION['kasutaja'];
        echo '</li>';

        echo '<li>';
        echo '<a href="#" class="menu-button">';
        echo '<img src="content/pictures/profile-icon.png" alt="Profile">';
        echo '</a>';
        echo '</li>';

        echo '<li>';
        echo '<form action="logout.php" method="post" class="logform">';
        echo '<button type="submit" name="logout" class="logout-button">';
        echo '<img src="content/pictures/logout-icon.png" alt="Logi välja">';
        echo '</button>';
        echo '</form>';
        echo '</li>';

        echo '</ul>';
        echo '</div>';
    } else {
        echo '<div class="right-menu">';
        echo '<ul>';

        echo '<li>';
        echo '<a href="login.php" class="menu-button">';
        echo '<img src="content/pictures/login-icon.png" alt="Profile">';
        echo '</a>';
        echo '</li>';

        echo '</ul>';
        echo '</div>';
    }

    ?>
</nav>