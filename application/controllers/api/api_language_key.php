<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'libraries/api/API_Controller.php');  

class API_Language_Key extends T_API_Controller {

	/**
	 * This function is called on any request send to this endpoint,
	 * it loads up all the needed files
	 * @since 1.0
	 * @access public
	 */
	public function __construct () {
		parent::__construct();
		$this->load->library("key");
		$this->load->model("user_roles_model");
		$this->load->model("key_model");
	}

	/**
	 * This endpoint outputs a language key object
	 * @since 1.0
	 * @access public
	 */
	public function index_get () {
		if ( ! $this->get('id') ) {  
           	self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        $Key = new Key();

        $project_id = $this->key_model->get_project_id($this->get('id'));

        $modes = $this->user_roles_model->get_user_project_modes( $this->user->id, $token->project->id);

        if ( count($modes) == 0 ) {
        	self::error(403);
        }

        if ( ! $Key->Load($this->get("id")) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        $this->response( $export );
	}

	/**
	 * This endpoint creates a language key with the posted data
	 * @since 1.0
	 * @access public
	 */
	public function index_post () {
		if ( ! $this->post() ) {  
           	self::error($this->config->item("api_bad_request_code"));
            return; 
        }

		$Key = new Key();

		if ( ! $Key->Import($this->post()) ) {
			self::error($this->config->item("api_bad_request_code"));
			return;
		}

		$project_id = $this->key_model->get_project_id($this->get('id'));

        $modes = $this->user_roles_model->get_user_project_modes( $this->user->id, $token->project->id);

        if ( count(array_intersect($modes,array("create","manage"))) == 0 ) {
    		self::error(403);
   		}

		if ( ! $Key->Save() ) {
			self::error($this->config->item("api_error_while_saving_code"));
			return;
		}

		$this->response($Key->Export(),$this->config->item("api_created_code"));
	}

	/**
	 * This endpoint updates and a language key
	 * @since 1.0
	 * @access public
	 */
	public function index_put () {
		if ( ! $this->get('id') || ! $this->put() ) {  
           	self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        $Key = new Key();

        if ( ! $Key->Load($this->get("id")) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        if ( ! $Key->Import($this->put(),true) ) {
			self::error($this->config->item("api_bad_request_code"));
			return;
		}

		$project_id = $this->key_model->get_project_id($this->get('id'));

        $modes = $this->user_roles_model->get_user_project_modes( $this->user->id, $token->project->id);

        if ( count(array_intersect($modes,array("edit","manage"))) == 0 ) {
    		self::error(403);
   		}

		if ( ! $Key->Save() ) {
			self::error($this->config->item("api_error_while_saving_code"));
			return;
		}

		$this->response($Key->Export(), $this->config->item("api_accepted_code"));
	}

	/**
	 * This endpoint deletes a language key by it's id
	 * @since 1.0
	 * @access public
	 */
	public function index_delete () {
		if ( ! $this->delete('id') ) {  
            self::error($this->config->item("api_bad_request_code"));
            return;
        }

        $Key = new Key();

        $project_id = $this->key_model->get_project_id($this->get('id'));

        $modes = $this->user_roles_model->get_user_project_modes( $this->user->id, $token->project->id);

        if ( count(array_intersect($modes,array("delete","manage"))) == 0 ) {
    		self::error(403);
   		}

        if ( ! $Key->Load($this->get("id")) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        $Key->Delete();

        $this->response = array();
	}
}