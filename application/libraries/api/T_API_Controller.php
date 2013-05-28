<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'libraries/api/API_Controller.php');  

/**
 * ComputerInfo API Controller for CodeIgniter
 *
 * An Wrapper for the API Controller
 *
 * @uses 			API Controller Extends the API_Controller and REST Controller <https://github.com/philsturgeon/codeigniter-restserver>
 * @package        	ComputerInfo
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author        	Bo Thomsen
 * @version 		1.0
 */
class T_API_Controller extends API_Controller {

	/**
	 * The current user
	 *
	 * @since 1.0
	 * @access protected
	 * @var object
	 */
	public $user = null;

	/**
	 * The requested fields to use
	 * @since 1.0
	 * @var array
	 */
	public $fields = null;

	/**
	 * The number of objects to return for mass data functions
	 * @since 1.0
	 * @var integer
	 */
	public $limit = null;

	/**
	 * The starting point offset when loading mass data
	 * @since 1.0
	 * @var integer
	 */
	public $offset = null;

	/**
	 * The constructor
	 */
	public function __construct () {
		parent::__construct();
		$this->load->library("user");
	}

	/**
	 * Used to check input arguments an assembly them in an array,
	 * to use for database querying
	 * 
	 * @param  array  $parameters The input arguments to fetch, if set
	 * @return array|null
	 * @since 1.0
	 */
	protected function query ( array $parameters ) {
		$query = array();

		foreach ( $parameters as $parameter ) {
			if ( $this->args($parameter) !== false ) {
				$query[$parameter] = $this->args($parameter);
			}
		}

		return ( count($query) > 0 ) ? $query : null;
	}

	/**
	 * This function is called just before the controller methods
	 *
	 * @since 1.0
	 * @access protected
	 */
	protected function before_method () {

		if ( $this->get("fields") !== false ) {
			$this->fields = explode(",", $this->get("fields"));
		} else if ( $this->post("fields") !== false ) {
			$this->fields = explode(",", $this->post("fields"));
		}

		$this->limit = ( $this->get("limit") !== false ) ? $this->get("limit") : null;

		$this->offset = ( $this->get("offset") !== false ) ? $this->get("offset") : null;
	}

	/**
	 * Returns an array of selected fields
	 *
	 * @since 1.0
	 * @return array|null
	 */
	protected function fields () {
		return ( ! is_null($this->fields) ) ? $this->fields : null;
	}

	/**
	 * Getter for the selected limit of data
	 *
	 * @since 1.0
	 * @return integer|null
	 */
	protected function limit () {
		return ( ! is_null($this->limit) ) ? $this->limit : null;
	}

	/**
	 * Getter for the selected offset
	 *
	 * @since 1.0
	 * @return integer|null
	 */
	protected function offset () {
		return ( ! is_null($this->offset) ) ? $this->offset : null;
	}

	/**
	 * This function checks if the token is valid
	 * 
	 * @see REST_Controller->_is_key_valid
	 * @param  object  $row The database row
	 * @return boolean      If the key is valid
	 */
	protected function _is_key_valid ( $row ) {
		$this->user = new User();
		$this->user->Load($row->user_id);
		define("STD_LIBRARY_CURRENT_USER",$this->user->id);

		/*$current_time = (int)time();
		$valid = $row->created_at + $row->time_to_live > $current_time;

		return $valid && $this->user->_INTERNAL_LOADED === true;*/

		return TRUE;
	}

	/**
	 * Wraps output data in "result" object
	 * 
	 * @param  string|integer|array|object $data Data to output
	 * @param  null|integer $code Error code
	 */
	public function response ( $data = null, $code = 200) {
		if ( is_array($data) && count($data) == 0 ) {
			self::error(404);
		}

		if ( count($data) > 0 && ! isset($data["status"]) ) {
			parent::response(array("result" => $data,"error_code" => null,"error" => null,"status" => true, "code" => $code), $code);
		} else if ( count($data) > 0 ) {
			parent::response($data, $code);
		} else {
			parent::response(array(), $code);
		}
	}
}
?>