 <?php 
 
 # Set form attributes
 $attr_FormOpen = array('role'=>'form', 'id' => 'login-form');
 $attr_Username	= array('class'=>'form-control', 'placeholder'=>'eMail', 'name'=>'username', 'id'=>'username', 'autofocus'=>'autofocus');
 $attr_Password	= array('class'=>'form-control', 'placeholder'=>'Password', 'name'=>'password', 'id'=> 'password', 'type'=>'password', 'value'=>'');
 $attr_RememberMe = array('name'=>'remember', 'checked'=> TRUE, 'value'=>'Remember Me',);
 $attr_FormSubmit = array('class'=>'btn btn-lg btn-success btn-block', 'value' =>'Login', 'type'=>'submit');
 
 ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-key fa-fw"></i> <?= $subtitle; ?></h3>
                    </div>
                    <div class="panel-body">
                    <?= form_open("{$action}", $attr_FormOpen); ?>
                            <fieldset>
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-user"></i></span>
                                	<?= form_input($attr_Username); ?>                                    
                                </div>
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-user"></i></span>
                                	<?= form_password($attr_Password); ?>
                                </div>
                                <div class="checkbox">
                                    <label>
                                    	<?= form_checkbox($attr_RememberMe); ?> Remember Me
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <?= form_submit($attr_FormSubmit); ?>
                                <?= form_close(); ?>
                                <?= br(); ?>
                                <?= anchor(base_url()."request/", 'Request Access'); ?>
                                <?= br(); ?>
                                <?= anchor(base_url()."request/password_reset", 'Forgot Password'); ?>
                                <?= br(); ?>
                                <?php
                                	if (isset($_GET["PasswordChange"])) {
                                	echo "<div class=\"alert alert-success alert-dismissable\">
										<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
											Your password has been changed
										</div>";
                                	}
                                ?>
                                <?= validation_errors('<p class="error">'); ?>
                            </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>