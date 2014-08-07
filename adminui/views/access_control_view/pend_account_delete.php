<?php 
$attr_FormOpen = array('role'=>'form', 'id'=>'delete-form');
$attr_FormSubmit = array('class'=>'btn btn-danger', 'value' =>'REMOVE PENDING ACCOUNT', 'type'=>'submit');

$hiddenid = array('class'=>'form-control', 'name' => 'user_id', 'type'=>'hidden','disabled'=>'disabled');
$firstname = array('class'=>'form-control', 'name' => 'first_name', 'placeholder'=>'First Name','required'=>'required','disabled'=>'disabled');
$lastname = array('class'=>'form-control', 'name' => 'last_name', 'placeholder'=>'Last Name','required'=>'required','disabled'=>'disabled');
$email = array('class'=>'form-control', 'name' => 'email_address', 'placeholder'=>'Email Address','required'=>'required','disabled'=>'disabled');
$username = array('class'=>'form-control', 'name' => 'username', 'placeholder'=>'Username', 'disabled'=>'disabled','disabled'=>'disabled');
$status = array('class'=>'form-control', 'name' => 'status', 'placeholder'=>'Status','required'=>'required','disabled'=>'disabled');

$date_requested = array('class'=>'form-control', 'name' => 'date_requested', 'id'=>'date_requested', 'placeholder'=>'Date Requested', 'disabled'=>'disabled','required'=>'required');

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
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                           <i class="fa fa-user fa-fw"></i> <?= $this->form_data->first_name. " ".$this->form_data->last_name; ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#profile-pills" data-toggle="tab">Remove Pending Account</a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="profile-pills">
									<h4><i class="fa fa-trash-o fa-fw"></i> Profile</h4><br>
									<?= form_open("".$del_pending_account ."", $attr_FormOpen); ?>
									<fieldset>
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
											<?= form_input($date_requested, set_value("date_requested", $this->form_data->date_requested)); ?>                                   
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
											<span class="input-group-addon"><i class="fa fa-lock"></i></span>
											<?php 	
												echo form_input($status, set_value("status", "User Disabled"));																					
											?>
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
            <?= br(); ?>
        </div>
        <!-- /#page-wrapper -->
    <!-- /#wrapper -->