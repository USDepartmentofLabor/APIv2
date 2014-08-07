<?php

# Set form attributes
$attr_FormOpen = array('permission'=>'form');
$uniqueid = array('class'=>'form-control', 'name' => 'perm_id', 'id'=>'disabledInput', 'disabled'=>'disabled', 'placeholder'=>'Permission ID: Disabled');
$hiddenid = array('class'=>'form-control', 'name' => 'perm_id', 'type'=>'hidden');
$name = array('class'=>'form-control', 'name' => 'name', 'placeholder'=>'Name');
$slug = array('class'=>'form-control', 'name' => 'slug', 'placeholder'=>'Slug');
$description = array('class'=>'form-control', 'name' => 'description', 'placeholder'=>'Description');


//$pass = array('class'=>'form-control', 'name' => 'password', 'placeholder' => 'Password');
//$pass2 = array('class'=>'form-control', 'name' => 'password2', 'placeholder' => 'Confirm password');
$attr_FormSubmit = array('class'=>'btn btn-primary', 'value' =>'Save', 'type'=>'submit');
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
                           <i class="fa fa-edit fa-fw"></i> Permission ID: &nbsp;<?= $this->form_data->perm_id; ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#permission-pill" data-toggle="tab">Edit <?=$this->form_data->name; ?></a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">                              
								<h4><i class="fa fa-edit fa-fw"></i> Permission ID:&nbsp; <?= $this->form_data->perm_id; ?> </h4>
                                <div class="tab-pane fade in active" id="perm-pill">
				                <?php
			                        if (isset($_GET["UpdateSuccess"])) {
			                            echo "<div class=\"alert alert-success alert-dismissable\">
			                            		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
												Permission successfully update.
											</div>";
			                        }
			                 
			                      	echo $this->session->flashdata('data'); 
			                     ?>                                                             								
								<?= form_open("".$action ."", $attr_FormOpen); ?>
									<fieldset>
				                	    <?= form_error("name"); ?> 
				                        <?= form_error("slug"); ?>
				                        <?= form_error("description"); ?>

										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-pencil"></i></span>
											<?= form_input($uniqueid, set_value("perm_id")); ?>
											<?= form_input($hiddenid, set_value("perm_id", $this->form_data->perm_id)); ?>                              
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-pencil"></i></span>
											<?= form_input($name, set_value("name", $this->form_data->name)); ?>                                
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-pencil"></i></span>
											<?= form_input($slug, set_value("slug", $this->form_data->slug)); ?>                                
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-pencil"></i></span>
											<?= form_input($description, set_value("description", $this->form_data->description)); ?>                                  
										</div>

										<!-- Change this to a button or input when using this as a form -->
										<?= form_submit($attr_FormSubmit); ?>
										<?= form_close(); ?>
									</fieldset>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                          <span> Permission ID: <?= $this->form_data->perm_id; ?></span>
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