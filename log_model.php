<?php

class Log_model extends CI_Model {

	private $dbmssql = NULL;
	private $db = NULL;
	
	function __construct() {
		parent::__construct();
		
		// bootstrap apiv2 mssql database
		$this->db = $this->load->database('default', TRUE);
	}
	
	public function get_logs() {
		$this->db->select('uri, method, ip_address');
		$this->db->from('logs');
		//$this->db->limit(10);
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

/*	
	function getLogs() {
		$query = $this->db->query("SELECT uri, method, ip_address FROM logs");
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}
*/
}
