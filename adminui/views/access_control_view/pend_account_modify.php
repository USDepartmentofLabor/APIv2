<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form', 'id'=>'pending_account-form');
$uniqueid = array('class'=>'form-control', 'name' => 'user_id', 'id'=>'disabledInput', 'disabled'=>'disabled', 'placeholder'=>'Pending User ID','required'=>'required');
$hiddenid = array('class'=>'form-control', 'name' => 'user_id', 'type'=>'hidden','required'=>'required');
$firstname = array('class'=>'form-control', 'name' => 'first_name', 'id'=>'first_name', 'placeholder'=>'First Name','required'=>'required');
$lastname = array('class'=>'form-control', 'name' => 'last_name', 'id'=>'last_name', 'placeholder'=>'Last Name','required'=>'required');
$email = array('class'=>'form-control', 'name' => 'email_address', 'id'=>'email_address', 'placeholder'=>'Email Address','required'=>'required');
$date_requested = array('class'=>'form-control', 'name' => 'date_requested', 'id'=>'date_requested', 'placeholder'=>'Date Requested', 'disabled'=>'disabled','required'=>'required');
$username = array('class'=>'form-control', 'name' => 'username', 'placeholder'=>'Username','required'=>'required');
$userstatus = array('class'=>'form-control', 'name' => 'status', 'placeholder'=>'Account Status','required'=>'required');
$approveRequest = array('class'=>'btn btn-success', 'name' => 'approved', 'value' =>'Approve Request', 'type'=>'submit');
//$DenyRequest = array('class'=>'btn btn-danger', 'name' => 'denied', 'value' =>'Deny Request', 'type'=>'submit');

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
                	    <?= form_error("first_name"); ?> 
                        <?= form_error("last_name"); ?>
                        <?= form_error("email_address"); ?>
                        <?= form_error("username"); ?>
		                <?= $success; ?>
		                <?= br(); ?>
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
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="profile-pills">
								<h4><i class="fa fa-user fa-fw"></i> Profile</h4>
								<?= form_open("".$action ."", $attr_FormOpen); ?>
									<fieldset>
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
											<?= form_input($date_requested, set_value("date_requested", $this->form_data->date_requested)); ?>                                   
										</div>
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
											<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<?= form_input($username, set_value("username", $this->form_data->username)); ?>                                  
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
											<?= form_input($email, set_value("email_address", $this->form_data->email_address)); ?>                                   
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
										<?= form_submit($approveRequest); ?>
										<?= form_close(); ?>
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