<!-- Reference API generation => \bd2017>php apigen.phar generate -s . -d ./docs -->
<!-- Bootstrap reference => https://getbootstrap.com/docs/4.0/getting-started/introduction/ -->

<?php
require(__DIR__ . '/php/services/AuthenticationService.php');
require(__DIR__ .'/php/services/BooksService.php');


if (!AuthenticationService::isLoggedIn()) {
    header("Location: " . Defaults::DEFAULT_BASE_URL . "/php/login.php");
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <!-- Include CSS -->
        <?php require(__DIR__  . '/php/partials/styles.php') ?>
        <!-- Include JS -->
        <?php require(__DIR__  . '/php/partials/scripts.php') ?>
        <title>Ricerca libri</title>
    </head>
    <body>
        <!-- Include navbar section -->
        <?php require(__DIR__ . '/php/partials/navbar.php') ?>

        <div class="container">
            <?php
            $books = BooksService::research(isset($_GET["keyword"]) && $_GET["keyword"] != null ? $_GET["keyword"] : null);
            if (!$books || count($books) == 0) {
                echo '
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <button type="button" class="close" aria-label="Close">
                ';
                if (isset($_GET["keyword"]) && $_GET["keyword"] != null)
                    echo '<span aria-hidden="true"><a href="' . Defaults::DEFAULT_BASE_URL . '"><i class="fa fa-times"></i></a></span>';
                else
                    echo '<span aria-hidden="true"><a onclick="window.location.reload()"><i class="fa fa-refresh"></i></a></span>';
                echo '    </button>';

                if (isset($_GET["keyword"]) && $_GET["keyword"] != null)
                    echo 'No book found for "' . $_GET["keyword"] . '"...';
                else
                    echo 'No books in the archive...';

                echo '</div>';
            } else {
                foreach ($books as $book) {
                    echo '
                        <div class="row">
                            <div class="col-md-4">
                                <img src="' . $book->image . '" height="200"/>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-xs-12">
                                    <a href="./php/book.php?id=' . $book->id . '"><h4>' . $book->title . '<small>&nbsp;' . $book->author . '</small></h4></a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                    <i>' . $book->genre . '</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ';
                }
            }
            ?>
        </div>
    </body>
</html>