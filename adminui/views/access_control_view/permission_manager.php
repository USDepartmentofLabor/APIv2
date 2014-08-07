<?php

# Set form attributes
$attr_FormOpen = array('perm'=>'form', 'id'=>'signup-form');
$name = array('class'=>'form-control', 'name' => 'name', 'id'=>'name', 'placeholder'=>'Name','required'=>'required');
$slug = array('class'=>'form-control', 'name' => 'slug', 'id'=>'slug', 'placeholder'=>'Slug','required'=>'required');
$description = array('class'=>'form-control', 'name' => 'description', 'id'=>'description', 'placeholder'=>'Description','required'=>'required');
$attr_FormSubmit = array('class'=>'btn btn-primary', 'value' =>'Create Permission', 'type'=>'submit');
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
                <div class="col-lg-12" id="perm_pill" name ="perm_pill">
	                <?php
                        if (isset($_GET["del_success_message"])) {
                            echo "<div class=\"alert alert-success alert-dismissable\">
									<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
									Permission successfully deleted. 
								</div>";
                        } 
                        if (isset($_GET["success_message"])) {
                            echo "<div class=\"alert alert-success alert-dismissable\">
                            		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
									Permission successfully added.
								</div>";
                        }
                 
                      	echo $this->session->flashdata('data'); 
                     ?>                           
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-group fa-fw"></i> <?= $panel_title; ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#permissions" data-toggle="tab">Permissions</a>
                                </li>
                                <li ><a href="#add_permission" data-toggle="tab">Add Permission</a>
                                </li>
                            </ul>                    	
                           <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="permissions">
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
                                <div class="tab-pane fade" id="add_permission">
					                <!-- /.col-lg-4 -->
					                <div class="col-lg-4">
										<div class="panel panel-primary">
											<div class="panel-heading">
					                           <i class="fa fa-plus fa-fw"></i>
					                        </div>
					                        <div class="panel-body">
					                            <fieldset>
					                            	<?= form_open("{$add_perm}", $attr_FormOpen); ?>
					                                <div class="form-group input-group">
					                                	<span class="input-group-addon"><i class="fa fa-group"></i></span>
					                                	<?= form_input($name); ?>                                    
					                                </div>
					                                <div class="form-group input-group">
					                                	<span class="input-group-addon"><i class="fa fa-group"></i></span>
					                                	<?= form_input($slug); ?>                                    
					                                </div>
					                                <div class="form-group input-group">
					                                	<span class="input-group-addon"><i class="fa fa-group"></i></span>
					                                	<?= form_input($description); ?>                                    
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