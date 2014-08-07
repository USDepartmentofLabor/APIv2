<?php 
$attr_FormOpen = array('role'=>'form', 'id'=>'delete-form');
$attr_FormSubmit = array('class'=>'btn btn-danger', 'value' =>'DELETE ROLE', 'type'=>'submit');
$name = array('class'=>'form-control', 'name' => 'name', 'placeholder'=>'Name','required'=>'required','disabled'=>'disabled');
$slug = array('class'=>'form-control', 'name' => 'slug', 'placeholder'=>'Slug','required'=>'required','disabled'=>'disabled');
$description = array('class'=>'form-control', 'name' => 'description', 'placeholder'=>'Description','required'=>'required','disabled'=>'disabled');
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
                        <div class="panel-heading ">
                           <i class="fa fa-trash-o fa-fw "></i> Role ID: &nbsp;<?= $role->role_id?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="role_details-pills" data-toggle="tab">Details</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="role_view-pills">
					              <?= form_open("{$del_role}", $attr_FormOpen); ?>                            
		                            <h4><i class="fa fa-trash-o fa-fw"></i> ID &nbsp;<?= $role->role_id?></h4>
									<div class="form-group input-group">
										<span class="input-group-addon"><i class="fa fa-user"></i></span>
		                            	<?= form_input($name, set_value("name", $role->name)); ?>                                
		                            </div>	 <br>  
									<div class="form-group input-group">
										<span class="input-group-addon"><i class="fa fa-tags"></i></span>
		                            	<?= form_input($slug, set_value("slug", $role->slug)); ?>                                
		                            </div><br>
									<div class="form-group input-group">
										<span class="input-group-addon"><i class="fa fa-file-text"></i></span>
		                            	<?= form_input($description, set_value("description", $role->description)); ?>                                
		                            </div>	
                                </div>
                             	<?= form_submit($attr_FormSubmit); ?>
                                <?php form_close();?>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                          <span> Role ID: &nbsp; <?= $role->role_id ?></span>
                        </div>
                    </div>
                    <?= $link_back; ?>
                </div>
            </div>
            <?= br(); ?>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->