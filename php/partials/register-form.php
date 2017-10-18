<?php
    require_once( __DIR__ . '/../services/Defaults.php');
?>
<div class="row">
    <div class="col-md-6 ml-md-auto mr-md-auto">

        <form action="<?php echo Defaults::DEFAULT_BASE_URL . '/php/rest/register.php'; ?>" method="post" name="RegisterForm">
            <h3>Sign Up</h3>
            <br>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" name="FirstName" placeholder="First name" autofocus="true" required="true" autocomplete="off">
            </div>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" name="LastName" placeholder="Last name" autofocus="true" required="true" autocomplete="off"/>
            </div>
            <div class="form-group">
                <input type="date" class="form-control form-control-sm" name="BirthDate" placeholder="Birth date" autofocus="true" required="true" />
            </div>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" name="Username" placeholder="Username" autofocus="true" required="true" autocomplete="off"/>
            </div>
            <div class="form-group">
                <input type="email" class="form-control form-control-sm" name="Email" placeholder="Email" required="true" autocomplete="off"/>
            </div>
            <div class="form-group">
                <input type="password" class="form-control form-control-sm" name="Password" placeholder="Password" required="true" autocomplete="off"/>     
            </div>		  
            <button class="btn btn-sm btn-primary"  name="Submit" value="SignUp" type="Submit">Register</button>  			
        </form>
    </div>
</div>