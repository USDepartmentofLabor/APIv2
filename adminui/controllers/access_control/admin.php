<?php if (! defined ( "BASEPATH" )) exit ( "No direct script access allowed" );

/**
 * APIv2 AdminUI Pilot Admin Controller
 *
 * @package Admin Controller
 * @author
 * @link http://avizium.com/
 * @version 2.0.0-pre
 */
class Admin extends CI_Controller {
	// number of records per page
	private $limit = 10;
	private $acl_table;
	public function __construct() {
		parent::__construct ();
		$this->is_logged_in ();
		// no page caching
		$this->output->nocache ();
		// bootstrap dashboard and access control model
		$this->load->model ( "adminuiacl_model", "", TRUE );

		$this->acl_conf = ( object ) $this->config->item ( "acl" );
		$this->acl_table = & $this->acl_conf->table;
	}
	public function is_logged_in() {
		$is_logged_in = $this->session->userdata ( "is_logged_in" );

		if (! isset ( $is_logged_in ) || $is_logged_in != TRUE) {
			echo "You don't have permission to access this page. " . anchor ( "/login", "Login Now" );
			die ();
		}
	}

	// load users account view
	public function account_manager($offset = 0) {
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "view_users" )) {
			show_error ( "You do not have access to this section" . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment ( $uri_segment );

		// load data
		$users = $this->adminuiacl_model->get_paged_list ( $this->limit, $offset )->result ();

		// generate pagination
		$config ["base_url"] = site_url ( "access_control/admin/account_manager/" );
		$config ["total_rows"] = $this->adminuiacl_model->count_all ();
		$config ["per_page"] = $this->limit;
		$config ["uri_segment"] = $uri_segment;
		$config ["anchor_class"] = "class=\"btn btn-primary btn-sm\"";
		$this->pagination->initialize ( $config );
		$data ["pagination"] = $this->pagination->create_links ();
		$data ["title"] = " APIv2 AdminUI Dashboard";
		$data ["subtitle"] = "Account Manager";
		$data ["panel_title"] = "AdminUI Accounts";
		$data ["action_passwd_chg"] = site_url ( "access_control/admin/password_change_process" );
		$data ["add_admin_process"] = site_url ( "access_control/admin/add_admin" );
		$data ["roles"] = $this->adminuiacl_model->get_group ();
		$data ["role_array"] = $this->adminuiacl_model->user_current_group();
		$data["perm_list"] = $this->adminuiacl_model->get_all_perms();

		//$data["user"]->roles = $this->adminuiacl_model->get_user_roles($user_id);
		$data["role_list"] = $this->adminuiacl_model->get_all_roles();

		// generate table data
		$this->table->set_empty ( "&nbsp;" );
		$table_setup = array (
				"table_open" => "<table class=\"table table-striped table-bordered table-hover\">"
		);
		$this->table->set_heading ( "#No", "First Name", "Last Name", "Username", "Status", "Date", "Actions" );
		$i = 0 + $offset;
		$view = array (
				"class" => "btn btn-success btn-sm",
				"type" => "button"
		);
		$update = array (
				"class" => "btn btn-warning btn-sm",
				"type" => "button"
		);
		$delete = array (
				"class" => "btn btn-danger btn-sm",
				"type" => "button",
				"data-toggle" => "confirmation"
		);
		foreach ( $users as $acct ) {
			$this->table->add_row ( ++ $i, $acct->first_name, $acct->last_name, $acct->username, strtoupper ( $acct->status ) == "1" ? "Active" : "Disabled", date ( "m/d/Y", strtotime ( $acct->date_created ) ), anchor ( "access_control/admin/account_view/" . $acct->user_id, "<i class =\"fa fa-search\"></i>", $view ) . " " . anchor ( "access_control/admin/account_update/" . $acct->user_id, "<i class =\"fa fa-refresh\"></i>", $update ) . " " . anchor ( "access_control/admin/account_delete/" . $acct->user_id, "<i class =\"fa fa-trash-o\"></i>", $delete ) );
		}
		$this->table->set_template ( $table_setup );
		$data ["table"] = $this->table->generate ();

		// load account view
		$data ["acl_content"] = "access_control_view/account_manager";
		$this->load->view ( "access_control/template", $data );
	}
	public function account_view($user_id) {
		// check roles and permissions

		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "view_users" )) {
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}

		// set common properties
		$data ["title"] = " APIv2 AdminUI Dashboard";
		$data ["subtitle"] = "Account View";
		$data["perm_list"] = $this->adminuiacl_model->get_all_perms();

		$data ["link_back"] = anchor ( "access_control/admin/account_manager/", "<i class=\"fa fa-group fa-fw\"></i> Back to list of admins", array (
				"class" => "btn btn-primary",
				"type" => "button"
		) );

		$data ["user"]->roles = $this->adminuiacl_model->get_user_role( $user_id );

		// get person details
		$data ["acct"] = $this->adminuiacl_model->get_by_id ( $user_id )->row ();
		$data ["role_list"] = $this->adminuiacl_model->get_all_roles();
		// Extract allowable permissions by roles based on role id.
		// TODO ADD TO HELPER CLASS

		if (is_array($data ["user"]->roles )) {
			foreach ( $data ["role_list"] as &$role ) {
				$perm_checklist = $this->adminuiacl_model->get_role_perms_keys ( $role->role_id );

				$role->set = in_array ( $role, $data ["user"]->roles );
				if ($role->set) {
					foreach ( $data ["perm_list"] as &$perm ) {
						foreach ( $perm_checklist as &$check ) {
							if ($perm->perm_id === $check->perm_id) {
								$perm->set = true;
							}
						}
					}
				}
			}
			// print_r($data['perm_list']);exit;
		} else {
			foreach ( $data ["role_list"] as &$role ) {
				$role->set = FALSE;
			}
			foreach ( $data ["perm_list"] as &$perm ) {
				$perm->set = FALSE;
			}
		}
		// load view
		$data ["acl_content"] = "access_control_view/account_view";
		$this->load->view ( "access_control/template", $data );
	}
	public function account_update($user_id) {
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "edit_user" )) {
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		// set validation properties
		// $this->_set_rules();
		// prefill form values
		$acct = $this->adminuiacl_model->get_by_id ( $user_id )->row ();
		$this->form_data->user_id = "$user_id";

		$this->form_data->first_name = $acct->first_name;
		$this->form_data->last_name = $acct->last_name;
		$this->form_data->username = $acct->username;
		$this->form_data->email_address = $acct->email_address;
		$this->form_data->status = strtoupper ( $acct->status );
		$this->form_data->date_created = date ( 'm/d/Y', strtotime ( $acct->date_created ) );

		// set common properties
		$data ["title"] = " APIv2 AdminUI Dashboard";
		$data ["subtitle"] = 'Modify Account';
		$data ["roles"] = $this->adminuiacl_model->get_group ();
		// $data["role_array"] = $this->adminuiacl_model->user_current_group();
		$data ["user"]->roles = $this->adminuiacl_model->get_user_role ( $user_id );
		$data ["role_list"] = $this->adminuiacl_model->get_all_roles ();
		$data ["perm_list"] = $this->adminuiacl_model->get_all_perms ();
		$data ["message"] = "";
		$data ["passwd_message"] = "";
		$data ["error"] = "";
		$data ["passwd_error"] = "";
		$data ["success"] = "";
		$data ["passwd_success"] = "";
		$data ["action"] = site_url ( "access_control/admin/account_modify" );
		$data ["action_passwd_chg"] = site_url ( "access_control/admin/password_change_process" );
		$data ["link_back"] = anchor ( "access_control/admin/account_manager/", "<i class=\"fa fa-group fa-fw\"></i> Back to list of admins", array (
				"class" => "btn btn-primary",
				"type" => "button"
		) );

		// Extract allowable permissions by roles based on role id.
		// TODO ADD TO HELPER CLASS
		if (is_array ( $data ["user"]->roles )) {
			foreach ( $data ["role_list"] as &$role ) {
				$perm_checklist = $this->adminuiacl_model->get_role_perms_keys ( $role->role_id );

				$role->set = in_array ( $role, $data ["user"]->roles );
				if ($role->set) {
					foreach ( $data ["perm_list"] as &$perm ) {
						foreach ( $perm_checklist as &$check ) {
							if ($perm->perm_id === $check->perm_id) {
								$perm->set = true;
							}
						}
					}
				}
			}
		} else {
			foreach ( $data ["role_list"] as &$role ) {
				$role->set = FALSE;
			}
			foreach ( $data ["perm_list"] as &$perm ) {
				$perm->set = FALSE;
			}
		}
		// load view
		$data ["acl_content"] = "access_control_view/account_modify";
		$this->load->view ( "access_control/template", $data );
	}
	public function account_delete($user_id) {
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "delete_user" )) {
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		// set validation properties
		// $this->_set_rules();
		// prefill form values
		$acct = $this->adminuiacl_model->get_by_id ( $user_id )->row ();
		$this->form_data->user_id = "$user_id";

		$this->form_data->first_name = $acct->first_name;
		$this->form_data->last_name = $acct->last_name;
		$this->form_data->username = $acct->username;
		$this->form_data->email_address = $acct->email_address;
		$this->form_data->status = strtoupper ( $acct->status );
		$this->form_data->date_created = date ( 'm/d/Y', strtotime ( $acct->date_created ) );
		$data ["role_list"] = $this->adminuiacl_model->get_all_roles ();
		$data ["perm_list"] = $this->adminuiacl_model->get_all_perms ();
		// set common properties
		$data ["title"] = " APIv2 AdminUI Dashboard";
		$data ["subtitle"] = 'YOU ARE ABOUT TO DEACTIVATE A USER ACCOUNT';
		$data ["del_account"] = site_url ( "access_control/admin/del_account_process/{$user_id}" );
		$data ["action"] = site_url ( "access_control/admin/account_delete" );
		$data ["link_back"] = anchor ( "access_control/admin/account_manager/", "<i class=\"fa fa-group fa-fw\"></i> Back to list of admins", array (
				"class" => "btn btn-primary",
				"type" => "button"
		) );

		// load view
		$data ["acl_content"] = "access_control_view/account_delete";
		$this->load->view ( "access_control/template", $data );
	}
	public function del_account_process($user_id) {
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "delete_user" )) {
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		$this->adminuiacl_model->del_user ( $user_id );
		redirect ( "access_control/admin/account_manager/?del_success_message=success" );
	}
	public function account_modify() {
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "edit_user" )) {
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}

		// set common properties
		$data ["title"] = " APIv2 AdminUI Dashboard";
		$data ["subtitle"] = "Modify Account";
		$data ["message"] = "";
		$data ["passwd_message"] = "";
		$data ["error"] = "";
		$data ["passwd_error"] = "";
		$data ["success"] = "";
		$data ["passwd_success"] = "";
		$data ["action"] = site_url ( "access_control/admin/account_modify" );
		$data ["action_passwd_chg"] = site_url ( "access_control/admin/password_change_process" );
		$data ["link_back"] = anchor ( "access_control/admin/account_manager/", "Back to list of admins", array (
				"class" => "btn btn-primary",
				"type" => "button"
		) );
		$data ["roles"] = $this->adminuiacl_model->get_group ();
		$data ["role_array"] = $this->adminuiacl_model->user_current_group ();

		// set empty default form field values
		$this->_set_fields ();
		// set validation properties
		$this->_set_rules ();

		// print_r($_POST); exit;
		$user_id = $this->input->post ( "user_id" );

		// run validation
		if ($this->form_validation->run () == FALSE) {


		} else {
			// save modification;
			if ($this->adminuiacl_model->edit_user_roles ( $user_id = $this->input->post ( "user_id" ), $this->input->post ( "roles" ) )) {
				$acct = array (
						"first_name" => $this->input->post ( "first_name" ),
						"last_name" => $this->input->post ( "last_name" ),
						"username" => $this->input->post ( "username" ),
						"email_address" => $this->input->post ( "email_address" ),
						"status" => $this->input->post ( "status" ),
						"modified_date" => date ( "Y-m-d H:i:s" ),
						"modified_by" => $this->session->userdata ( "username" )
				);
				$this->adminuiacl_model->account_update ( $user_id, $acct );

				// set modification message
				redirect ( "access_control/admin/account_update/{$user_id}/?success_message=true" );
			} else {
				show_error ( "Failed assigning user." );
			}
		}
		// prefill form values
		$acct = $this->adminuiacl_model->get_by_id ( $user_id )->row ();
		$this->form_data->user_id = "$user_id";
		$data ["success"] = "";
		$data ["roles"] = $this->adminuiacl_model->get_group ();
		$data ["user"]->roles = $this->adminuiacl_model->get_user_roles ( $user_id );
		$data ["user"]->perms = $this->adminuiacl_model->get_role_perms_keys ( $user_id );
		$data ["role_list"] = $this->adminuiacl_model->get_all_roles ();
		$data ["perm_list"] = $this->adminuiacl_model->get_all_perms ();
		$this->form_data->username = $acct->username;
		$this->form_data->first_name = $acct->first_name;
		$this->form_data->last_name = $acct->last_name;
		$this->form_data->email_address = $acct->email_address;
		$this->form_data->status = strtoupper ( $acct->status );

		if (is_array ( $data ["user"]->roles )) {

			foreach ( $data ["role_list"] as &$role ) {
				$role->set = in_array ( $role, $data ["user"]->roles );
			}
			foreach ( $data ["perm_list"] as &$perm ) {
				// Compare this list with the user's current permissions

				$perm->set = in_array ( $perm, ( array ) ($data ["user"]->perms) );
			}
		} else {
			foreach ( $data ["role_list"] as &$role ) {
				$role->set = FALSE;
			}
			foreach ( $data ["perm_list"] as &$perm ) {
				$perm->set = FALSE;
			}
		}
		// load view
		$data ["acl_content"] = "access_control_view/account_modify";
		$this->load->view ( "access_control/template", $data );
	}

	// create admin account form
	public function add_admin() {
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "add_user" )) {
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		$this->form_validation->set_rules ( "first_name", "First Name", "trim|required" );
		$this->form_validation->set_rules ( "last_name", "Last name", "trim|required" );
		$this->form_validation->set_rules ( "username", "Username", "trim|required|valid_email|unique" );
		$this->form_validation->set_rules ( "email_address", "Email Address", "trim|required|valid_email|unique" );
		$this->form_validation->set_rules ( "password", "Password", "required|min_length[6]" );
		$this->form_validation->set_rules ( "password2", "Confirm Password", "required|matches[password]" );

		if ($this->form_validation->run () == FALSE) {

		} else {
			// call admin model and process new admin
			$this->add_admin_process ();
		}
	}
	private function add_admin_process() {
		// load model
		// $this->load->model("adminuiacl_model");
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "add_user" )) {
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		$role_array = $this->input->post("roles");
		$role_id = $role_array[0];
		// validation complete
		$new_admin = array("first_name" => $this->input->post("first_name"),
				"last_name" => $this->input->post("last_name"),
				"username" => $this->input->post("username"),
				"password" => md5($this->input->post("password")),
				"password_reset" => 1,
				"email_address" => $this->input->post("email_address"),
				"status" => 1,
				"date_created" => date("Y-m-d H:i:s"));

		$response = $this->adminuiacl_model->admin_add_user($new_admin);


		if ($response == NO_DUPLICATE_ADMIN) {
			//Everything checks out add role
			$new_admin = $this->adminuiacl_model->get_user_by('username',$this->input->post("username"));
			$this->adminuiacl_model->add_user_role($new_admin->user_id,$role_id);

			// if no duplicate admin account is found, send an email notification to the new admin
			$name = $this->input->post ( "first_name" ) . " " . $this->input->post ( "last_name" );
			$email = $this->input->post ( "email_address" );

			$this->email->set_newline ( "\r\n" );

			$this->email->from ( "" . FROM_EMAIL . "", "" . FROM_NAME . "" );
			$this->email->to ( "" . APPROVAL_ADMIN . "" );
			$this->email->cc ( $email );
			$this->email->subject ( "AdminUI account created" );
			$body = "
			<table border=\"1\" style=\"border-width: thin; border-spacing: 2px; border-style: none; border-color: #ccc;\">
				<tr>
					<td>Admin</td>
					<td>{$name}</td>
				</tr>
				<tr>
					<td>Message</td>
					<td width=\"300\">
						{$name} has access to AdminUI Dashboard.<br>
						Username: {$email}<br>
						Password: {$this->input->post("password2")}<br>
						Please " . anchor ( "" . base_url () . "login", "Login" ) . " at your earliest convenience and change your password.
					</td>
				</tr>
			</table>";

			$this->email->message ( $body );

			if ($this->email->send ()) {
				// get user details
				$user = $this->adminuiacl_model->get_by_user ( $user = $this->input->post ( "username" ) )->row ();

				// echo 'Your message was sent successfully...';
				redirect ( "access_control/admin/account_manager/?success_message=true&user={$user->user_id}#add_admin" );
			} else {
				show_error ( $this->email->print_debugger () );
			}
		} elseif ($response == DUPLICATE_ADMIN) {
			// redirect to password tab on duplicate admin error
			redirect ( "access_control/admin/account_manager/?admin_error_message=true#add_admin" );
		} elseif ($response == DUPLICATE_REG) {
			// redirect to password tab on registered user error
			redirect ( "access_control/admin/account_manager/?reg_error_message=true#add_admin" );
		}else {
			// TODO throw internal server error page

		}
	}

	// view pending account details
	public function pend_account_view($user_id) {
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "access_acl" )) {
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}

		// set common properties
		$data ["title"] = " APIv2 AdminUI Dashboard";
		$data ["subtitle"] = "Pending Account View";
		$data ["link_back"] = anchor ( "access_control/admin/pending_request/", "<i class=\"fa fa-group fa-fw\"></i> Back to request list", array (
				"class" => "btn btn-primary",
				"type" => "button"
		) );

		// get person details
		$data ["acct"] = $this->adminuiacl_model->get_by_id_pendrequest ( $user_id )->row ();

		// load view
		$data ["acl_content"] = "access_control_view/pend_account_view";
		$this->load->view ( "access_control/template", $data );
	}

	// load pending request view
	public function pending_request($offset = 0) {
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "access_acl" )) {
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		// offset
		$uri_segment = 3;
		$offset = $this->uri->segment ( $uri_segment );
		// load data
		$users = $this->adminuiacl_model->get_paged_list_pendrequest ( $this->limit, $offset )->result ();

		// print_r($users); exit;
		// generate pagination
		$config ["base_url"] = site_url ( "access_control/admin/pending_request/" );
		$config ["total_rows"] = $this->adminuiacl_model->count_all_pendrequest ();
		$config ["per_page"] = $this->limit;
		$config ["uri_segment"] = $uri_segment;
		$config ["anchor_class"] = "class=\"btn btn-primary btn-sm\"";
		$this->pagination->initialize ( $config );
		$data ["pagination"] = $this->pagination->create_links ();
		$data ["title"] = " APIv2 AdminUI Dashboard";
		$data ["subtitle"] = "Pending Request";
		$data ["panel_title"] = "AdminUI Request";

		// generate table data
		$this->table->set_empty ( "&nbsp;" );
		$table_setup = array (
				"table_open" => "<table class=\"table table-striped table-bordered table-hover\">"
		);
		$this->table->set_heading ( "#No", "First Name", "Last Name", "Username", "Status", "Request Date", "Actions" );
		$i = 0 + $offset;
		$view = array (
				"class" => "btn btn-success btn-sm",
				"type" => "button"
		);
		$update = array (
				"class" => "btn btn-warning btn-sm",
				"type" => "button"
		);
		$delete = array (
				"class" => "btn btn-danger btn-sm",
				"type" => "button",
				"data-toggle" => "confirmation"
		);
		foreach ( $users as $acct ) {
			$this->table->add_row ( ++ $i, $acct->first_name, $acct->last_name, $acct->username, strtoupper ( $acct->status ) == "1" ? "Disabled" : "Pending", date ( "m/d/Y", strtotime ( $acct->date_requested ) ), anchor ( "access_control/admin/pend_account_view/" . $acct->user_id, "<i class =\"fa fa-search\"></i>", $view ) . " " . anchor ( "access_control/admin/pend_account_update/" . $acct->user_id, "Approve Request", $update ) . " " . anchor ( "access_control/admin/pend_account_delete/" . $acct->user_id, "Deny Request", $delete ) );
		}
		$this->table->set_template ( $table_setup );
		$data ["table"] = $this->table->generate ();

		// load account view
		$data ["acl_content"] = "access_control_view/pending_request";
		$this->load->view ( "access_control/template", $data );
	}
	public function pend_account_update($user_id) {
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "access_acl" )) {
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}

		// set validation properties
		//$this->_set_pending_rules();

		// prefill form values
		$acct = $this->adminuiacl_model->get_by_id_pendrequest ( $user_id )->row ();
		$this->form_data->user_id = "$user_id";

		// get admin group roles
		//$admin_roles = $this->adminuiacl_model->get_group ();

		$this->form_data->first_name = $acct->first_name;
		$this->form_data->last_name = $acct->last_name;
		$this->form_data->username = $acct->username;
		$this->form_data->email_address = $acct->email_address;
		$this->form_data->status = strtoupper ( $acct->status );
		$this->form_data->date_requested = date ( 'm/d/Y', strtotime ( $acct->date_requested ) );

		// set common properties
		$data ["title"] = " APIv2 AdminUI Dashboard";
		$data ["subtitle"] = 'Modify Pending Request';
		$data ["message"] = '';
		$data ["error"] = "";
		$data ["success"] = "";
		$data ["role_list"] = $this->adminuiacl_model->get_all_roles ();
		$data ["perm_list"] = $this->adminuiacl_model->get_all_perms ();
		$data ["action"] = site_url ( 'access_control/admin/pend_account_modify' );
		$data ["link_back"] = anchor ( "access_control/admin/pending_request/", "<i class=\"fa fa-group fa-fw\"></i> Back to request list", array (
				"class" => "btn btn-primary",
				"type" => "button"
		) );

		// load view
		$data ["acl_content"] = "access_control_view/pend_account_modify";
		$this->load->view ( "access_control/template", $data );
	}

	// pending requet modifier
	public function pend_account_modify() {
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "access_acl" )) {
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}

		// load database model
		$this->load->model ( "adminuiacl_model" );

		// set common properties
		$data ["title"] = " APIv2 AdminUI Dashboard";
		$data ["subtitle"] = "Modify Pending Request";
		$data ["error"] = "";
		$data ["action"] = site_url ( "access_control/admin/pend_account_modify" );
		$data ["link_back"] = anchor ( "access_control/admin/pending_request/", "Back to list request list", array (
				"class" => "btn btn-primary",
				"type" => "button"
		) );

		// set empty default form field values
		$this->_set_fields_pendreqst ();
		// set validation properties
		$this->_set_rules_pendreqst ();

		// run validation
		if ($this->form_validation->run () == FALSE) {
			$data ["message"] = "";
		} else {
			// transfer new account
			$user_id = $this->input->post ( "user_id" );
			// get requestor's information
			$user_info = $this->adminuiacl_model->get_by_id_pendrequest ( $user_id )->row ();

			$new_acct = array (
					"first_name" => $user_info->first_name,
					"last_name" => $user_info->last_name,
					"username" => $this->input->post ( "username" ),
					"password" => $user_info->password,
					"password_reset" => 0,
					"email_address" => $this->input->post ( "email_address" ),
					"status" => 1,
					"date_created" => date ( "Y-m-d H:i:s" ),
					"modified_by" => $this->session->userdata ( "username" )
			);

			$role_array = $this->input->post('roles');
			// send to data model for processing
			$this->adminuiacl_model->approve_request ( $user_id, $new_acct,$role_array[0]);

			// after request has been approved, delete from pending table
			$this->adminuiacl_model->delete_approved_request ( $user_id );

			// prepare email notification
			$this->email->set_newline ( "\r\n" );
			$this->email->from ( "" . FROM_EMAIL . "", "" . FROM_NAME . "" );
			$this->email->to ( $this->input->post ( "email_address" ) );
			$this->email->cc ( "" . CC_EMAIL . "", "" . CC_NAME . "" );
			$this->email->subject ( "AdminUI Account Activated" );
			$this->email->message ( "APIv2 AdminUI account has been approved. Please login to verify " . anchor ( "" . base_url () . "login", "Login" ) . " " );
			$this->email->send ();

			// get user details
			$this->request_approved_view ( $user = $this->input->post ( "username" ), $name = $user_info->first_name );
		}
	}
	public function pend_account_delete($user_id) {
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "delete_user" )) {
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		// set validation properties
		// $this->_set_rules();
		// prefill form values
		$acct = $this->adminuiacl_model->get_by_id_pendrequest( $user_id )->row ();
		$this->form_data->user_id = "$user_id";

		$this->form_data->first_name = $acct->first_name;
		$this->form_data->last_name = $acct->last_name;
		$this->form_data->username = $acct->username;
		$this->form_data->email_address = $acct->email_address;
		$this->form_data->status = strtoupper ( $acct->status );
		$this->form_data->date_requested = date ( 'm/d/Y', strtotime ( $acct->date_requested ) );

		// set common properties
		$data ["title"] = " APIv2 AdminUI Dashboard";
		$data ["subtitle"] = 'YOU ARE ABOUT TO DEACTIVATE A PENDING ACCOUNT';
		$data ["del_pending_account"] = site_url ( "access_control/admin/del_pend_account_process/{$user_id}" );
		$data ["action"] = site_url ( "access_control/admin/pend_account_delete" );
		$data ["link_back"] = anchor ( "access_control/admin/pending_request/", "<i class=\"fa fa-group fa-fw\"></i> Back to list of Pending Requests", array (
				"class" => "btn btn-primary",
				"type" => "button"
		) );

		// load view
		$data ["acl_content"] = "access_control_view/pend_account_delete";
		$this->load->view ( "access_control/template", $data );
	}
	public function del_pend_account_process($user_id){
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "delete_user" )) {
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}

			$this->adminuiacl_model->delete_approved_request ( $user_id );
			redirect ( "access_control/admin/pending_request/?del_success_message=success" );
	}
	public function request_approved_view($user, $name) {
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "access_acl" )) {
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		// set common properties
		$data ["title"] = " APIv2 AdminUI Dashboard";
		$data ["subtitle"] = "Request Status";
		$data ["link_back"] = anchor ( "access_control/admin/account_manager/", "<i class=\"fa fa-group fa-fw\"></i> Back to list of admins", array (
				"class" => "btn btn-primary",
				"type" => "button"
		) );

		// get person details
		$data ["acct"] = $this->adminuiacl_model->get_by_user ( $user = $this->input->post ( "username" ) )->row ();

		// load view
		// $this->load->view('personView', $data);
		// set user message
		$data ["message"] = "<div class=\"alert alert-success alert-dismissable\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
		{$name}'s account has been activated. " . anchor ( "" . base_url () . "access_control/admin/account_update/{$data["acct"]->user_id}", "Re-assign role(s)" ) . "
		</div>";

		$data ["acl_content"] = "access_control_view/request_approved_view";
		$this->load->view ( "access_control/template", $data );
	}
	public function roles_select_ui() {

		$roles_json = '';
		$all_perms = $this->adminuiacl_model->get_all_perms ();
		$role_id = $this->input->get ( "rid" );
		$perm_keys = $this->adminuiacl_model->get_role_perms_keys ( $role_id );
		$flag = 0;
		// Populate the dropdown options here
		foreach ( $all_perms as $perm ) {
			foreach ( $perm_keys as $keys ) {
				if ($perm->perm_id == $keys->perm_id) {
					$flag = 1;
				}
			}
			if ($flag) {
				$roles_json .= "<option selected value=" . $perm->perm_id . ">" . $perm->name;
			} else {
				$roles_json .= "<option  value=" . $perm->perm_id . ">" . $perm->name;
			}
			$flag = 0;
		}
		print_r ( $roles_json );

		// echo json_encode($roles_json);
	}
	// admin password change...
	public function password_change_process() {

		// set default parameters
		$data ["passwd_success"] = "";
		$data ["passwd_error"] = "";
		$data ["user"] = "";

		// set empty default form field values
		$this->_set_passwd_fields ();
		// set validation properties
		$this->_set_passwd_rules ();

		// print_r($_POST); exit;

		// run validation
		if ($this->form_validation->run () == FALSE) {
			$data ["message"] = "";
		} else {

			// get authenticated user user_id
			$acct = $this->adminuiacl_model->get_by_user ( $user = $this->input->post ( "username" ) )->row ();
			$this->form_data->user_id = $acct->user_id;
			$this->form_data->password = $acct->password;
			// get user user_id post-back update
			$data ["user_id"] = $acct->user_id;
			$data ["email_address"] = $acct->email_address;

			// print_r($acct); exit;

			// process password change
			$new_password = md5 ( $this->input->post ( "password" ) );
			$conf_password = md5 ( $this->input->post ( "password2" ) );

			// echo $post_password; exit;

			if ($new_password === $conf_password) {
				// call the password change method
				$this->password_change_admin ( $this->form_data->user_id, $conf_password, $acct->email_address );

				// redirect password change profile tab with success message
				redirect ( "access_control/admin/account_update/{$acct->user_id}/?PasswordChangeSuccess=success#password-pills" );
			} else {
				// redirect password change profile tab with error message
				redirect ( "access_control/admin/account_update/{$acct->user_id}/?PasswordChangeError=error#password-pills" );
			}
		}
	}

	// process password by super admin
	protected function password_change_admin($user_id, $conf_password, $email) {
		$acct = array (
				"password" => $conf_password,
				"password_reset" => "" . PASS_RESET_REQUIRED . "",
				"modified_date" => date ( "Y-m-d H:i:s" ),
				"modified_by" => $this->session->userdata ( "username" )
		);

		// parse data array to update user table
		$this->adminuiacl_model->update_password_prompt ( $user_id, $acct );

		// prepare email notification
		$this->email->set_newline ( "\r\n" );
		$this->email->from ( "" . FROM_EMAIL . "", "" . FROM_NAME . "" );
		$this->email->to ( $email );
		$this->email->cc ( "" . CC_EMAIL . "", "" . CC_NAME . "" );
		$this->email->subject ( "Password reset by admin" );
		$this->email->message ( "You password has been changed to: " . $this->input->post ( "password2" ) . " Please return to the " . anchor ( "" . base_url () . "login", "Login" ) . " page to verify" );
		$this->email->send ();
	}

	// set empty default form field values
	protected function _set_fields() {
		$this->form_data->user_id = "";
		$this->form_data->first_name = "";
		$this->form_data->last_name = "";
		$this->form_data->email_address = "";
		$this->form_data->status = "";
		$this->form_data->username = "";
	}
	protected function _set_pending_rules() {

		$id_check = $this->input->post('user_id');
		$current_acct = $this->adminuiacl_model->get_user_by('user_id',$id_check);

		$current_acct_name = $current_acct->username;
		$current_acct_email = $current_acct->email_address;

		$post_username = $this->input->post('username');
		$post_email_address = $this->input->post('email_address');

		//Do not apply rule is the current user already owns the username
		if($current_acct_name != $post_username){
			$this->form_validation->set_rules ( "username", "Username", "trim|required|valid_email|is_unique[admin_user.username]|xss_clean" );
		}
		//Do not apply rule is the current user already owns the email address
		if($current_acct_email != $post_email_address) {
			$this->form_validation->set_rules ( "email_address", "Email Address", "trim|required|valid_email|is_unique[admin_user.email_address]" );
		}
		$this->form_validation->set_rules ( "first_name", "First Name", "trim|required" );
		$this->form_validation->set_rules ( "last_name", "Last name", "trim|required" );
		$this->form_validation->set_rules ( "status", "Account Status", "trim|required" );
		$this->form_validation->set_rules ( "roles[]", "Roles", "required" );

		$this->form_validation->set_message ( "required", "* required" );
		$this->form_validation->set_message ( "isset", "* required" );
		$this->form_validation->set_message ( "is_unique", "This %s has already been taken. Please try a different value." );
		$this->form_validation->set_message ( "valid_date", "date format is not valid. dd-mm-yyyy" );
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );
	}
	// validation rules
	protected function _set_rules() {

		$id_check = $this->input->post('user_id');
		$current_acct = $this->adminuiacl_model->get_user_by('user_id',$id_check);

		$current_acct_name = $current_acct->username;
		$current_acct_email = $current_acct->email_address;

		$post_username = $this->input->post('username');
		$post_email_address = $this->input->post('email_address');

		//Do not apply rule is the current user already owns the username
		if($current_acct_name != $post_username){
			$this->form_validation->set_rules ( "username", "Username", "trim|required|valid_email|is_unique[admin_user.username]|xss_clean" );
		}
		//Do not apply rule is the current user already owns the email address
		if($current_acct_email != $post_email_address) {
			$this->form_validation->set_rules ( "email_address", "Email Address", "trim|required|valid_email|is_unique[admin_user.email_address]" );
		}
		$this->form_validation->set_rules ( "first_name", "First Name", "trim|required" );
		$this->form_validation->set_rules ( "last_name", "Last name", "trim|required" );
		$this->form_validation->set_rules ( "status", "Account Status", "trim|required" );
		$this->form_validation->set_rules ( "roles[]", "Roles", "required" );

		$this->form_validation->set_message ( "required", "* required" );
		$this->form_validation->set_message ( "isset", "* required" );
		$this->form_validation->set_message ( "is_unique", "This %s has already been taken. Please try a different value." );
		$this->form_validation->set_message ( "valid_date", "date format is not valid. dd-mm-yyyy" );
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );
	}

	// set empty default form field values for pending request
	protected function _set_fields_pendreqst() {
		$this->form_data->first_name = "";
		$this->form_data->last_name = "";
		$this->form_data->email_address = "";
		$this->form_data->username = "";
	}

	// validation rules
	protected function _set_rules_pendreqst() {
		$this->form_validation->set_rules ( "first_name", "First Name", "trim|required" );
		$this->form_validation->set_rules ( "last_name", "Last name", "trim|required" );
		$this->form_validation->set_rules ( "username", "Username", "trim|required|valid_email" );
		$this->form_validation->set_rules ( "email_address", "Email Address", "trim|required|valid_email" );

		$this->form_validation->set_message ( "required", "* required" );
		$this->form_validation->set_message ( "isset", "* required" );
		$this->form_validation->set_message ( "valid_date", "date format is not valid. dd-mm-yyyy" );
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
                                	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );
	}

	// set empty default form field password values
	protected function _set_passwd_fields() {
		$this->form_data->password = "";
		$this->form_data->password2 = "";
	}

	// validation rules
	protected function _set_passwd_rules() {
		$this->form_validation->set_rules ( 'password', 'Password', 'trim|required|min_length[6]|max_length[32]' );
		$this->form_validation->set_rules ( 'password2', 'Confirm Password', 'trim|required|min_length[6]|max_length[32]|matches[password]' );

		$this->form_validation->set_message ( "required", "* required" );
		$this->form_validation->set_message ( "isset", "* required" );
		$this->form_validation->set_message ( "valid_date", "date format is not valid. dd-mm-yyyy" );
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
                                	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );
	}
}