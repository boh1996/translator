<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'libraries/api/T_API_Controller.php');  

/**
 * Token Object Controller
 *
 * The Token Controller/Endpoint
 *
 * @uses 			API Controller
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author        	Bo Thomsen
 * @version 		1.0
 */
class API_Token extends T_API_Controller {

	/**
	 * This function is called on any request send to this endpoint,
	 * it loads up all the needed files
	 * @since 1.0
	 * @access public
	 */
	public function __construct () {
		parent::__construct();
		$this->load->library("token");
	}	

	/**
	 * This endpoint outputs a token retrieved by it's :id
	 * @since 1.0
	 * @access public
	 * @param integer :id The database id of a token
	 */
	public function index_get () {
		if ( ! $this->get('id') ) {  
           	self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        $Token = new Token();

        if ( ! $Token->Load($this->get('id')) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        $this->response($Token->Export());
	}

	/**
	 * This endpoint creates a Token
	 * @since 1.0
	 * @access public
	 */
	public function index_post () {
		$Token = new Token();

		if ( ! $Token->Import($this->post()) ) {
			self::error($this->config->item("api_bad_request_code"));
			return;
		}

		if ( ! $Token->Save() ) {
			self::error($this->config->item("api_error_while_saving_code"));
			return;
		}

		$this->response($Token->Export(),$this->config->item("api_created_code"));
	}

	/**
	 * This endpoint updates a Token
	 * @param integer :id The database id of the token to update
	 * @since 1.0
	 * @access public
	 */
	public function index_put () {
		$Token = new Token();

		if ( ! $this->get('id') ) {  
           	self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        if ( ! $Token->Load($this->get('id')) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

		if ( ! $Token->Import($this->put(),true) ) {
			self::error($this->config->item("api_bad_request_code"));
			return;
		}

		if ( ! $Token->Save() ) {
			self::error($this->config->item("api_error_while_saving_code"));
			return;
		}

		//Revalidate translations

		$this->response($Token->Export(), $this->config->item("api_accepted_code"));
	}

	/**
	 * This endpoint deletes a Token by it's :id
	 * @since 1.0
	 * @access public
	 * @param integer :id The database id of the token
	 */
	public function index_delete () {
		if ( ! $this->get('id') ) {  
            self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        $Token = new Token();

        if ( ! $Token->Load( $this->get("id") ) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        $Token->Delete();
	}

	/**
	 * This function outputs all the available tokens for a project
	 * @since 1.0
	 * @access public
	 */
	public function get_tokens_get () {
		if ( ! $this->get('project_id') ) {  
           	self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        $this->load->model("project_model");

        if ( ! $this->project_model->project_exists($this->get("project_id")) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        $this->load->model("token_model");

        $result = $this->token_model->get_project_tokens($this->get("project_id"));

        if ( ! $result ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        $this->response($result);
	}
}