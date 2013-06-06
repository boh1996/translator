<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

/**
 * This object holds a language file
 * 
 * @author Bo Thomsen <bo@illution.dk>
 * @version 1.0
 * @license http://opensource.org/licenses/MIT MIT License
 * @uses Std_Library This object is dependent of the Illution ORM
 */
class File extends Std_Library {

	/**
	 * The datebase id of the object
	 * @since 1.0
	 * @access public
	 * @var integer
	 */
	public $id = null;

	/**
	 * The name of the language file
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $name = null;

	/**
	 * The file location with the CodeIgniter directory and language directory removed,
	 * if the files is placed in the language file directory, 
	 * and not inside any subdirectories then this property will be the same as name
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $file_location = null;

	/**
	 * If all keys in the file, should be approved first before exporting
	 * 
	 * @var boolean
	 */
	public $approve_first = null;

	### Class Settings ###

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "language_files";

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
			"id",
		);
		parent::__construct();
	}
}