<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

/**
 * This object holds a access role
 * 
 * @author Bo Thomsen <bo@illution.dk>
 * @version 1.0
 * @license http://opensource.org/licenses/MIT MIT License
 * @uses Std_Library This object is dependent of the Illution ORM
 */
class Role extends Std_Library {

	/**
	 * The datebase id of the object
	 * @since 1.0
	 * @access public
	 * @var integer
	 */
	public $id = null;

	/**
	 * The name of the role
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $name = null;

	/**
	 * The available modes or actions for this role
	 * @since 1.0
	 * @access public
	 * @var array
	 */
	public $modes = null;

	/**
	 * The role type "project" or "user"
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $type = null;

	### Class Settings ###

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "roles";

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
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array(
			"id"
		);
		$this->_INTERNAL_DATABASE_SAVE_IGNORE = array(
			"modes"
		);
		$this->_INTERNAL_FORCE_ARRAY = 	array(
			"modes",
		);
		$this->_INTERNAL_LINK_SAVE_DUPLICATE_FUNCTION = array(
			"modes" => "OVERWRITE"
		);
		$this->_INTERNAL_LINK_PROPERTIES = array(
			"modes"			=> array("role_modes",	array("role_id" => "id"),array("mode"), array("role_id","mode"))
 		);
 		$this->_INTERNAL_LINKED_MERGE_RESULTS = array(
 			"modes"	=> true
 		);
		parent::__construct();
	}

	/**
	 * This function can be used to check if a user has the correct "mode" or "permission"
	 * @param  string  $mode The mode to check for
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function has_mode ( $mode ) {
		return (isset($this->modes) && is_array($this->modes)) ? in_array(strtolower($mode), $this->modes) : false;
	}
}