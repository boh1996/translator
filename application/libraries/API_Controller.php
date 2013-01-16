<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/REST_Controller.php');  

/**
 * API Controller for CodeIgniter
 *
 * An Wrapper for the REST Controller
 *
 * @uses 			API Controller Extends the REST Controller <https://github.com/philsturgeon/codeigniter-restserver>
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author        	Bo Thomsen
 * @version 		1.0
 */
class API_Controller extends REST_Controller{

	/**
	 * The current user
	 * @since 1.0
	 * @access public
	 * @var object
	 */
	public $user = null;

	public function API_Controller () {
		parent::__construct();
		$this->load->config("api");
	}

	/**
	 * This function fires an error
	 * @param  integer $code The http error code
	 * @since 1.0
	 * @access private
	 */
	protected function error ( $code ) {
		if ( $code !== 404 ) {
			$this->load->helper("http");
			$this->response(array(
				"code" => $code,
				"message" => Status_Message($code)
			), $code);
		} else {
			$this->response(array());
		}
	}
}
?>