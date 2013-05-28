<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'libraries/api/T_API_Controller.php');  

class API_Languages extends T_API_Controller {

	/**
	 * This function is called on any request send to this endpoint,
	 * it loads up all the needed files
	 * @since 1.0
	 * @access public
	 */
	public function __construct () {
		parent::__construct();
		$this->load->library("language");
		$this->load->library("batch_loader");
	}

	/**
	 * Fetches a list of the available languages,
	 * use get parameters of the query parameter names to search, and use fields to select the available _fields,
	 * use :limit and :offset to select the amount of objects to output
	 * 
	 * @return array<Language>
	 */
	public function index_get () {
		$Loader = new Batch_Loader();

		$languages = ( $this->get("languages") !== false ) ? explode(",", $this->get("languages")) : null;

		if ( is_array($languages) ) {
			$this->db->where_in("id", $languages);
		}

		$parameters = array("name");

		$result = $Loader->Load("languages", "Language", null, $this->limit(), $this->offset(), $this->fields(), $this->query($parameters));

		if ( $result === false || is_null($result) ) {
			self::error(404);
		}

		$this->response($result);
	}
}