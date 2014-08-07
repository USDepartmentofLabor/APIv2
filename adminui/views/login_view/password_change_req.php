<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form', 'id'=>'password-change');
$currpass = array('class'=>'form-control', 'name' => 'current_password', 'id'=>'current_password', 'placeholder' => 'Current Password');
$pass = array('class'=>'form-control', 'name' => 'password', 'id'=>'password', 'placeholder' => 'New Password');
$pass2 = array('class'=>'form-control', 'name' => 'password2', 'id'=>'password2', 'placeholder' => 'Confirm Password');
$hiddenuid = array('class'=>'form-control', 'name' => 'username', 'type'=>'hidden');
$attr_FormSubmit = array('class'=>'btn btn-lg btn-primary btn-block', 'value' =>'Change Password', 'type'=>'submit');

?>
<!-- Signup form for administrative access -->
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                        	<i class="fa fa-key fa-fw"></i> <?= $title; ?>
                        </h3>
                    </div>
                    <div class="panel-body">
                            <fieldset>
                            	<?= form_open("{$action}", $attr_FormOpen); ?>
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-key"></i></span>
                                	<?= form_password($currpass); ?>
                                </div>
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-key"></i></span>
                                	<?= form_password($pass); ?>
                                </div>
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-key"></i></span>
                                	<?= form_password($pass2); ?>
                                	<?= form_input($hiddenuid, set_value("username", $user)); ?>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <?= form_submit($attr_FormSubmit); ?>
                                <?= form_close(); ?>
                                <?= br(); ?>
                                <?= anchor(base_url()."login", 'Back to Login'); ?>
		            		    <?= br(); ?>
				                <?= $error; ?>
				                <?= form_error("current_password"); ?>
		                	    <?= form_error("password"); ?> 
		                        <?= form_error("password2"); ?>
                            </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
