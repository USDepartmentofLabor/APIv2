<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * APIv2 AdminUI Pilot Role Controller
 *
 * @package	Role Controller
 * @author
 * @link	http://avizium.com/
 * @version 2.0.0-pre
 */

class Role extends CI_controller {

	// number of records per page
	private $limit = 10;
	private $acl_table;

	public function __construct() {
		parent::__construct();
		$this->is_logged_in();

		// bootstrap dashboard and access control model
		$this->load->model("adminuiacl_model","", TRUE);

		$this->acl_table = (object)$this->config->item("acl");
		$this->acl_table =& $this->acl_table->table;
	}

	public function is_logged_in() {
		$is_logged_in = $this->session->userdata("is_logged_in");
		//print_r($this->session->all_userdata()); exit;

		if (!isset($is_logged_in) || $is_logged_in != TRUE) {
			echo "You don't have permission to access this page. ". anchor("/login", "Login Now");
			die();
		}
	}

	public function role_manager($offset = 0) {
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "view_roles")) {
			show_error("Permission denied.", 401);
		}

		//offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);

		// load data
		$role = $this->adminuiacl_model->get_role_paged_list($this->limit, $offset)->result();

		// generate pagination
		$data["title"] = "APIv2 AdminUI Dashboard";
		$data["subtitle"] = "Role Manager";
		$data["panel_title"] = "AdminUI Roles";
		$config["base_url"] = site_url("access_control/role/role_manager/");
		$config["total_rows"] = $this->adminuiacl_model->count_all_roles();
		$config["per_page"] = $this->limit;
		$config["uri_segment"] = $uri_segment;
		$config["anchor_class"] = "class=\"btn btn-primary btn-sm\"";
		$data["add_role_process"] = site_url("access_control/role/add_role_process");
		$data["perm_list"] = $this->adminuiacl_model->get_all_perms();
		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();

		//foreach($data["role_list"] as &$role) {
			//$role->perms = $this->adminuiacl_model->get_role_perms($role->role_id);
		//}

		// generate table data
		$this->table->set_empty("&nbsp;");
		$table_setup = array("table_open" => "<table class=\"table table-striped table-bordered table-hover\">");
		$this->table->set_heading("#No", "Slug", "Name", "Description", "Actions");
		$i = 0 + $offset;
		$view = array("class" => "btn btn-success btn-sm", "type" => "button");
		$update = array("class" => "btn btn-warning btn-sm", "type" => "button");
		$delete = array("class" => "btn btn-danger btn-sm", "type" => "button",
				"data-toggle" => "confirmation");
		foreach ($role as $group) {
			$this->table->add_row(++$i, $group->slug, $group->name, $group->description,
				anchor("access_control/role/role_view/".$group->role_id, "<i class =\"fa fa-search\"></i>", $view)." ".
				anchor("access_control/role/role_update/".$group->role_id, "<i class =\"fa fa-edit\"></i>", $update)." ".
				anchor("access_control/role/role_delete/".$group->role_id, "<i class =\"fa fa-trash-o\"></i>", $delete)
			);
		}
		$this->table->set_template($table_setup);
		$data["table"] = $this->table->generate();

		// load account view
		$data["acl_content"] = "access_control_view/role_manager";
		$this->load->view("access_control/template", $data);
	}

	public function add_role_process(){
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "add_role")) {
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}

		$this->_set_role_fields();
		$this->_set_rules();

		if($this->form_validation->run() == FALSE) {
			$this->load->view("access_control/role/role_manager/1/add_role");
		} else {
			$new_role = array(
				"name"  => $this->input->post("name"),
				"slug"  => $this->input->post("slug"),
				"description" => $this->input->post("description")
			);

			$this->add_role($new_role);
		}
	}

	public function add_role($new_role) {
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "add_role")) {
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}

		$new_role = array(
		"name"  => $this->input->post("name"),
		"slug"  => $this->input->post("slug"),
		"description" => $this->input->post("description")
		);

		$this->adminuiacl_model->add_role($new_role);
		$new_perm_array = $this->input->post('perms');
		$new_role = $this->adminuiacl_model->get_role_by("slug",$this->input->post("slug"));

		foreach ($new_perm_array as $perm) {
			$this->adminuiacl_model->add_role_perm($new_role[0]->role_id,$perm);
		}

		$this->form_data->role_id = $role_id;
		$this->form_data->name =$this->input->post("name");
		$this->form_data->slug = $this->input->post("slug");
		$this->form_data->description = $this->input->post("description");
		redirect("access_control/role/role_manager/1/add_role?success_message=success#add_role_process");

	}

	public function role_view($role_id) {
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "view_roles")) {
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		// set common properties
		$data["title"] = "APIv2 AdminUI Dashboard";
		$data["subtitle"] = "Role View";
		$data["link_back"] = anchor("access_control/role/role_manager/", "<i class=\"fa fa-group fa-fw\"></i> Back to list of Roles", array("class" => "btn btn-primary", "type" => "button"));
		// get role details
		$data["role"] = $this->adminuiacl_model->get_role($role_id);
		$data["role_list"] = $this->adminuiacl_model->get_all_roles();
		$data["perm_list"] = $this->adminuiacl_model->get_all_perms();

		// load view
		//$this->load->view('personView', $data);
		$data["acl_content"] = "access_control_view/role_view";
		$this->load->view("access_control/template", $data);
	}
	public function role_update($role_id) {
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "edit_role")) {
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		// set validation properties
		//$this->_set_rules();

		// prefill form values
		$role = $this->adminuiacl_model->get_role($role_id);

		$this->form_data->role_id = $role_id;
		$this->form_data->name = $role->name;
		$this->form_data->slug = $role->slug;
		$this->form_data->description = $role->description;
		$data["perm_list"] = $this->adminuiacl_model->get_all_perms();

		$perm_keys = $this->adminuiacl_model->get_role_perms_keys($role_id);

		foreach ($data["perm_list"] as $perm){
			foreach($perm_keys as $keys){
				if($perm->perm_id == $keys->perm_id){
				 $perm->set = true;
				}
			}
		}
		//print_r($data["perm_list"]);
		//exit;
		// set common properties
		$data["title"] = "APIv2 AdminUI Dashboard";
		$data["subtitle"] = 'Modify Role';
		//$data["perms"] = $this->adminuiacl_model->get_all_perms();

		//$data["role_array"] = $this->adminuiacl_model->user_current_group();
		//		$data["user"]->roles = $this->adminuiacl_model->get_user_roles($user_id);
		//$data["role_list"] = $this->adminuiacl_model->get_all_roles();
		$data["action"] = site_url("access_control/role/role_modify");
		$data["link_back"] = anchor("access_control/role/role_manager/", "<i class=\"fa fa-group fa-fw\"></i> Back to list of Roles", array("class" => "btn btn-primary", "type" => "button"));

		// load view
		$data["acl_content"] = "access_control_view/role_modify";
		$this->load->view("access_control/template", $data);
	}
	public function role_modify(){
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "edit_role")) {
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}

		//set empty default form field values
		//$this->_set_role_fields();
		//set validation properties
		//$this->_set_role_rules();
		$role_id =$this->input->post("role_id");
		$role_pdo = array(
			"role_id" => $this->input->post("role_id"),
			"name" => $this->input->post("name"),
			"slug" => $this->input->post("slug"),
			"description" => $this->input->post("description")
		);
		$data["title"] = "APIv2 AdminUI Dashboard";
		$data["subtitle"] = "Modify Role";
		$data["panel_title"] = "AdminUI Role";

		$this->form_data->role_id = $role_id;
		$this->form_data->name =$this->input->post("name");
		$this->form_data->slug = $this->input->post("slug");
		$this->form_data->description = $this->input->post("description");

		$this->adminuiacl_model->edit_role($role_id,$role_pdo);
		$this->adminuiacl_model->edit_role_perms($role_id,$this->input->post("perms"));

		// Extract allowable permissions by roles based on role id.
		//TODO ADD TO HELPER CLASS
		unset($data["perm_list"]);
		$data["perm_list"] = $this->adminuiacl_model->get_role_perms($role_id);
		//print_r($data["perm_list"]);exit;

		//Update the role
		$data["action"] = site_url("access_control/role/role_update/{$role_id}");
		$data["link_back"] = anchor("access_control/role/role_manager/", "<i class=\"fa fa-group fa-fw\"></i> Back to list of Roles", array("class" => "btn btn-primary", "type" => "button"));
		$data["acl_content"] = "access_control_view/role_modify";

		redirect("access_control/role/role_update/{$role_id}/?UpdateSuccess=success");
	}
	public function role_delete($role_id) {
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "delete_role")) {
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		// set validation properties
		//$this->_set_rules();

		// prefill form values
		$role = $this->adminuiacl_model->get_role($role_id);
		$data["role"] = $role;
		// set common properties
		$data["title"] = "APIv2 AdminUI Dashboard";
		$data["subtitle"] = 'YOU ARE ABOUT TO DELETE A ROLE';
		$data["del_role"] = site_url("access_control/role/del_role_process/{$role_id}");
		$data["action"] = site_url("access_control/role/role_delete");
		$data["link_back"] = anchor("access_control/role/role_manager/", "<i class=\"fa fa-group fa-fw\"></i> Back to list of roles", array("class" => "btn btn-primary", "type" => "button"));

		// load view
		$data["acl_content"] = "access_control_view/role_delete";
		$this->load->view("access_control/template", $data);
	}

	public function del_role_process($role_id){
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "delete_role")) {
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		//TODO REMOVE CONSTRAINTS THIS WORKS
		$this->adminuiacl_model->del_role($role_id);
		redirect("access_control/role/role_manager/?del_success_message=success");
	}

	// set empty default form field values
	protected function _set_role_fields() {

		$this->form_data->name = "";
		$this->form_data->slug = "";
		$this->form_data->description = "";
	}

	// validation rules
	protected function _set_rules() {

		$this->form_validation->set_rules("name", "Name", "trim|min_length[3]|required|is_unique[role.name]|xss_clean");
		$this->form_validation->set_rules("slug", "Slug", "trim|min_length[3]|required|is_unique[role.slug]|xss_clean");
		$this->form_validation->set_rules("description", "Description", "trim|required|xss_clean");

		$this->form_validation->set_message ( "required", "* required" );
		$this->form_validation->set_message ( "isset", "* required" );
		$this->form_validation->set_message ( "is_unique", "%s value has already been taken. Please try a different value." );
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );

	}
}
