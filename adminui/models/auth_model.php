<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * APIv2 AdminUI Pilot Database Controller
 *
 * @package	CI Controller
 * @category Database Controller
 * @author
 * @link	http://avizium.com/
 * @version 2.0.0-pre
 */

class Auth_model extends CI_Model {

	private $admin_user = "admin_user";
	private $admin_request = "admin_request";
	private $request_assistance = "request_assistance";
	private $group_manager = "role";

	function __construct(){
		parent::__construct();
		$this->db = $this->load->database("adminDB", TRUE);
	}

	function validate() {
		$this->db->where("username", $this->input->post("username"));
		$this->db->where("password", md5($this->input->post("password")));
		$this->db->where("status", "1");
		$query = $this->db->get($this->admin_user);

		if ($query->num_rows == 1 && $query->row("password_reset") != 1) {
			return AUTH_PASS;
		} elseif ($query->row("password_reset") == 1) {
			return PASS_RESET_REQUIRED;
		}
	}

	// check registration table for duplicate username or/and email
	private function check_admin_user() {
		$this->db->where("username", $this->input->post("username"));
		$this->db->where("email_address", $this->input->post("email_address"));
		$query = $this->db->get($this->admin_user);

		if ($query->num_rows == 1) {
			return DUPLICATE_ADMIN;
		}
	}

	function admin_request() {
		// check valid admin user table private method
		$response = $this->check_admin_user();

		if ($response == DUPLICATE_ADMIN) {
			return DUPLICATE_ADMIN;
		} else {
			// check for duplicate user before inserting new request...
			$this->db->where("username", $this->input->post("username"));
			$this->db->where("email_address", $this->input->post("email_address"));
			$query = $this->db->get($this->admin_request);


			if ($query->num_rows != 1) {
				// no duplicate record(s) found...
				$new_user = array(
						"first_name" => $this->input->post("first_name"),
						"last_name" => $this->input->post("last_name"),
						"email_address" => strtolower($this->input->post("email_address")),
						"username" => strtolower($this->input->post("username")),
						"password" => md5($this->input->post("password")),
						"password_reset" => "0",
						"status" => "0",
						"date_requested" => date("Y-m-d H:i:s")
				);

				$insert = $this->db->insert($this->admin_request, $new_user);
				return $insert;
			} else {
				return DUPLICATE_REG;
			}
		}
	}

	function create_admin() {
		// check for duplicate user before inserting new request...
		$this->db->where("username", $this->input->post("username"));
		$this->db->where("email_address", $this->input->post("email_address"));
		$query = $this->db->get($this->admin_user);


		if ($query->num_rows != 1) {
			// no duplicate record(s) found...
			$new_user = array(
				"first_name" => $this->input->post("first_name"),
				"last_name" => $this->input->post("last_name"),
				"email_address" => $this->input->post("email_address"),
				"username" => $this->input->post("username"),
				"password" => md5($this->input->post("password")),
				"password_reset" => "0",
				"status" => "0",
				"date_created" => date("Y-m-d H:i:s")
			);

			$insert = $this->db->insert($this->admin_user, $new_user);
			return $insert;
		} else {
			return DUPLICATE_ADMIN;
		}
	}

	function list_all() {
		$this->db->order_by("user_id","asc");
		return $this->db->get($this->admin_user);
	}

	function list_all_pendrequest() {
		$this->db->order_by("user_id","asc");
		return $this->db->get($this->admin_request);
	}

	function count_all() {
		return $this->db->count_all($this->admin_user);
	}

	function get_by_id($user_id) {
		$this->db->where("user_id", $user_id);
		return $this->db->get($this->admin_user);
	}

	function get_by_user($user) {
		$this->db->where("username", $user);
		return $this->db->get($this->admin_user);
	}

	function save($acct) {
		$this->db->insert($this->admin_user, $acct);
		return $this->db->insert_id();
	}

	function delete($user_id) {
		$this->db->where("user_id", $user_id);
		$this->db->delete($this->admin_user);
	}

	function passreset($email) {
		$this->db->where("email_address", $email);
		return $this->db->get($this->admin_user);
	}

	function update_password($user_id, $acct) {
		$this->db->where("user_id", $user_id);
		$this->db->update($this->admin_user, $acct);
	}

	function update_password_prompt($user_id, $acct) {
		$this->db->where("user_id", $user_id);
		$this->db->update($this->admin_user, $acct);
	}

	function reg_error_message($data) {
		$insert = $this->db->insert($this->request_assistance, $data);
		return $insert;
	}
}