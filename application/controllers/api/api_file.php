<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'libraries/API_Controller.php');  

/**
 * File Object Controller
 *
 * The Language File Controller/Endpoint
 *
 * @uses 			API Controller
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author        	Bo Thomsen
 * @version 		1.0
 */
class API_File extends API_Controller {

	/**
	 * This function is called on any request send to this endpoint,
	 * it loads up all the needed files
	 * @since 1.0
	 * @access public
	 */
	public function __construct () {
		parent::__construct();
		$this->load->library("file");
	}

	/**
	 * This endpoint outputs a language file object,
	 * found by it's :id
	 * @param integer :id The database id of the language file to retrieve
	 * @since 1.0
	 * @access public
	 */
	public function index_get () {
		if ( ! $this->get('id') ) {  
           	self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        $File = new File();

        if ( ! $File->Load( $this->get("id") ) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        $this->response( $File->Export() );
	}

	/**
	 * This endpoint creates a new file
	 * @since 1.0
	 * @access public
	 */
	public function index_post () {
		$File = new File();

		if ( ! $File->Import($this->post()) ) {
			self::error($this->config->item("api_bad_request_code"));
			return;
		}

		if ( ! $File->Save() ) {
			self::error($this->config->item("api_error_while_saving_code"));
			return;
		}

		$this->response($File->Export(),$this->config->item("api_created_code"));

	}

	/**
	 * This endpoint updates the file with the the new data
	 * @param integer :id The database id of the file to update
	 * @since 1.0
	 * @access public
	 */
	public function index_put () {
		$File = new File();

		if ( ! $this->get('id') ) {  
           	self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        if ( ! $File->Load( $this->get("id") ) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

		if ( ! $File->Import($this->put()) ) {
			self::error($this->config->item("api_bad_request_code"));
			return;
		}

		if ( ! $File->Save() ) {
			self::error($this->config->item("api_error_while_saving_code"));
			return;
		}

		$this->response($File->Export(), $this->config->item("api_accepted_code"));
	}

	/**
	 * This endpoints deletes a language file, found by it's database :id,
	 * note that this only deletes the entry in the database, not the actual file,
	 * the file will first be deleted when some output is generated
	 * @param integer :id The database id of the language file
	 * @since 1.0
	 * @access public
	 */
	public function index_delete () {
		if ( ! $this->get('id') ) {  
            self::error($this->config->item("api_bad_request_code"));
            return; 
        }

        $File = new File();

        if ( ! $File->Load( $this->get("id") ) ) {
        	self::error($this->config->item("api_not_found_code"));
        	return;
        }

        $File->Delete();
	}
}