<?php
    require_once( __DIR__ . '/../services/Defaults.php');
?>
<form action="<?php echo Defaults::DEFAULT_BASE_URL . '/php/rest/login.php'; ?>" method="post" name="LoginForm">
    <h3>Login</h3>
    <br>
    <div class="form-group">
        <input type="text" class="form-control form-control-sm" name="Username" placeholder="Username" required="" autofocus="" />
    </div>
    <div class="form-group">
        <input type="password" class="form-control form-control-sm" name="Password" placeholder="Password" required=""/>     		  
    </div>
    <button class="btn btn-sm btn-primary"  name="Submit" value="Login" type="Submit">Sign in</button>  			
</form>