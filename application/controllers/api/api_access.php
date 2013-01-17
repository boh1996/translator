<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'libraries/API_Controller.php');  

/**
 * Access control API
 *
 * The Access Control Controller/Endpoint
 *
 * @uses 			API Controller
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author        	Bo Thomsen
 * @version 		1.0
 */
class API_Access extends API_Controller {

	/**
	 * This function is called on any request send to this endpoint,
	 * it loads up all the needed files
	 * @since 1.0
	 * @access public
	 */
	public function __construct () {
		parent::__construct();
		$this->load->library("user");
		$this->load->model("user_roles_model");
	}

	/**
	 * This function returns the available permissions for a project
	 * @since 1.0
	 * @access public
	 * @param integer :project The id of the project to return permissions for
	 */
	public function project_get () {
		if ( ! $this->get("project") ) {
			self::error(400);
			return;
		}

		$modes = $this->user_roles_model->get_user_project_modes($this->user->id, $this->get("project"));

		if ( ! $modes || count($modes) == 0 ) {
			$this->response = array("error_code" => 403);
			return;
		}

		$this->response = $modes;
	}
}