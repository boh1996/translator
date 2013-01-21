<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'libraries/API_Controller.php');  

/**
 * Translation Object Controller
 *
 * The Translation Controller/Endpoint
 *
 * @uses 			API Controller
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author        	Bo Thomsen
 * @version 		1.0
 */
class API_Translation extends API_Controller {

	/**
	 * This function is called on any request send to this endpoint,
	 * it loads up all the needed files
	 * @since 1.0
	 * @access public
	 */
	public function __construct () {
		parent::__construct();
		$this->load->library("translation");
	}

	/**
	 * This endpoint outputs a translation,
	 * found by it's :id
	 * @param integer :id The database id of the translation to retrieve
	 * @since 1.0
	 * @access public
	 */
	public function index_get () {
		if ( ! $this->get('id') ) {  
           	self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        $Translation = new Translation();

        if ( ! $Translation->Load( $this->get("id") ) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        $this->response( $Translation->Export() );
	}

	/**
	 * This endpoint saves a new translation
	 * @since 1.0
	 * @access public
	 */
	public function index_post () {
		$Translation = new Translation();

		if ( ! $Translation->Import($this->post()) ) {
			self::error($this->config->item("api_bad_request_code"));
			return;
		}

		if ( ! $Translation->Save() ) {
			self::error($this->config->item("api_error_while_saving_code"));
			return;
		}

		$this->response($Translation->Export(),$this->config->item("api_created_code"));

	}

	/**
	 * This function updates a translation with the new data
	 * @since 1.0
	 * @access public
	 */
	public function index_put () {
		$Translation = new Translation();

		if ( ! $this->get('id') ) {  
           	self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        if ( ! $Translation->Load( $this->get("id") ) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

		if ( ! $Translation->Import($this->put(), true) ) {
			self::error($this->config->item("api_bad_request_code"));
			return;
		}

		if ( ! $Translation->Save() ) {
			self::error($this->config->item("api_error_while_saving_code"));
			return;
		}

		$this->response($Translation->Export(), $this->config->item("api_accepted_code"));
	}

	/**
	 * This endpoints deletes a Translation, found by it's database :id
	 * @param integer :id The database id of the translation
	 * @since 1.0
	 * @access public
	 */
	public function index_delete () {
		if ( ! $this->get('id') ) {  
            self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        $Translation = new Translation();

        if ( ! $Translation->Load( $this->get("id") ) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        $Translation->Delete();
	}
}