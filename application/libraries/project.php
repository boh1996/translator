<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

/**
 * This object holds a project
 * 
 * @author Bo Thomsen <bo@illution.dk>
 * @version 1.0
 * @license http://opensource.org/licenses/MIT MIT License
 * @uses Std_Library This object is dependent of the Illution ORM
 */
class Project extends Std_Library {

	/**
	 * The datebase id of the object
	 * @since 1.0
	 * @access public
	 * @var integer
	 */
	public $id = null;

	/**
	 * The dislpay name of the project
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $name = null;

	/**
	 * The file directory for the project
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $location = null;

	/**
	 * The available files for the project
	 * @since 1.0
	 * @access public
	 * @var array
	 */
	public $files = null;

	/**
	 * The available languages for this project
	 * @since 1.0
	 * @access public
	 * @var array
	 */
	public $languages = null;

	/**
	 * The standard language for this product
	 * @since 1.0
	 * @access public
	 * @var object
	 */
	public $base_language = null;

	### Class Settings ###

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "projects";

	/**
	 * This is the constructor, it configurates the std library
	 * @since 1.0
	 * @access private
	 */
	public function __construct () {
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS_ABORT_ON_NULL = true;
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = 	array(
			"name"
		);
		$this->_INTERNAL_LOAD_FROM_CLASS = array(
			"files" 				=> "File",
			"languages"				=> "Language",
			"base_language"			=> "Language"
		);
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array(
			"id",
			"files",
			"languages"
		);
		$this->_INTERNAL_ROW_NAME_CONVERT = array(
			"base_language_id" => "base_language"
		);
		$this->_INTERNAL_SIMPLE_LOAD = 		array(
			"files" => true,
			"languages" => true
		);
		$this->_INTERNAL_LINK_PROPERTIES = array(
			"languages"			=> array("project_languages",	array("project_id" => "id"),array("language_id"), array("language_id","project_id")),
			"files"				=> array("project_language_files",	array("project_id" => "id"),array("language_file_id"), array("language_file_id","project_id")),
 		);
		parent::__construct();
	}
}