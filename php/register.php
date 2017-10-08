<?php
    require_once(__DIR__ . '/services/AuthenticationService.php');
    require_once(__DIR__ . '/services/Defaults.php');

    if (AuthenticationService::isLoggedIn())
        header('Location: ' . $_SERVER['HTTP_REFERER']);
?>

<!DOCTYPE HTML>
<html>
    <head>
        <!-- Include CSS -->
        <?php require(__DIR__ . '/../php/partials/styles.php') ?>
        <title>Ricerca libri - Register</title>
    </head>
    <body>
        <!-- Include navbar section -->
        <?php require(__DIR__ . '/partials/navbar.php') ?>
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <?php
                        if (isset($_GET["errors"])) {
                            include(__DIR__ . '/partials/messages/item-added-warn.php');
                        }
                        if (isset($_GET["signup"])) {
                            include(__DIR__ . '/partials/messages/register-success.php');
                        } else {
                            include(__DIR__ . '/partials/register-form.php');
                        }
                    ?>
                </div>
            </div>
        </div>
        <!-- Include JS -->
        <?php require(__DIR__ . '/../php/partials/scripts.php') ?>
    </body>
</html>