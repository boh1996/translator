<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'libraries/API_Controller.php');  

class API_Language_Key extends API_Controller {

	/**
	 * This function is called on any request send to this endpoint,
	 * it loads up all the needed files
	 * @since 1.0
	 * @access public
	 */
	public function __construct () {
		parent::__construct();
		$this->load->library("key");
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

        if ( ! $Key->Load($this->get("id")) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        $Key->Delete();

        $this->response = array();
	}
}