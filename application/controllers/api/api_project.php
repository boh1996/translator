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

	/**
	 * This function returns a project found by it's db id
	 * @since 1.0
	 * @access public
	 * @param integer :id The database id
	 */
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

	/**
	 * This functuion creates a project, if the user has access to it
	 * @since 1.0
	 * @access public
	 */
	public function index_post () {
		if ( $this->user->has_one_mode("create") ) {
			$Project = new Project();

			if ( ! $Project->Import($this->post()) ) {
				self::error($this->config->item("api_bad_request_code"));
				return;
			}

			if ( ! $Project->Save() ) {
				self::error($this->config->item("api_error_while_saving_code"));
				return;
			}

			$this->response($Project->Export(),$this->config->item("api_created_code"));
		} else {
    		self::error($this->config->item("api_forbidden_error"));
    	}
	}

	/**
	 * This endpoint updates a project with the new data, used for PUT requests, 
	 * edit operations
	 * @param integer :id The project to edit
	 * @since 1.0
	 * @access public
	 */
	public function index_put () {
		$Project = new Project();

		if ( ! $this->get('id') ) {  
           	self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        if ( ! $Project->Load( $this->get("id") ) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

		if ( ! $Project->Import($this->put(),true) ) {
			self::error($this->config->item("api_bad_request_code"));
			return;
		}

		if ( ! $Project->Save() ) {
			self::error($this->config->item("api_error_while_saving_code"));
			return;
		}

		$this->response($Project->Export(), $this->config->item("api_accepted_code"));
	}

	/**
	 * This function deletes a project by it's id if the user has access to it
	 * @since 1.0
	 * @param integer :id The database id of the project to delete
	 * @access public
	 */
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