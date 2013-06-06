<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'libraries/api/T_API_Controller.php');  

class API_Export extends T_API_Controller {

	/**
	 * The export token database row
	 * 
	 * @var object
	 */
	protected $export_token = null;

	/**
	 * This function is called on any request send to this endpoint,
	 * it loads up all the needed files
	 * @since 1.0
	 * @access public
	 */
	public function __construct () {
		parent::__construct();
		$this->load->model("user_roles_model");
		$this->load->model("export_model");
	}

	protected $validate_key_function = "_validate_export_key";

	protected $methods = array(
		"project_language_get" 	=> array("key_name" => "api_key", "key_column" => "token","keys_table" => "export_tokens"),
		"project_get"			=> array("key_name" => "api_key", "key_column" => "token","keys_table" => "export_tokens"),
	);

	/**
	 * Validates the export token
	 * 
	 * @param  object $row The database row for the export token
	 * @return boolean
	 */
	protected function _validate_export_key ( $row ) {
		$this->user = new User();
		$this->user->Load($row->user_id);
		define("STD_LIBRARY_CURRENT_USER",$this->user->id);

		$modes = $this->user_roles_model->get_user_project_modes( $this->user->id, $row->project_id);

		$this->export_token = $row;

		if ( count(array_intersect($modes,array("manage","export"))) == 0 ) return false;

		return true;
	}

	public function project_language_get () {
		echo "Exporting Project Language";
	}

	public function project_get () {
		$this->load->library("Project");
		$Project = new Project();

		if ( $this->get("project_id") !== $this->export_token->project_id ) {
			self::error(403);
		}

		$modes = $this->user_roles_model->get_user_project_modes( $this->user->id, $this->get("project_id"));

		if ( count(array_intersect($modes,array("manage","export"))) == 0 ) {
			self::error(403);
		}

		if ( ! $Project->Load($this->get("project_id")) ) {
			self::error(404);
		}

		$files = array();
		$languages = array();

		$this->export_model->fetch_files_data($Project);
	}
}