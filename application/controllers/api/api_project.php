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
	 * This function returns a project and with progress for each language file
	 * @since 1.0
	 * @access public
	 * @param integer :project The project id
	 * @param integer :language The current language id
	 */
	public function language_project_get () {

		if ( ! $this->get('project') || ! $this->get("language") ) {  
           	self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        $Project = new Project();

        if ( ! $Project->Load( $this->get("project") ) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        $export = $Project->Export();

        if ( isset($export["files"]) ) {
        	$this->load->model("file_model");

        	foreach ( $export["files"] as $key => $file ) {
        		$progress = $this->file_model->progress($file["id"], $this->get("language"));

        		if ( $progress === false ) {
        			$progress = array("done" => false, "missing_approval" => false, "missing" => 100, "missing_count" => $progress["count"]);
        		}

        		$progress["missing"] = round(100 - (($progress["missing_approval"] !== false) ? $progress["missing_approval"] : 0) - (($progress["done"] !== false) ? $progress["done"] : 0));

        		$progress["missing_count"] = $progress["count"] - $progress["done_count"] - $progress["missing_approval_count"];

        		$export["files"][$key]["progress"] = $progress;
        	}
        }

        $this->response( $export );
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

	/*public function files_get () {
		$this->load->model("project_model");
		$this->load->model("language_model");

		if ( ! $this->get('project') ) {  
            self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        if ( ! $this->project_model->project_exists($this->get("project")) ) {
        	self::error($this->config->item("api_not_found_code"));
	        return;
        }
	}*/
}