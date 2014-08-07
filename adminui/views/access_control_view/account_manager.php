<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form', 'id'=>'signup-form');
$firstname = array('class'=>'form-control', 'name' => 'first_name', 'id'=>'first_name', 'placeholder'=>'First Name','required'=>'required');
$lastname = array('class'=>'form-control', 'name' => 'last_name', 'id'=>'last_name', 'placeholder'=>'Last Name','required'=>'required');
$email = array('class'=>'form-control', 'name' => 'email_address', 'id'=>'email_address', 'placeholder'=>'Email Address','required'=>'required');
$user = array('class'=>'form-control', 'name' => 'username', 'id'=>'username', 'placeholder'=>'Email Address','required'=>'required');
$pass = array('class'=>'form-control', 'name' => 'password', 'id'=>'password', 'placeholder' => 'Password','required'=>'required');
$pass2 = array('class'=>'form-control', 'name' => 'password2', 'id'=>'password2', 'placeholder' => 'Confirm password','required'=>'required');
$attr_FormSubmit = array('class'=>'btn btn-primary', 'value' =>'Create Admin', 'type'=>'submit');

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
            <!-- start tab view for access control list -->
                <div class="col-lg-12">
           		<?php 
                	if (isset($_GET["del_success_message"]) == TRUE) {
                  		echo "<div class=\"alert alert-success alert-dismissable\">
								<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
								Account successfully deactivated.
							</div>";
                 	} 
                 	if (isset($_GET["success_message"]) == TRUE) {
                    	echo "<div class=\"alert alert-success alert-dismissable\">
								<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
								admin successfully added. 
							</div>";
                 	}                 	
                 	if (isset($_GET["admin_error_message"])) {
						echo "<div class=\"alert alert-danger alert-dismissable\">
								<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
								Error, user already exist...
							</div>";
                 	}           	
                  	if (isset($_GET["reg_error_message"])) {
                   		echo "<div class=\"alert alert-danger alert-dismissable\">
								<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
								Error, user already requested access. " .anchor("".base_url()."access_control/admin/pending_request", "Pending Request"). "
						</div>";
                     }
            	?>                 
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-group fa-fw"></i> <?= $panel_title; ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#users" data-toggle="tab">Users</a>
                                </li>
                                <li><a href="#add_admin" data-toggle="tab">Add User</a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="users">
                                	<?= br(); ?>
					            	<div><?= $pagination; ?></div>
					            	<?= br(); ?>
					                <div class="col-lg-12">
					                    <div class="panel panel-default">
					                        <!-- /.panel-heading -->
					                        <div class="panel-body">
					                            <div class="table-responsive">
												<?= $table; ?>
					                            </div>
					                            <!-- /.table-responsive -->
					                        </div>
					                        <!-- /.panel-body -->
					                    </div>
					                    <!-- /.panel -->
					                </div>
					                <!-- /.col-lg-12 -->
                                </div>
                                <div class="tab-pane fade" id="add_admin">
					                <!-- /.col-lg-4 -->
					                <div class="col-lg-4">
					                	<?= br(); ?>
					                    <div class="panel panel-primary">
											<div class="panel-heading">
					                           <i class="fa fa-user fa-fw"></i>
					                        </div>
					                        <div class="panel-body">
					                            <fieldset>
					                            	<?= form_open("{$add_admin_process}", $attr_FormOpen); ?>
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
			                             <div class="form-group input-group">
			                             	<span class="input-group-addon"><i class="fa fa-group"></i></span>
												<select id="roles"  name="roles[]" class="form-control" required size = "<?=count($role_list)?>" onChange="change_perms()">
													<? foreach($role_list as $role): ?>
													<option name="<?= $role->name;?>"  id="<?= $role->name;?>"value="<?= $role->role_id; ?>" <?= isset($role->set) ? 'selected="selected"' : NULL; ?>><?= $role->name; ?></option>
													<? endforeach; ?>
												</select>
			                            </div>
										<div><strong >Allowable permissions by role type: </strong><br>
										<span>To edit this list please go to the Role Manager page.</span><br></div>																				 											                            
			                             <div class="form-group input-group height:100%" >
			                             	<span class="input-group-addon"><i class="fa fa-group"></i></span>
												<select id="perms" name="perms" multiple="multiple" class="form-control" size = "<?=count($perm_list)?>" disabled required>
													<? foreach($perm_list as $perm): ?>
													<option value="<?= $perm->perm_id; ?>" <?= (!empty($perm->set)) ? 'selected="selected"' : NULL; ?>><?= $perm->name; ?></option>
													<? endforeach; ?>
												</select>
			                            </div>					                                
					                                <!-- Change this to a button or input when using this as a form -->
					                                <?= form_submit($attr_FormSubmit); ?>
					                                <?= form_close(); ?>
					                                <?= br(); ?>

					                            </fieldset>
					                        </div>
					                    </div>
					                </div>
					                <!-- /.col-lg-4 -->									
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- end tab view -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->