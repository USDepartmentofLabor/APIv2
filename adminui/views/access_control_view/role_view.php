 <?php 
 $attr_FormOpen = array('role'=>'form');
 $hiddenid = array('class'=>'form-control', 'name' => 'user_id', 'type'=>'hidden','disabled'=>'disabled');
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
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                           <i class="fa fa-search fa-fw"></i> Role ID: &nbsp;<?= $role->role_id?>
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

		                            <h4><i class="fa fa-search fa-fw"></i> ID &nbsp;<?= $role->role_id?></h4><br>
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
                            </div>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                           Role ID: &nbsp; <?= $role->role_id ?>
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