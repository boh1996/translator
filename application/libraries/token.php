<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

/**
 * This object holds a translation tokens/pseudo variable
 * 
 * @author Bo Thomsen <bo@illution.dk>
 * @version 1.0
 * @license http://opensource.org/licenses/MIT MIT License
 * @uses Std_Library This object is dependent of the Illution ORM
 */
class Token extends Std_Library {

	/**
	 * The datebase id of the object
	 * @since 1.0
	 * @access public
	 * @var integer
	 */
	public $id = null;

	/**
	 * The translation token
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $token = null;

	/**
	 * A description of the translation token
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $description = null;

	/**
	 * The project that owns the token
	 * @since 1.0
	 * @access public
	 * @var object
	 */
	public $project = null;

	### Class Settings ###

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "tokens";

	/**
	 * This is the constructor, it configurates the std library
	 * @since 1.0
	 * @access private
	 */
	public function __construct () {
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS_ABORT_ON_NULL = true;
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = 	array(
			"token",
			"description",
			"project"
		);
		$this->_INTERNAL_OVERWRITE_ON_DUBLICATE = true;
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array(
			"id"
		);
		$this->_INTERNAL_ROW_NAME_CONVERT = array(
			"project_id" 				=> "project",
		);
		$this->_INTERNAL_LOAD_FROM_CLASS = array(
			"Project" 				=> "Project",
		);
		parent::__construct();
	}
}