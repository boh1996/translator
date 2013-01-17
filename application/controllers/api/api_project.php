<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'libraries/API_Controller.php');  

class API_Project extends API_Controller {

	/**
	 * This function is called on any request send to this endpoint,
	 * it loads up all the needed files
	 * @since 1.0
	 * @access public
	 */
	public function __construct () {
		parent::__construct();
		$this->load->library("project");
	}

	public function index_get () {
		if ( ! $this->get('id') ) {  
           	self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        $Project = new Project();

        if ( ! $Project->Load( $this->get("id") ) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        $this->response( $Project->Export() );
	}

	public function index_post () {

	}

	public function index_put () {

	}

	public function index_delete () {
		if ( $this->user->has_one_mode("delete") ) {
			if ( ! $this->get('id') ) {  
	            self::error($this->config->item("api_bad_request_code"));
	            return; 
	        }

	        $Project = new Project();

	        if ( ! $Project->Load( $this->get("id") ) ) {
	        	self::error($this->config->item("api_not_found_code"));
	        	return;
	        }

	        $Project->Delete();
	        
		    $this->response = array();
    	} else {
    		self::error($this->config->item("api_forbidden_error"));
    	}
	}
}