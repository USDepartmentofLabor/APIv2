<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form', 'id'=>'signup-form');
$firstname = array('class'=>'form-control', 'name' => 'first_name', 'id'=>'first_name', 'placeholder'=>'First Name');
$lastname = array('class'=>'form-control', 'name' => 'last_name', 'id'=>'last_name', 'placeholder'=>'Last Name');
$email = array('class'=>'form-control', 'name' => 'email_address', 'id'=>'email_address', 'placeholder'=>'Email Address');
$user = array('class'=>'form-control', 'name' => 'username', 'id'=>'username', 'placeholder'=>'Email Address');
$pass = array('class'=>'form-control', 'name' => 'password', 'id'=>'password', 'placeholder' => 'Password');
$pass2 = array('class'=>'form-control', 'name' => 'password2', 'id'=>'password2', 'placeholder' => 'Confirm password');
$attr_FormSubmit = array('class'=>'btn btn-lg btn-warning btn-block', 'value' =>'Request Access', 'type'=>'submit');

?>
<!-- Signup form for administrative access -->
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                        	<i class="fa fa-key fa-fw"></i> <?= $subtitle; ?>
                        </h3>
                    </div>
                    <div class="panel-body">
                            <fieldset>
                            	<?= form_open("{$action}", $attr_FormOpen); ?>
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-user"></i></span>
                                	<?= form_input($firstname); ?>                                    
                                </div>
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-user"></i></span>
                                	<?= form_input($lastname); ?>                                    
                                </div>
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                	<?= form_input($email); ?>                                    
                                </div>                                                            
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-user"></i></span>
                                	<?= form_input($user); ?>                                    
                                </div>
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-key"></i></span>
                                	<?= form_password($pass); ?>
                                </div>
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-key"></i></span>
                                	<?= form_password($pass2); ?>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <?= form_submit($attr_FormSubmit); ?>
                                <?= form_close(); ?>
                                <?= br(); ?>
                                <?= $error; ?>
                                <?= anchor(base_url()."login", 'Back to Login'); ?>
                            </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
