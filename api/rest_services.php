<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * APIv2 REST Services Controller
 *
 * @package	REST Services Controller
 */

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Rest_services extends REST_Controller {
	   
    function daas_read_get()
    {    	
    	$this->load->model('apiv2_model');
    	$daas_get = $this->apiv2_model->get_daas( $limit = $this->get('limit'), $this->get('source'));
    
    	if($daas_get)
    	{
    		$this->response($daas_get, 200); // 200 being the HTTP response code
    	}
    
    	else
    	{
    		$this->response(array('error' => 'Couldn\'t find any users!'), 404);
    	}
    }
    
    // add/update user account (deactivate user account)
    function daas_write_post()
    {
    	//load the model
    	$this->load->model('apiv2_model');
   	
    	// user account status
    	$params = array('status' => "0");
    	
    	$source = "e_grant";
    	
    	//print_r($params->IsActive); exit;
    	
    	//print_r($source["data"]->source); exit;
    	
    	$daas_post = $this->apiv2_model->dass_update($params, $source);
    	
    	if($daas_post === FALSE)
    	{
    		$this->response($daas_post, 200); // 200 being the HTTP response code
    	}
    	else
    	{
    		$this->response($daas_post, 200); // 200 being the HTTP response code
    	}
    
    	//$this->response($daas_post, 200); // 200 being the HTTP response code
    }

    // deactivate user account
    function daas_delete()
    {
    	//$this->some_model->deletesomething( $this->get('id') );
    	$this->load->model('apiv2_model');
    	$daas_post = $this->apiv2_model->dass_delete($params, $source);
    
    	$this->response($message, 200); // 200 being the HTTP response code
    }
    
    function logs_get() {
    	 
    	$this->load->model('log_model');
    	$logs = $this->log_model->get_logs($this->get('limit'));
    	 
    	if ($logs) {
    		$this->response($logs, 200); // 200 being the HTTP response code
    	} else {
    		$this->response(array('error' => 'Couldn\'t find any log entries!'), 404);
    	}
    }
}