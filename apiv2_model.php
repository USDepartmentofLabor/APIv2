<?php defined('BASEPATH') OR exit('No direct script access allowed');

class APIv2_model extends CI_Model {
	
	private $db = NULL;
	private $drv_mssql = "mssql";
	private $string_tbl = "string";
	
	function __construct() {
		parent::__construct();
		
		// bootstrap apiv2 mssql database
		$this->db = $this->load->database("default", TRUE);
	}
	
	//
	public function get_daas($limit, $source) {
		$this->db->select("string.*, tbl2.*");
		$this->db->join("api_rdbms AS tbl2", "string.rdbms = tbl2.db_id");
		$this->db->where("string.method", $source);
		
		$query = $this->db->get($this->string_tbl); 
	
		if ($query->num_rows > 0) {
				// initiate viratual varaibles from the database
				$config['hostname'] = $query->row("host");
				$config['username'] = $query->row("user");
				$config['password'] = $query->row("passwd");
				$config['database'] = $query->row("dbname");
				$config['dbdriver'] = $query->row("db_driver"); // this determines the entire connection
				$config['dbprefix'] = "";
				$config['pconnect'] = FALSE;
				$config['db_debug'] = TRUE;
				$config['char_set'] = 'utf8';
				$config['dbcollat'] = 'utf8_general_ci';
				$config['autoinit'] = TRUE;
				$config['stricton'] = FALSE;
		}
			// load the virtual database
			$this->apiv2 = $this->load->database($config, TRUE);
			$this->apiv2->from($query->row("table"));
			$this->apiv2->limit(0);
			$query = $this->apiv2->get();
				
			$connect = array();
	
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$connect[] = $row;
				}
				return $connect;
			}
	}
	
	//
	public function dass_update($params, $source) {
		$this->db->select("string.*, tbl2.*");
		$this->db->join("api_rdbms AS tbl2", "string.rdbms = tbl2.db_id");
		$this->db->where("string.method", $source);
	
		$query = $this->db->get($this->string_tbl);
	
		if ($query->num_rows > 0) {
				// initiate viratual varaibles from the database
				$config['hostname'] = $query->row("host");
				$config['username'] = $query->row("user");
				$config['password'] = $query->row("passwd");
				$config['database'] = $query->row("dbname");
				$config['dbdriver'] = $query->row("db_driver"); // this determines the entire connection
				$config['dbprefix'] = "";
				$config['pconnect'] = FALSE;
				$config['db_debug'] = TRUE;
				$config['char_set'] = 'utf8';
				$config['dbcollat'] = 'utf8_general_ci';
				$config['autoinit'] = TRUE;
				$config['stricton'] = FALSE;
		}
		// load the virtual database
		$this->apiv2 = $this->load->database($config, TRUE);
		
		$this->apiv2->where("user_id", "1");
		
		return $this->apiv2->update($query->row("table"), $params);

	}
}