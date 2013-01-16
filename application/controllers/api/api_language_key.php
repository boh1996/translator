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
	}

	public function index_get () {
		if ( ! $this->get('id') ) {  
           	self::error(400);
            return; 
        }
	}

	public function index_post () {

	}

	public function index_put () {

	}

	public function index_delete () {
		if ( ! $this->delete('id') ) {  
            self::error(400);
            return; 
        }
	}
}