<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

/**
 * This object holds a user
 * 
 * @author Bo Thomsen <bo@illution.dk>
 * @version 1.0
 * @license http://opensource.org/licenses/MIT MIT License
 * @uses Std_Library This object is dependent of the Illution ORM
 */
class User extends Std_Library {

	/**
	 * The datebase id of the object
	 * @since 1.0
	 * @access public
	 * @var integer
	 */
	public $id = null;

	/**
	 * The name of the user
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $name = null;

	/**
	 * The users overall access level,
	 * if the user has rights to create a project etc
	 * @since 1.0
	 * @access public
	 * @var object
	 */
	public $role = null;

	### Class Settings ###

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "users";

	/**
	 * This is the constructor, it configurates the std library
	 * @since 1.0
	 * @access private
	 */
	public function __construct () {
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS_ABORT_ON_NULL = true;
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = 	array(
			"name",
		);
		$this->_INTERNAL_LOAD_FROM_CLASS = array(
			"role" 				=> "Role",
		);
		$this->_INTERNAL_ROW_NAME_CONVERT = array(
			"role_id" 				=> "role"
		);
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array(
			"id",
		);
		parent::__construct();
	}

	/**
	 * This function checks if a user has specific ascess permissions
	 * @since 1.0
	 * @access public
	 * @param  array|string  $modes The mode or modes to check for
	 * @return boolean
	 */
	public function has_modes ( $modes ) {
		if ( !is_array($modes) ) {
			$modes = array($modes);
		}
		if ( !isset($this->role) && !is_object($this->role) ) {
			return false;
		}
		foreach ($modes as $mode) {
			if ( ! $this->role->has_mode($mode) ) {
				return false;
			}
		}
		return true;
	}
}