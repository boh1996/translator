<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'libraries/api/T_API_Controller.php');  

/**
 * User Object Controller
 *
 * The user information endpoint
 *
 * @uses 			T_API Controller
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author        	Bo Thomsen
 * @version 		1.0
 */
class API_User extends T_API_Controller {

	/**
	 * This function is called on any request send to this endpoint,
	 * it loads up all the needed files
	 * @since 1.0
	 * @access public
	 */
	public function __construct () {
		parent::__construct();
	}

	/**
	 * Outputs a user object, with projects->modes+roles assigned
	 * 
	 * @return object<User>
	 */
	public function index_get () {
		$user = $this->user->Export();

		$this->load->model("user_roles_model");
		$this->load->model("projects_model");

		$projects = $this->projects_model->get_user_projects($this->user->id);

		if ( count($projects) == 0 ) {
			self::error(404);
		}

		foreach ( $projects as $key => $project ) {
			$projects[$key]["modes"] = $this->user_roles_model->get_user_project_modes( $this->user->id, $project["id"]);

			$roles = $this->user_roles_model->get_user_project_roles( $this->user->id, $project["id"]);

			foreach ( $roles as $key => $role ) {
				$roles[$key] = $role->Export();
			}

			$projects[$key]["roles"] = $roles;
		}

		$user["projects"] = $projects;

		$this->response($user);
	}
}