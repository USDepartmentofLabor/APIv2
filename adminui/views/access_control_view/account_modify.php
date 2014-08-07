<?php

# Set form attributes
$attr_FormOpen = array('account'=>'form');
$uniqueid = array('class'=>'form-control', 'name' => 'user_id', 'id'=>'disabledInput', 'disabled'=>'disabled', 'placeholder'=>'UserID Disabled','required'=>'required');
$hiddenid = array('class'=>'form-control', 'name' => 'user_id', 'type'=>'hidden');
$firstname = array('class'=>'form-control', 'name' => 'first_name', 'placeholder'=>'First Name','required'=>'required');
$lastname = array('class'=>'form-control', 'name' => 'last_name', 'placeholder'=>'Last Name','required'=>'required');
$email = array('class'=>'form-control', 'name' => 'email_address', 'placeholder'=>'Email Address','required'=>'required');

// prohibit admin from changing username if the logged in user is the same as the admin...  
if ($this->form_data->username === $this->session->userdata("username")) {
	$username = array('class'=>'form-control', 'name' => 'username', 'placeholder'=>'Username', 'disabled'=>'disabled');
} else {
	$username = array('class'=>'form-control', 'name' => 'username', 'placeholder'=>'Username');
}
$userstatus = array('class'=>'form-control', 'name' => 'status', 'placeholder'=>'Account Status');
$admin_role = array('type'=>'radio', 'name'=>'admin_roles');
$attr_FormSubmit = array('class'=>'btn btn-primary', 'value' =>'Save', 'type'=>'submit');

# Set form attributes for password change
$attr_FormOpen_passwd = array('role'=>'form', 'id'=>'password-change');
$currpass = array('class'=>'form-control', 'name' => 'current_password', 'id'=>'current_password', 'placeholder' => 'Current Password');
$pass = array('class'=>'form-control', 'name' => 'password', 'id'=>'password', 'placeholder' => 'New Password');
$pass2 = array('class'=>'form-control', 'name' => 'password2', 'id'=>'password2', 'placeholder' => 'Confirm Password');
$hiddenuid = array('class'=>'form-control', 'name' => 'username', 'type'=>'hidden');
$attr_FormSubmit_passwd = array('class'=>'btn btn-primary', 'value' =>'Change Password', 'type'=>'submit');

?>

	<div id="wrapper">
        <!-- /.navbar-static-top -->
		<?php // load dashboard admin menu ?>
		<?php $this->load->view("dashboard_menu"); ?>
        <!-- /.navbar-static-side -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?= $subtitle; ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                           <i class="fa fa-user fa-fw"></i> <?= $this->form_data->first_name. " ".$this->form_data->last_name; ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#profile-pills" data-toggle="tab">Edit Profile</a>
                                </li>
                                <li><a href="#password-pills" data-toggle="tab">Change Password</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="profile-pills">
								<h4><i class="fa fa-user fa-fw"></i> Profile</h4>
								<?= form_open("".$action ."", $attr_FormOpen); ?>
									<fieldset>
				                	    <?= form_error("first_name"); ?> 
				                        <?= form_error("last_name"); ?>
				                        <?= form_error("email_address"); ?>
				                        <?= form_error("username"); ?>
						                <?= $message; ?>
						                <?= $success; ?>
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<?= form_input($uniqueid, set_value("user_id")); ?>
											<?= form_input($hiddenid, set_value("user_id", $this->form_data->user_id)); ?>                              
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<?= form_input($firstname, set_value("first_name", $this->form_data->first_name)); ?>                                
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<?= form_input($lastname, set_value("last_name", $this->form_data->last_name)); ?>                                
										</div>
										<div class="form-group input-group">
											<?php
											if ($this->form_data->status == "1") {
												echo "<span class=\"input-group-addon\"><i class=\"fa fa-unlock\"></i></span>";
											} elseif ($this->form_data->status == "0") {
												echo "<span class=\"input-group-addon\"><i class=\"fa fa-lock\"></i></span>";
											}
											?>
											<select class="form-control" name="status">
											<?php 
											if ($this->form_data->status == "1") {
												echo "<option value=\"{$this->form_data->status}\">User Active</option>";
												echo "<option value=\"0\">User Disable</option>";
											} elseif ($this->form_data->status == "0") {
												echo "<option value=\"{$this->form_data->status}\">User Disabled</option>";
												echo "<option value=\"1\">User Enable</option>";
											}                                    
											?>
											</select>
										 </div>
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<?= form_input($username, set_value("username", $this->form_data->username)); ?>                                 
										</div>
			                             <div class="form-group input-group">
			                             	<span class="input-group-addon"><i class="fa fa-group"></i></span>
												<select id="roles"  name="roles[]" class="form-control" required size = "<?=count($role_list)?>" onChange="change_perms()">
													<? foreach($role_list as $role): ?>
													<option name="<?= $role->name;?>"  id="<?= $role->name;?>"value="<?= $role->role_id; ?>" <?= ($role->set) ? 'selected="selected"' : NULL; ?>><?= $role->name; ?></option>
													<? endforeach; ?>
												</select>
			                            </div>
										<div><label >Allowable permissions by role type: </label><br>
										<span>To edit this list please go to the Role Manager page.</span><br></div>																				 											                            
			                             <div class="form-group input-group height:100%" >
			                             	<span class="input-group-addon"><i class="fa fa-group"></i></span>
												<select id="perms" name="perms[]" multiple="multiple" class="form-control" size = "<?=count($perm_list)?>" disabled required>
													<? foreach($perm_list as $perm): ?>
													<option value="<?= $perm->perm_id; ?>" <?= (!empty($perm->set)) ? 'selected="selected"' : NULL; ?>><?= $perm->name; ?></option>
													<? endforeach; ?>
												</select>
			                            </div>
			                            
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
											<?= form_input($email, set_value("email_address", $this->form_data->email_address)); ?>                                   
										</div>
										<!-- Change this to a button or input when using this as a form -->
										<?= form_submit($attr_FormSubmit); ?>
										<?= form_close(); ?>
									</fieldset>
                                </div>
                                <div class="tab-pane fade" id="password-pills">
                                    <h4><i class="fa fa-key fa-fw"></i> Password Change</h4><br>
		                            <fieldset>
		                                <?php if (isset($_GET["PasswordChangeError"])) { ?>
		                                	<div class="alert alert-danger alert-dismissable">
			                                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										 		Password change failed
											</div>
		                                <?php } elseif (isset($_GET["PasswordChangeSuccess"])) { ?>
		                                	<div class="alert alert-success alert-dismissable">
			                                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										 		Password changed
											</div>			                                
		                                <?php } ?>
		                               <div><label >New password:</label></div>		                        
		                            	<?= form_open("{$action_passwd_chg}", $attr_FormOpen_passwd); ?>
		                                <div class="form-group input-group">
		                                	<span class="input-group-addon"><i class="fa fa-key"></i></span>
		                                	<?= form_password($pass); ?>
		                                </div>
										<div><label >Confirm new password:</label></div>		                                
		                                <div class="form-group input-group">
		                                	<span class="input-group-addon"><i class="fa fa-key"></i></span>
		                                	<?= form_password($pass2); ?>
		                                	<?= form_input($hiddenuid, set_value("username", $this->form_data->username)); ?>
		                                </div>
		                                <!-- Change this to a button or input when using this as a form -->
		                                <?= form_submit($attr_FormSubmit_passwd); ?>
		                                <?= form_close(); ?>
				            		    <?= br(); ?>
						                <?= $passwd_error; ?>
				                	    <?= form_error("password"); ?> 
				                        <?= form_error("password2"); ?>
				                        <?= br(); ?>
		                            </fieldset>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                            <?= $this->form_data->first_name. " ".$this->form_data->last_name; ?>
                        </div>
                    </div>
                    <?= $link_back; ?>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
    </div>
 
    <!-- /#wrapper -->