<?php
    require_once(__DIR__ . '/services/AuthenticationService.php');
    require_once(__DIR__ . '/services/Defaults.php');


    if (!AuthenticationService::isLoggedIn())
        header("Location:" . Defaults::DEFAULT_BASE_URL);
?>

<!DOCTYPE HTML>
<html>
    <head>
        <!-- Include CSS -->
        <?php require(__DIR__ . '/../php/styles.php') ?>
        <!-- Include JS -->
        <?php require(__DIR__ . '/../php/scripts.php') ?>
        <title>Ricerca libri - Book details</title>
    </head>
    <body>
        <!-- Include navbar section -->
        <?php require(__DIR__ . '/../php/partials/navbar.php') ?>
        <div class="container text-center">
            <?php
                if (isset($_GET["errors"])) {
                    include(__DIR__ . '/../php/partials/messages/item-added-warn.php');
                } else if (isset($_GET["added"])) {
                    include(__DIR__ . '/../php/partials/messages/item-added-success.php');
                } else {
                    echo '<div class="row">';
                    include(__DIR__ . '/../php/partials/add-book-form.php');
                    include(__DIR__ . '/../php/partials/add-author-form.php');
                    echo '</div>';
                }
            ?>
        </div>
    </body>
</html>