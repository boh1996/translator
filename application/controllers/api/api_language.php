<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'libraries/api/T_API_Controller.php');  

class API_Language extends T_API_Controller {

	/**
	 * This function is called on any request send to this endpoint,
	 * it loads up all the needed files
	 * @since 1.0
	 * @access public
	 */
	public function __construct () {
		parent::__construct();
		$this->load->library("language");
	}

	/**
	 * This function returns a language found by it's db id
	 * @since 1.0
	 * @access public
	 * @param integer :id The database id
	 */
	public function index_get () {
		if ( ! $this->get('id') ) {  
           	self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        $Language = new Language();

        if ( ! $Language->Load( $this->get("id") ) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        $this->response( $Language->Export() );
	}
}