<?php
require_once( __DIR__ . '/../services/AuthenticationService.php');
require_once( __DIR__ . '/../services/Defaults.php');


/**
 *  Indicates if the current user is logged in or not
 */
$isLogged = AuthenticationService::isLoggedIn();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?php echo Defaults::DEFAULT_BASE_URL; ?>"/>BD2017</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
            <a class="nav-link" href="<?php echo Defaults::DEFAULT_BASE_URL; ?>">Home <span class="sr-only">(current)</span></a>
        </li>
        <?php
        if (!$isLogged) {
            echo '<li class="nav-item"><a class="nav-link" href="' . Defaults::DEFAULT_BASE_URL . '/php/login.php">Login</a></li>';
            echo '<li class="nav-item"><a class="nav-link" href="' . Defaults::DEFAULT_BASE_URL . '/php/register.php">Sign up</a></li>';
        } else {
            echo '<li class="nav-item"><a class="nav-link" href="' . Defaults::DEFAULT_BASE_URL . '/php/rest/logout.php">Logout</a></li>';
            echo '<li class="nav-item"><a class="nav-link" href="' . Defaults::DEFAULT_BASE_URL . '/php/add-book.php">Add</a></li>';
        }
        ?>
    </ul>
    <?php
    if ($isLogged) {
        require(__DIR__ . '/search-box.php');
    }
    ?>

</div>
</nav>